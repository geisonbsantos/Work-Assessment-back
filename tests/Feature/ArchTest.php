<?php

/*
| Testes de arquitetura (Pest Arch) — ver Referências/Padrões de Testes §9.
| Barato; pega regressão estrutural.
*/

arch('só o User estende Authenticatable')
    ->expect('App\Models')
    ->not->toExtend('Illuminate\Foundation\Auth\User')
    ->ignoring('App\Models\User');

arch('controllers de API estendem o Controller base')
    ->expect('App\Http\Controllers\Api')
    ->toExtend('App\Http\Controllers\Controller');

arch('services não dependem de Illuminate\Http\Request')
    ->expect('App\Services')
    ->not->toUse('Illuminate\Http\Request');

arch('sem debug esquecido')
    ->expect(['dd', 'dump', 'ray', 'var_dump', 'vd'])
    ->not->toBeUsed();

arch()->preset()->php();
arch()->preset()->security()->ignoring(['App\Http\Middleware']);
