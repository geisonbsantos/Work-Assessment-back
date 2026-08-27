<?php

namespace App\Repositories\Core;

use App\Models\ExpertiseArea;

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

    public function filter(array $filters)
    {
        // withTrashed() para listar também os registros excluídos
        $query = $this->entity->newQuery()->withTrashed();

        if (filled($filters['description'] ?? null)) {
            $query->where('description', 'like', '%'.$filters['description'].'%');
        }

        if (filled($filters['slug'] ?? null)) {
            $query->where('slug', 'like', '%'.$filters['slug'].'%');
        }

        return $query->paginate();
    }

    public function destroy(object $entity): void
    {
        $entity->delete();
    }
}
