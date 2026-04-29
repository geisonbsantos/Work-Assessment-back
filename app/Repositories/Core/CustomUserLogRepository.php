<?php

namespace App\Repositories\Core;
use App\Models\CustomUserLog;
use Illuminate\Database\Eloquent\Collection;

class CustomUserLogRepository extends BaseRepository
{
    

    public function __construct(
        private CustomUserLog $entity,

    ) {
        parent::__construct($entity);
    }

    public function getAll(): Collection
    {
        return $this->loadRelationships($this->entity)->get();
    }

    public function applyFilter(array $items)
    {
        $relationship = $this->loadRelationships($this->entity);

        $relationship->when(isset($items['start_date']), function ($query) use ($items) {
            $query->whereBetween('created_at', ["$items[start_date] 00:00:00", "$items[start_end] 23:59:59"]);
        })
            ->when(isset($items['action']), function ($query) use ($items) {
                $query->whereRaw("UPPER(action) LIKE '%'||UPPER('$items[action]')||'%'");
            })
            ->when(isset($items['profile_id']), function ($query) use ($items) {
                $query->whereHas('user', function ($query) use ($items) {
                    $query->where('profile_id', $items['profile_id']);
                });
            })
            ->when(isset($items['user_id']), function ($query) use ($items) {
                $query->where('user_id', $items['user_id']);
            });

        $items_items_per_page = isset($items['items_per_page']) ? $items['items_per_page'] : 10;

        return $this->orderAndPaginate($relationship, $items, $items_items_per_page);
    }

    private function orderAndPaginate($relationship, $filters, $perPage)
    {
        $sortBy = $filters['sortBy'] ?? 'created_at';
        $sortDesc = (isset($filters['sortDesc']) && ($filters['sortDesc'] != 'false')) ? 'ASC' : 'DESC';

        if (isset($filters['sortBy'])) {
            return $relationship->orderBy($sortBy, $sortDesc)->paginate($perPage);
        }

        return $relationship->orderBy('created_at', 'desc')->paginate($perPage);
    }

    private function loadRelationships($query)
    {
        return $query->with(
            'user:id,name,cpf',
            'profile:id,name'
        );
    }
}
