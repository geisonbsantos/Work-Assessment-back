<?php

namespace App\Repositories\Contracts;

interface BaseInterface
{
    public function getAll();

    public function paginate(int $id);

    public function findById(int $id);

    public function store(array $data);

    public function update(array $request, int $id);

    public function destroy(int $id);
}
