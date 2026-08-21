<?php

namespace App\Repositories\Core;

use App\Models\ResetPassword;

class PasswordResetRepository extends BaseRepository
{
    private ResetPassword $entity;

    public function __construct(ResetPassword $entity)
    {
        parent::__construct($entity);
        $this->entity = $entity;
    }

    public function findWhereTokenAndEmail($token, $email)
    {
        return $this->entity->where('token', '=', $token)->where('email', '=', $email)->first();
    }
}
