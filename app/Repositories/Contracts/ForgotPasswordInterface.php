<?php

namespace App\Repositories\Contracts;

interface ForgotPasswordInterface
{
    public function sendEmail(array $request): void;

    public function validToken(array $request): void;

    public function resetPassword(array $request): void;
}
