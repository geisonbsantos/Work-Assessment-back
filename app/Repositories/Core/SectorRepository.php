<?php

namespace App\Repositories\Core;
use App\Models\Sector;
use App\Models\Unity;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class SectorRepository extends BaseRepository
{
    private Sector $entity;

    public function __construct(Sector $entity)
    {
        parent::__construct($entity);
        $this->entity = $entity;
    }

    public function store(array $data): void
    {
        $unity = Unity::where('id', $data['unity_id'])->first();

        if (!$unity) {
            throw new \Exception('Unidade não encontrada para o setor.');
        }
        $unity_slug = $unity->slug ?? null;

        try {
            DB::beginTransaction();

            // Concatenar adescription do setor com o slug da unidade
            $data['description'] = $data['description'] . ' - ' . $unity_slug;

            $this->entity->firstOrCreate(
                [
                    'description' => $data['description'],
                    'slug' => $data['slug'],
                    'unity_id' => $data['unity_id'],
                ]
            );

            DB::commit();

        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function filter(array $filters)
    {
        // query que mostra os registros que foram deletados
        $query = $this->entity->with('unity');

        if (isset($filters['description'])) {
            $query->where('description', 'like', '%' . $filters['description'] . '%');
        }

        if (isset($filters['slug'])) {
            $query->where('slug', 'like', '%' . $filters['slug'] . '%');
        }

        if (isset($filters['unity_id'])) {
            $query->where('unity_id', $filters['unity_id']);
        }

        return $query->withTrashed()->paginate();
    }

    public function destroy(object $entity): void
    {
        $entity->delete();
    }
}
