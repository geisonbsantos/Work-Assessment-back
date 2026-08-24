<?php

namespace App\Services;

use App\Helpers\UnitySlugHelpers;
use App\Http\Resources\UnityCollection;
use App\Http\Resources\UnityResource;
use App\Repositories\Core\UnityRepository;

class UnityService
{
    private UnityRepository $repository;

    public function __construct(UnityRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll(): UnityCollection
    {
        return new UnityCollection($this->repository->getAll());
    }

    public function paginate(int $totalPage): UnityCollection
    {
        return new UnityCollection($this->repository->paginate($totalPage));
    }

    public function findById(int $id): object
    {
        return new UnityResource($this->repository->findById($id));
    }

    public function store(array $data): void
    {
        $data = UnitySlugHelpers::prepareDataForStore($data);

        $this->repository->store($data);
    }

    public function update(array $data, int $id): void
    {
        $Unity = $this->findById($id);
        $this->repository->update($Unity, $data);
    }

    public function filter(array $filters)
    {
        return $this->repository->filter($filters);
    }

    public function destroy(int $id): void
    {
        $Unity = $this->findById($id);
        $this->repository->destroy($Unity);
    }

    public function restore(int $id)
    {
        $this->repository->restore($id);
    }
}
