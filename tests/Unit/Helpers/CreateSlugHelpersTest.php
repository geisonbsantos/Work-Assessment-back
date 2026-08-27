<?php

use App\Helpers\CreateSlugHelpers;

it('gera slug a partir do name', function () {
    $out = CreateSlugHelpers::prepareDataForStore(['name' => 'Área Meio Ambiente']);

    expect($out['slug'])->toBe('area-meio-ambiente');
});

it('gera slug a partir do description quando não há name', function () {
    $out = CreateSlugHelpers::prepareDataForStore(['description' => 'Recepção Central']);

    expect($out['slug'])->toBe('recepcao-central');
});

it('não adiciona slug quando não há name nem description', function () {
    expect(CreateSlugHelpers::prepareDataForStore(['foo' => 'bar']))
        ->not->toHaveKey('slug');
});
