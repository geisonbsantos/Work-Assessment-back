<?php

use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;

beforeEach(function () {

    DB::beginTransaction();
    $this->token = createUserFactoryGetToken();
});

function createUserFactoryGetToken()
{
    return User::factory()

        ->create()
        ->createToken('auth_token')
        ->plainTextToken;
}

function createProfileFactory()
{
    return Profile::factory()
        ->create()
        ->dump();
}

it('should profile get endpoint throw an unauthorized status', function () {
    $this->getJson('/api/profiles', [])

        ->assertUnauthorized();
});

it('should profile post endpoint throw an unauthorized status', function () {
    $data = [
        'name' => 'TESTE',
        'slug' => Str::slug('TESTE'),
    ];

    $this->postJson('/api/profiles', $data)
        ->assertUnauthorized();
});

it('should profile put endpoint throw an unauthorized status', function () {
    $data = [
        'name' => 'TESTE',
        'slug' => Str::slug('TESTE'),
    ];

    $this->putJson('/api/profiles/1', $data)
        ->assertUnauthorized();
});

it('should profile delete endpoint throw an unauthorized status', function () {
    $Profile = Profile::factory()
        ->create();

    $this->deleteJson('/api/profiles/'.$Profile->id)
        ->assertUnauthorized();
});

it('should profile get endpoint get all profiles', function () {
    $response = $this->getJson('/api/profiles', ['Authorization' => 'Bearer '.$this->token])
        ->assertOk();

    $response->assertJson(fn (AssertableJson $json) => $json->whereAll(['data.0.name' => 'ADMINISTRADOR'])->count('data', 1)->etc());
});

it('should profile get endpoint return a single profile', function () {
    $response = $this->getJson('/api/profiles/1', ['Authorization' => 'Bearer '.$this->token])
        ->assertOk();

    $response->assertJson(fn (AssertableJson $json) => $json->whereAll(['name' => 'ADMINISTRADOR'])
        ->etc());
});

it('should profile post endpoint create a new profile', function () {
    $data = [
        'name' => 'TESTE',
        'slug' => Str::slug('TESTE'),
    ];

    $this->postJson('/api/profiles', $data, ['Authorization' => 'Bearer '.$this->token])
        ->assertCreated()
        ->assertJson(fn (AssertableJson $json) => $json->whereAll(['message' => 'Registro inserido com sucesso.'])->etc());
});

it('should profile put endpoint update a profile', function () {
    $Profile = Profile::factory()->create();

    $data = [
        'name' => $Profile->name.' ATUALIZADO',
        'slug' => Str::slug($Profile->name.' ATUALIZADO'),
    ];

    $this->putJson('/api/profiles/'.$Profile->id, $data, ['Authorization' => 'Bearer '.$this->token])
        ->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json->whereAll(['message' => 'Registro atualizado com sucesso.'])->etc());
});

afterEach(function () {
    DB::rollBack();
});
