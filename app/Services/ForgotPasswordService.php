<?php

namespace App\Services;

use App\Exceptions\CodeException;
use App\Mail\ForgotPasswordMail;
use App\Models\ResetPassword;
use App\Repositories\Contracts\ForgotPasswordInterface;
use App\Repositories\Core\PasswordResetRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordService implements ForgotPasswordInterface
{
    /** Minutos de validade do token de redefinição. */
    public const TOKEN_TTL_MIN = 30;

    public function __construct(
        private PasswordResetRepository $repository,
        private UserService $userService,
    ) {}

    public function sendEmail(array $request): void
    {
        $email = mb_strtolower(trim($request['email']));
        $user = $this->userService->findWhereFirst('email', $email);

        // Não revela se o e-mail existe; só age se existir.
        if (! $user) {
            return;
        }

        // Invalida pedidos anteriores do mesmo e-mail.
        ResetPassword::where('email', $email)->delete();

        $token = Str::upper(Str::random(8));
        ResetPassword::create(['email' => $email, 'token' => $token]);

        Mail::to($email)->queue(new ForgotPasswordMail(['email' => $email, 'token' => $token]));
    }

    private function findValidRegister(array $request): ResetPassword
    {
        $register = $this->repository->findWhereTokenAndEmail(
            $request['token'],
            mb_strtolower(trim($request['email'])),
        );

        if (! $register) {
            throw new CodeException('O seu código é inválido!');
        }

        if ($register->created_at?->lt(Carbon::now()->subMinutes(self::TOKEN_TTL_MIN))) {
            $register->delete();
            throw new CodeException('O seu código expirou. Solicite um novo.');
        }

        return $register;
    }

    public function validToken(array $request): void
    {
        $this->findValidRegister($request);
    }

    public function resetPassword(array $request): void
    {
        $register = $this->findValidRegister($request);

        $this->userService->updatePassword($register->email, $request['password']);
        $register->delete();
    }
}
