<?php

use App\Models\ExpertiseArea;

it('filtra áreas de expertise por description (H3)', function () {
    actingAsUser(['list_areas_expertise']);
    ExpertiseArea::factory()->create(['description' => 'Enfermagem Cirúrgica']);
    ExpertiseArea::factory()->create(['description' => 'Radiologia']);

    $this->getJson('/api/expertise-areas/filter?description=Enfermagem')
        ->assertOk()
        ->assertJsonPath('total', 1)
        ->assertJsonPath('data.0.description', 'Enfermagem Cirúrgica');
});

it('sem filtro retorna todas', function () {
    actingAsUser(['list_areas_expertise']);
    ExpertiseArea::factory()->count(3)->create();

    $this->getJson('/api/expertise-areas/filter')
        ->assertOk()
        ->assertJsonPath('total', 3);
});
