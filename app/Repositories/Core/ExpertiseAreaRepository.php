<?php

namespace App\Repositories\Core;
use App\Models\ExpertiseArea;
use Illuminate\Database\Eloquent\Collection;

class ExpertiseAreaRepository extends BaseRepository
{
    private ExpertiseArea $entity;

    public function __construct(ExpertiseArea $entity)
    {
        parent::__construct($entity);
        $this->entity = $entity;
    }

    public function store(array $data): void
    {
        $this->entity->firstOrCreate($data);
    }

    public function getAbilities(int $id): ExpertiseArea
    {
        return $this->entity->with('abilities')->findOrFail($id);
    }

    public function storeAbilities(object $ExpertiseArea, array $request): void
    {
        $ExpertiseArea->abilities()->sync($request['abilities']);
    }

    public function destroy(object $entity): void
    {
        $entity->delete();
    }
}
