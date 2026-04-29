<?php

namespace App\Repositories\Core;

use App\Models\ProfileAbility;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UserRepository extends BaseRepository
{
    public function __construct(private User $entity)
    {
        parent::__construct($entity);
    }

    public function getAll(): Collection
    {
        return $this->entity->withTrashed()->get();
    }

    public function findById(int $id): object
    {
        return $this->entity->withTrashed()->findOrFail($id);
    }

    public function findWhereFirst(string $column, string $value)
    {
        return $this->entity->where($column, $value)->withTrashed()->first();
    }

    public function updatePassword(string $email, string $password): void
    {
        $this->entity::where('email', $email)->update(['password' => Hash::make($password)]);
    }

    public function paginate(int $totalPage): LengthAwarePaginator
    {
        return $this->entity->orderBy('users.name')->withTrashed()->with('profile')->paginate($totalPage);
    }

    public function getUserAbilities(int $id)
    {
        return ProfileAbility::select('abilities.slug as abilities')
            ->join('abilities', 'abilities.id', '=', 'profile_abilities.ability_id')
            ->where('profile_abilities.profile_id', '=', $id)
            ->pluck('abilities')
            ->toArray();
    }

    public function applyFilter(array $items)
    {
        $query = $this->entity::query();
        foreach ($items as $item => $value) {
            if ($item == 'page' || $item == 'per_page') {
                continue;
            }
            if ($value) {
                if (in_array($item, ['name'])) {
                    $value = mb_strtoupper($value, 'UTF-8');
                    $query->where($item, 'LIKE', "%$value%");
                } else {
                    $query->whereRaw("UPPER($item) LIKE '%'||UPPER('".$value."')||'%'");
                }
            }
        }
        $page = ($item === 'per_pege') ? $page = $value : $page = 10;

        return $query->orderBy('name')->paginate($page);
    }
}
