<?php

use App\Models\Profile;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

it('nega acesso sem token (401)', function () {
    $this->getJson('/api/profiles')->assertUnauthorized();
    $this->postJson('/api/profiles', ['name' => 'X'])->assertUnauthorized();
    $this->putJson('/api/profiles/1', ['name' => 'X'])->assertUnauthorized();
    $this->deleteJson('/api/profiles/1')->assertUnauthorized();
});

it('nega listagem sem a ability list_perfil (403)', function () {
    actingAsUser(['outra_ability']);

    $this->getJson('/api/profiles')->assertForbidden();
});

it('lista perfis com a ability list_perfil', function () {
    actingAsUser(['list_perfil']);

    $this->getJson('/api/profiles')
        ->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json->has('data')->etc());
});

it('retorna o perfil ADMINISTRADOR em /api/profiles/1', function () {
    actingAsUser(['list_perfil']);

    $this->getJson('/api/profiles/1')
        ->assertOk()
        ->assertJsonPath('name', 'ADMINISTRADOR');
});

it('cria um novo perfil', function () {
    actingAsUser(['cad_perfil']);

    $this->postJson('/api/profiles', ['name' => 'PERFIL TESTE'])
        ->assertCreated()
        ->assertJsonPath('message', 'Registro inserido com sucesso.');

    $this->assertDatabaseHas('profiles', ['name' => 'PERFIL TESTE', 'slug' => 'perfil-teste']);
});

it('atualiza um perfil', function () {
    actingAsUser(['cad_perfil']);
    $profile = Profile::factory()->create(['name' => 'ANTIGO']);

    $this->putJson("/api/profiles/{$profile->id}", ['name' => 'NOVO'])
        ->assertOk()
        ->assertJsonPath('message', 'Registro atualizado com sucesso.');

    $this->assertDatabaseHas('profiles', ['id' => $profile->id, 'name' => 'NOVO']);
});

it('vincula abilities a um perfil', function () {
    actingAsUser(['cad_perfil']);
    $profile = Profile::factory()->create();
    $ids = \App\Models\Ability::query()->limit(3)->pluck('id')->all();

    $this->postJson("/api/profiles/{$profile->id}/abilities", ['abilities' => $ids])
        ->assertOk()
        ->assertJsonPath('message', 'Vínculo realizado com sucesso.');

    expect($profile->fresh()->abilities)->toHaveCount(3);
});
