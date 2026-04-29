<?php

namespace App\Repositories\Contracts;

interface ProfileInterface extends BaseInterface
{
    public function getAbilities(int $id);

    public function storeAbilities(array $request, int $id);
}
