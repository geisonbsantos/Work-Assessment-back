<?php

namespace App\Services;

use App\Helpers\CreateSlugHelpers;
use App\Http\Resources\ProfileCollection;
use App\Http\Resources\ProfileResource;
use App\Models\Profile;
use App\Repositories\Contracts\ProfileInterface;
use App\Repositories\Core\ProfileRepository;

class ProfileService implements ProfileInterface
{
    public function __construct(private ProfileRepository $repository) {}

    public function getAll(): ProfileCollection
    {
        return new ProfileCollection($this->repository->getAll());
    }

    public function paginate(int $totalPage): ProfileCollection
    {
        return new ProfileCollection($this->repository->paginate($totalPage));
    }

    public function findById(int $id): object
    {
        return new ProfileResource($this->repository->findById($id));
    }

    public function store(array $data): void
    {
        $data = CreateSlugHelpers::prepareDataForStore($data);

        $this->repository->store($data);
    }

    public function update(array $data, int $id): void
    {
        $profile = $this->findById($id);
        $this->repository->update($profile, $data);
    }

    public function destroy(int $id): void
    {
        $profile = $this->findById($id);
        $this->repository->destroy($profile);
    }

    public function getAbilities(int $id): Profile
    {
        return $this->repository->getAbilities($id);
    }

    public function storeAbilities(array $request, int $id): void
    {
        $profile = $this->findById($id);
        $this->repository->storeAbilities($profile, $request);
    }

    public function restore(int $id)
    {
        $this->repository->restore($id);
    }
}
