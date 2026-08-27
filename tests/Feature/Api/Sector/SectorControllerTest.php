<?php

use App\Models\Sector;
use App\Models\Unity;

it('cria setor sem sufixo cumulativo na descrição (M1)', function () {
    actingAsUser(['cad_unities', 'list_unities']);
    $unity = Unity::factory()->create();

    $this->postJson('/api/sectors', ['description' => 'Recepção', 'unity_id' => $unity->id])
        ->assertCreated();

    $this->assertDatabaseHas('sectors', ['description' => 'Recepção', 'unity_id' => $unity->id]);
});

it('não acumula sufixo em updates sucessivos (M1)', function () {
    actingAsUser(['cad_unities']);
    $unity = Unity::factory()->create();
    $sector = Sector::factory()->create(['description' => 'Recepção', 'unity_id' => $unity->id]);

    $payload = ['description' => 'Triagem', 'unity_id' => $unity->id];
    $this->putJson("/api/sectors/{$sector->id}", $payload)->assertOk();
    $this->putJson("/api/sectors/{$sector->id}", $payload)->assertOk();

    expect($sector->fresh()->description)->toBe('Triagem');
});

it('rejeita setor com unity_id inexistente (422, não 500)', function () {
    actingAsUser(['cad_unities']);

    $this->postJson('/api/sectors', ['description' => 'X', 'unity_id' => 999999])
        ->assertStatus(422);
});

it('exclui e restaura setor', function () {
    actingAsUser(['cad_unities', 'del_unities', 'list_unities']);
    $sector = Sector::factory()->create();

    $this->deleteJson("/api/sectors/{$sector->id}")->assertOk();
    $this->assertSoftDeleted('sectors', ['id' => $sector->id]);

    $this->putJson("/api/sectors/restore/{$sector->id}")->assertOk();
    $this->assertNotSoftDeleted('sectors', ['id' => $sector->id]);
});
