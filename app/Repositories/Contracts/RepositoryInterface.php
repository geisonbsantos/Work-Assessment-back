<?php

namespace App\Repositories\Contracts;

interface RepositoryInterface
{
    public function getAll();

    public function findById(int $id);

    public function findWhere(string $column, string $value);

    public function findWhereFirst(string $column, string $value);

    public function paginate(int $totalPage);

    public function store(array $data);

    public function update(object $entity, array $data);

    public function destroy(object $entity);

    public function restore(int $id);
}
