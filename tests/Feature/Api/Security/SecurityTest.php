<?php

use App\Models\Profile;
use App\Models\User;

/*
| RPI-0002 — correções de segurança. Achados C1, C2, C3, H1, H2, M2, M5.
*/

// ── C1: SQL injection no filtro de usuários ─────────────────────────────
it('não permite SQL injection no filtro de usuários (C1)', function () {
    actingAsUser(['list_usuario']);
    User::factory()->count(2)->create();

    // payload que quebraria uma string interpolada
    $this->getJson('/api/users?email='.urlencode("') OR ('1'='1"))
        ->assertOk()
        ->assertJsonPath('total', 0); // nenhum e-mail casa com o literal
});

it('ignora colunas fora da allowlist no filtro de usuários (C1)', function () {
    actingAsUser(['list_usuario']);
    User::factory()->create(['name' => 'FULANO']);

    // "password" não é filtrável → filtro ignorado, volta a lista toda
    $this->getJson('/api/users?password=qualquercoisa')
        ->assertOk()
        ->assertJsonPath('total', fn ($t) => $t >= 1);
});

it('filtra usuários por name (allowlist)', function () {
    actingAsUser(['list_usuario']);
    User::factory()->create(['name' => 'MARIA DAS DORES']);
    User::factory()->create(['name' => 'JOAO SILVA']);

    $this->getJson('/api/users?name=maria')
        ->assertOk()
        ->assertJsonPath('total', 1)
        ->assertJsonPath('data.0.name', 'MARIA DAS DORES');
});

// ── C2: DELETE /profiles exige a ability del_perfil ─────────────────────
it('nega DELETE de perfil sem a ability del_perfil (C2)', function () {
    actingAsUser(['cad_perfil', 'list_perfil']); // tudo menos del_perfil
    $profile = Profile::factory()->create();

    $this->deleteJson("/api/profiles/{$profile->id}")->assertForbidden();

    $this->assertDatabaseHas('profiles', ['id' => $profile->id, 'deleted_at' => null]);
});

// ── C3: exclusão de perfil é soft e bloqueada com usuários ──────────────
it('exclui (soft) um perfil sem usuários com a ability del_perfil (C3)', function () {
    actingAsUser(['del_perfil']);
    $profile = Profile::factory()->create();

    $this->deleteJson("/api/profiles/{$profile->id}")->assertStatus(204);

    $this->assertSoftDeleted('profiles', ['id' => $profile->id]);
});

it('não exclui um perfil que tem usuários vinculados (C3)', function () {
    actingAsUser(['del_perfil']);
    $profile = Profile::factory()->create();
    User::factory()->create(['profile_id' => $profile->id]);

    $this->deleteJson("/api/profiles/{$profile->id}")
        ->assertStatus(409)
        ->assertJsonPath('error', 'Não é possível excluir um perfil com usuários vinculados.');

    $this->assertDatabaseHas('profiles', ['id' => $profile->id, 'deleted_at' => null]);
    $this->assertDatabaseHas('users', ['profile_id' => $profile->id]);
});

// ── H1: status corretos (403 / 429) ────────────────────────────────────
it('devolve 403 para permissão negada, não 500 (H1)', function () {
    actingAsUser(['nada']);
    $this->getJson('/api/users')
        ->assertForbidden()
        ->assertJsonPath('error', 'Acesso negado.');
});

// ── H2: erro não vaza detalhe interno ──────────────────────────────────
it('não vaza mensagem interna em erro 404 de recurso (H2)', function () {
    actingAsUser(['list_perfil']);

    $res = $this->getJson('/api/profiles/999999')->assertNotFound();

    expect($res->json())->toHaveKey('error')
        ->and($res->json('details') ?? '')->not->toContain('SQL')
        ->and($res->json('details') ?? '')->not->toContain('Eloquent');
});

// ── M5: log funcional não aceita user_id do cliente ────────────────────
it('grava o log funcional sempre com o user autenticado, ignorando o corpo (M5)', function () {
    $user = actingAsUser(['cad_logs']);
    $outro = User::factory()->create();

    $this->postJson('/api/user_custom_logs', [
        'action' => 'Teste',
        'user_id' => $outro->id,          // deve ser ignorado
        'user_profile_id' => 999,
    ])->assertCreated();

    $this->assertDatabaseHas('custom_user_logs', [
        'action' => 'Teste',
        'user_id' => $user->id,
        'user_profile_id' => $user->profile_id,
    ]);
    $this->assertDatabaseMissing('custom_user_logs', ['user_id' => $outro->id]);
});
