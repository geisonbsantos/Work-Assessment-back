<?php

namespace App\Repositories\Core;

use App\Models\Faq;
use Exception;
use Illuminate\Support\Facades\DB;
use Yajra\Pdo\Oci8\Exceptions\Oci8Exception;

class FaqRepository extends BaseRepository
{
    public function __construct(private Faq $entity)
    {
        parent::__construct($entity);
    }

    public function store(array $data): void
    {
        try {

            DB::beginTransaction();

            $this->entity->create($data);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw new Oci8Exception($e->getMessage(), 400);
        }
    }

    public function update(object $entity, array $data): void
    {
        try {
            DB::beginTransaction();

            $entity->update($data);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw new Oci8Exception($e->getMessage(), 400);
        }
    }

    public function applyFilter(array $items)
    {
        $query = $this->entity::query();
        foreach ($items as $item => $value) {
            if ($item == 'page' || $item == 'per_page') {
                continue;
            }
            if ($value) {
                if (in_array($item, ['title'])) {
                    if ($item == 'title') {
                        $query->whereRaw("UPPER($item) LIKE '%'||UPPER('".$value."')||'%'");
                    }
                }
            }
        }
        $page = ($item === 'per_pege') ? $page = $value : $page = 10;

        return $query->orderBy('title')->paginate($page);
    }
}
