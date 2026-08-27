<?php

namespace App\Repositories\Core;

use App\Models\Sector;
use App\Models\Unity;
use Illuminate\Support\Facades\DB;

class SectorRepository extends BaseRepository
{
    private Sector $entity;

    public function __construct(Sector $entity)
    {
        parent::__construct($entity);
        $this->entity = $entity;
    }

    private function assertUnity(int $unityId): void
    {
        if (! Unity::whereKey($unityId)->exists()) {
            throw new \App\Exceptions\UserException('Unidade não encontrada para o setor.', 422);
        }
    }

    public function store(array $data): void
    {
        $this->assertUnity((int) $data['unity_id']);

        DB::transaction(function () use ($data) {
            $this->entity->firstOrCreate([
                'description' => $data['description'],
                'slug' => $data['slug'],
                'unity_id' => $data['unity_id'],
            ]);
        });
    }

    public function update(object $entity, array $data): void
    {
        $this->assertUnity((int) $data['unity_id']);

        DB::transaction(function () use ($entity, $data) {
            $entity->update($data);
        });
    }

    public function filter(array $filters)
    {
        $query = $this->entity->newQuery()->withTrashed()->with('unity');

        if (filled($filters['description'] ?? null)) {
            $query->where('description', 'like', '%'.$filters['description'].'%');
        }

        if (filled($filters['slug'] ?? null)) {
            $query->where('slug', 'like', '%'.$filters['slug'].'%');
        }

        if (filled($filters['unity_id'] ?? null)) {
            $query->where('unity_id', $filters['unity_id']);
        }

        return $query->paginate();
    }

    public function destroy(object $entity): void
    {
        $entity->delete();
    }
}
