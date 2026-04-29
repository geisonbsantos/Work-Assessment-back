<?php

namespace App\Repositories\Core;

use App\Models\Ability;

class AbilityRepository extends BaseRepository
{
    public function __construct(Ability $entity)
    {
        parent::__construct($entity);
    }
}
