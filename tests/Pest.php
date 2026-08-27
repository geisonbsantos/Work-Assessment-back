<?php

use App\Models\User;
use Database\Seeders\AbilitySeeder;
use Database\Seeders\ProfileAbilitySeeder;
use Database\Seeders\ProfileSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case + banco
|--------------------------------------------------------------------------
| Feature usa RefreshDatabase (migra o :memory: a cada teste) e semeia o
| mínimo de RBAC (perfis + abilities). Ver Referências/Padrões de Testes.
*/

uses(TestCase::class, RefreshDatabase::class)
    ->beforeEach(function () {
        $this->seed([
            ProfileSeeder::class,
            AbilitySeeder::class,
            ProfileAbilitySeeder::class,
        ]);

        // O login exige o captcha (mews/captcha) — neutralizado nos testes.
        Validator::extend('captcha_api', fn () => true);
    })
    ->in('Feature');

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

/**
 * Autentica como um usuário novo com as abilities informadas (Sanctum).
 * `['*']` = todas as abilities.
 */
function actingAsUser(array $abilities = ['*'], array $attributes = []): User
{
    $user = User::factory()->create($attributes);
    Sanctum::actingAs($user, $abilities);

    return $user;
}

/**
 * Token plain-text real de um usuário novo (para testar login / refreshTokenSanctum).
 */
function userToken(array $abilities = ['*'], array $attributes = []): string
{
    return User::factory()->create($attributes)
        ->createToken('test', $abilities)->plainTextToken;
}

function bearer(string $token): array
{
    return ['Authorization' => 'Bearer '.$token];
}
