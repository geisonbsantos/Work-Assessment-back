<?php

use App\Mail\AccountCreateMail;
use App\Models\Sector;
use App\Models\Unity;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

function novoUserPayload(array $override = []): array
{
    $unity = Unity::factory()->create();

    return array_merge([
        'name' => 'Novo Usuario',
        'cpf' => fake()->unique()->numerify('###########'),
        'email' => fake()->unique()->safeEmail(),
        'profile_id' => 3,
        'unity_id' => $unity->id,
        'sector_id' => Sector::factory()->create(['unity_id' => $unity->id])->id,
    ], $override);
}

it('nega tudo sem token', function () {
    $this->getJson('/api/users')->assertUnauthorized();
});

it('nega listagem sem list_usuario (403)', function () {
    actingAsUser(['x']);
    $this->getJson('/api/users')->assertForbidden();
});

it('cria usuário, envia e-mail de credencial e grava CPF só com dígitos (H5, M8)', function () {
    Mail::fake();
    actingAsUser(['cad_usuario'])->update(['profile_id' => 1]);

    $payload = novoUserPayload(['cpf' => '111.444.777-35', 'email' => 'novo@x.com']);
    $this->postJson('/api/users', $payload)
        ->assertCreated()
        ->assertJsonPath('message', 'Registro inserido com sucesso.');

    $this->assertDatabaseHas('users', ['email' => 'novo@x.com', 'cpf' => '11144477735']);
    Mail::assertQueued(AccountCreateMail::class);
});

it('exige unity_id e sector_id ao criar usuário — 422, não 500 (H5)', function () {
    actingAsUser(['cad_usuario']);

    $this->postJson('/api/users', [
        'name' => 'X', 'cpf' => \Database\Factories\UserFactory::cpf(), 'email' => 'x@x.com', 'profile_id' => 3,
    ])
        ->assertStatus(422)
        ->assertJsonStructure(['error', 'details' => ['unity_id', 'sector_id']]);
});

it('impede não-administrador de criar usuário com perfil ADMINISTRADOR (H5)', function () {
    actingAsUser(['cad_usuario'], ['profile_id' => 3]); // não admin

    $this->postJson('/api/users', novoUserPayload(['profile_id' => 1]))
        ->assertStatus(422)
        ->assertJsonStructure(['error', 'details' => ['profile_id']]);
});

it('exclui (soft) e restaura usuário', function () {
    actingAsUser(['del_usuario', 'cad_usuario', 'list_usuario']);
    $alvo = User::factory()->create();

    $this->deleteJson("/api/users/{$alvo->id}")->assertStatus(204);
    $this->assertSoftDeleted('users', ['id' => $alvo->id]);

    $this->putJson("/api/users/restore/{$alvo->id}")->assertOk();
    $this->assertNotSoftDeleted('users', ['id' => $alvo->id]);
});

it('lista usuários sem explodir em N+1 (M6)', function () {
    actingAsUser(['list_usuario']);
    User::factory()->count(5)->create();

    \Illuminate\Support\Facades\DB::enableQueryLog();
    $this->getJson('/api/users?per_page=50')->assertOk();
    $queries = count(\Illuminate\Support\Facades\DB::getQueryLog());
    \Illuminate\Support\Facades\DB::disableQueryLog();

    // com eager loading a contagem é constante (~7), não proporcional a N
    expect($queries)->toBeLessThan(15);
});
