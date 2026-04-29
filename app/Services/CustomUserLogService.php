<?php

namespace App\Services;

use App\Repositories\Core\CustomUserLogRepository;

class CustomUserLogService
{
    private $repository;

    public function __construct(CustomUserLogRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll()
    {
        return $this->repository->getAll();
    }

    public function applyFilter(array $data)
    {
        return $this->repository->applyFilter($data);
    }

    public function store(array $data): void
    {
        $this->repository->store($data);
    }
}
