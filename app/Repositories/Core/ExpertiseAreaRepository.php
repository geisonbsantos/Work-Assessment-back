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

    public function filter(array $filters)
    {
        // query que mostra os registros que foram deletados
        $query = $this->entity;

        if (isset($filters['description'])) {
            $query->where('description', 'like', '%' . $filters['description'] . '%');
        }

        if (isset($filters['slug'])) {
            $query->where('slug', 'like', '%' . $filters['slug'] . '%');
        }

        return $query->withTrashed()->paginate();
    }

    public function destroy(object $entity): void
    {
        $entity->delete();
    }
}
