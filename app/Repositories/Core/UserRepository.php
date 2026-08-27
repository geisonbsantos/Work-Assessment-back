<?php

namespace App\Repositories\Core;

use App\Models\ProfileAbility;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UserRepository extends BaseRepository
{
    private $entity;
    public function __construct(User $entity)
    {
        parent::__construct($entity);
        $this->entity = $entity;
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

    /**
     * Colunas que podem ser filtradas em GET /api/users?<coluna>=<valor>.
     * Allowlist — evita injeção de nome de coluna no SQL.
     */
    private const FILTRAVEIS = ['name', 'cpf', 'email'];

    public function applyFilter(array $items)
    {
        $query = $this->entity->newQuery()->withTrashed()->with('profile');

        foreach ($items as $coluna => $valor) {
            if (! in_array($coluna, self::FILTRAVEIS, true) || blank($valor)) {
                continue;
            }
            // $coluna vem da allowlist (seguro interpolar); $valor é bound.
            $query->whereRaw("UPPER($coluna) LIKE ?", ['%'.mb_strtoupper((string) $valor, 'UTF-8').'%']);
        }

        return $query->orderBy('name')->paginate($items['per_page'] ?? 10);
    }
}
