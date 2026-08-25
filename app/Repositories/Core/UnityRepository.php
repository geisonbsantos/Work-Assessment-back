<?php

namespace App\Repositories\Core;
use App\Models\Unity;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class UnityRepository extends BaseRepository
{
    private Unity $entity;

    public function __construct(Unity $entity)
    {
        parent::__construct($entity);
        $this->entity = $entity;
    }

    public function store(array $data): void
    {

        try {
            DB::beginTransaction();

            $this->entity->firstOrCreate($data);

            DB::commit();

        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function filter(array $filters)
    {
        // query que mostra os registros que foram deletados
        $query = $this->entity->with('sectors');

        if (isset($filters['description'])) {
            $query->where('description', 'like', '%' . $filters['description'] . '%');
        }

        if (isset($filters['slug'])) {
            $query->where('slug', 'like', '%' . $filters['slug'] . '%');
        }

        if (isset($filters['cnes'])) {
            $query->where('cnes', 'like', '%' . $filters['cnes'] . '%');
        }

        if (isset($filters['municipality'])) {
            $query->where('municipality', 'like', '%' . $filters['municipality'] . '%');
        }

        return $query->withTrashed()->paginate();
    }

    public function destroy(object $entity): void
    {
        $entity->delete();
    }
}
