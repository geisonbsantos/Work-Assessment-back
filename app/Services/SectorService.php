<?php

namespace App\Services;

use App\Helpers\CreateSlugHelpers;
use App\Helpers\SectorSlugHelpers;
use App\Http\Resources\SectorCollection;
use App\Http\Resources\SectorResource;
use App\Repositories\Core\SectorRepository;

class SectorService
{
    private SectorRepository $repository;

    public function __construct(SectorRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll(): SectorCollection
    {
        return new SectorCollection($this->repository->getAll());
    }

    public function paginate(int $totalPage): SectorCollection
    {
        return new SectorCollection($this->repository->paginate($totalPage));
    }

    public function findById(int $id): object
    {
        return new SectorResource($this->repository->findById($id));
    }

    public function store(array $data): void
    {
        $data = CreateSlugHelpers::prepareDataForStore($data);

        $this->repository->store($data);
    }

    public function update(array $data, int $id): void
    {
        $Sector = $this->findById($id);
        $this->repository->update($Sector, $data);
    }

    public function filter(array $filters)
    {
        return $this->repository->filter($filters);
    }

    public function destroy(int $id): void
    {
        $Sector = $this->findById($id);
        $this->repository->destroy($Sector);
    }

    public function restore(int $id)
    {
        $this->repository->restore($id);
    }
}
