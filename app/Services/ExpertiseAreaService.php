<?php

namespace App\Services;

use App\Helpers\CreateSlugHelpers;
use App\Http\Resources\ExpertiseAreaCollection;
use App\Http\Resources\ExpertiseAreaResource;
use App\Repositories\Core\ExpertiseAreaRepository;

class ExpertiseAreaService
{
    private ExpertiseAreaRepository $repository;

    public function __construct(ExpertiseAreaRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll(): ExpertiseAreaCollection
    {
        return new ExpertiseAreaCollection($this->repository->getAll());
    }

    public function paginate(int $totalPage): ExpertiseAreaCollection
    {
        return new ExpertiseAreaCollection($this->repository->paginate($totalPage));
    }

    public function findById(int $id): object
    {
        return new ExpertiseAreaResource($this->repository->findById($id));
    }

    public function store(array $data): void
    {
        $data = CreateSlugHelpers::prepareDataForStore($data);

        $this->repository->store($data);
    }

    public function update(array $data, int $id): void
    {
        $ExpertiseArea = $this->findById($id);
        $this->repository->update($ExpertiseArea, $data);
    }

    public function filter(array $filters)
    {
        return $this->repository->filter($filters);
    }

    public function destroy(int $id): void
    {
        $ExpertiseArea = $this->findById($id);
        $this->repository->destroy($ExpertiseArea);
    }

    public function restore(int $id)
    {
        $this->repository->restore($id);
    }
}
