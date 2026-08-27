<?php

use App\Models\ResetPassword;
use App\Models\User;
use App\Services\ForgotPasswordService;
use Illuminate\Support\Facades\Mail;

it('faz login com CPF sem máscara e devolve token', function () {
    User::factory()->create(['cpf' => '11144477735', 'password' => 'segredo123']);

    $this->postJson('/api/login', ['cpf' => '11144477735', 'password' => 'segredo123', 'captcha' => 'x', 'key' => 'x'])
        ->assertOk()
        ->assertJsonStructure(['message', 'token']);
});

it('faz login mesmo se o CPF vier com máscara (M8)', function () {
    User::factory()->create(['cpf' => '11144477735', 'password' => 'segredo123']);

    $this->postJson('/api/login', ['cpf' => '111.444.777-35', 'password' => 'segredo123', 'captcha' => 'x', 'key' => 'x'])
        ->assertOk()
        ->assertJsonPath('message', 'Autenticado com sucesso!');
});

it('rejeita login com senha errada (401, sem vazar)', function () {
    User::factory()->create(['cpf' => '11144477735', 'password' => 'certa123']);

    $this->postJson('/api/login', ['cpf' => '11144477735', 'password' => 'errada', 'captcha' => 'x', 'key' => 'x'])
        ->assertUnauthorized()
        ->assertJsonPath('error', 'Erro no login.');
});

it('nega login de usuário desativado', function () {
    $u = User::factory()->create(['cpf' => '11144477735', 'password' => 'segredo123']);
    $u->delete();

    $this->postJson('/api/login', ['cpf' => '11144477735', 'password' => 'segredo123', 'captcha' => 'x', 'key' => 'x'])
        ->assertStatus(422)
        ->assertJsonPath('error', 'Usuário desativado! Favor entrar em contato com a Administração.');
});

it('login derruba tokens anteriores (sessão única)', function () {
    $u = User::factory()->create(['cpf' => '11144477735', 'password' => 'segredo123']);
    $u->createToken('antigo');
    expect($u->tokens()->count())->toBe(1);

    $this->postJson('/api/login', ['cpf' => '11144477735', 'password' => 'segredo123', 'captcha' => 'x', 'key' => 'x'])
        ->assertOk();

    expect($u->fresh()->tokens()->count())->toBe(1); // só o novo
});

it('GET /auth/me devolve o usuário logado com abilities (bug do array corrigido)', function () {
    actingAsUser(['*'], ['name' => 'FULANO DE TAL']);

    $this->getJson('/api/auth/me')
        ->assertOk()
        ->assertJsonPath('name', 'FULANO DE TAL')
        ->assertJsonStructure(['id', 'name', 'cpf', 'email', 'abilities']);
});

it('logout revoga o token do usuário', function () {
    $user = User::factory()->create();
    $plain = $user->createToken('t')->plainTextToken;

    $this->withHeaders(['Authorization' => 'Bearer '.$plain])
        ->postJson('/api/auth/logout')
        ->assertNoContent();

    expect($user->fresh()->tokens()->count())->toBe(0);
});

// ── H4: recuperação de senha ───────────────────────────────────────────
it('gera token de reset >= 8 chars e envia e-mail só se o e-mail existe (H4)', function () {
    Mail::fake();
    User::factory()->create(['email' => 'alvo@x.com']);

    $this->postJson('/api/forget-password', ['email' => 'alvo@x.com'])->assertOk();
    $this->postJson('/api/forget-password', ['email' => 'ninguem@x.com'])->assertOk();

    $reg = ResetPassword::where('email', 'alvo@x.com')->first();
    expect($reg)->not->toBeNull()
        ->and(strlen($reg->token))->toBeGreaterThanOrEqual(8);
    expect(ResetPassword::where('email', 'ninguem@x.com')->exists())->toBeFalse();
    Mail::assertQueuedCount(1);
});

it('redefine a senha com token válido, invalida o token e revoga sessões (H4)', function () {
    Mail::fake();
    $user = User::factory()->create(['email' => 'alvo@x.com', 'password' => 'velha123']);
    $user->createToken('t');

    app(ForgotPasswordService::class)->sendEmail(['email' => 'alvo@x.com']);
    $token = ResetPassword::where('email', 'alvo@x.com')->value('token');

    $this->postJson('/api/valid-token', ['email' => 'alvo@x.com', 'token' => $token])->assertOk();

    $this->postJson('/api/reset-password', [
        'email' => 'alvo@x.com', 'token' => $token,
        'password' => 'novaSenha123', 'password_confirmation' => 'novaSenha123',
    ])->assertOk();

    expect(ResetPassword::where('email', 'alvo@x.com')->exists())->toBeFalse()
        ->and($user->fresh()->tokens()->count())->toBe(0);

    $this->postJson('/api/login', ['cpf' => $user->cpf, 'password' => 'novaSenha123', 'captcha' => 'x', 'key' => 'x'])->assertOk();
});

it('rejeita token de reset expirado (H4)', function () {
    User::factory()->create(['email' => 'alvo@x.com']);
    $reg = ResetPassword::create(['email' => 'alvo@x.com', 'token' => 'ABC12345']);
    $reg->forceFill(['created_at' => now()->subHour()])->saveQuietly();

    $this->postJson('/api/valid-token', ['email' => 'alvo@x.com', 'token' => 'ABC12345'])
        ->assertStatus(422);
});
