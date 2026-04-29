<?php

namespace App\Repositories\Core;

use App\Models\ResetPassword;

class PasswordResetRepository extends BaseRepository
{
    public function __construct(private ResetPassword $entity)
    {
        parent::__construct($entity);
    }

    public function findWhereTokenAndEmail($token, $email)
    {
        return $this->entity->where('token', '=', $token)->where('email', '=', $email)->first();
    }
}
