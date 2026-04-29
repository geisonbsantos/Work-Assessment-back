<?php

namespace App\Repositories\Core;

use App\Models\Profile;

class ProfileRepository extends BaseRepository
{
    public function __construct(private Profile $entity)
    {
        parent::__construct($entity);
    }

    public function store(array $data): void
    {
        $this->entity->firstOrCreate($data);
    }

    public function getAbilities(int $id): Profile
    {
        return $this->entity->with('abilities')->findOrFail($id);
    }

    public function storeAbilities(object $profile, array $request): void
    {
        $profile->abilities()->sync($request['abilities']);
    }

    public function destroy(object $entity): void
    {
        $entity->delete();
    }
}
