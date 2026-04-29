<?php

namespace App\Repositories\Contracts;

interface ForgotPasswordInterface
{
    public function findWhereTokenAndEmail(array $request);

    public function validToken(array $request): void;

    public function resetPassword(array $request): void;

    public function sendEmail(array $request): void;
}
