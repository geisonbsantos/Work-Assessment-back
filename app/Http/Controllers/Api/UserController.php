<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreUpdateUserFormRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends CrudController
{
    public function __construct(UserService $service)
    {
        parent::__construct($service);
    }

    protected function beforeStore(StoreUpdateUserFormRequest $request): JsonResponse
    {
        $request->validated();

        return $this->store($request);
    }

    protected function beforeUpdate(StoreUpdateUserFormRequest $request, int $id): JsonResponse
    {
        $request->validated();

        return $this->update($request, $id);
    }

    public function restore(int $id): JsonResponse
    {
        $this->service->restore($id);

        return response()->json(['message' => 'Registro restaurado com sucesso.'], 200);
    }
}
