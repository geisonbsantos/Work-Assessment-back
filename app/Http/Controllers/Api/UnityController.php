<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\CrudController;
use App\Http\Requests\StoreUpdateUnityFormRequest;
use App\Services\UnityService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class UnityController extends CrudController
{
    public function __construct(UnityService $service)
    {
        parent::__construct($service);
    }

    protected function beforeStore(StoreUpdateUnityFormRequest $request): JsonResponse
    {
        $request->validated();

        return $this->store($request);
    }

    protected function beforeUpdate(StoreUpdateUnityFormRequest $request, string $uuid): JsonResponse
    {
        $request->validated();

        return $this->update($request, $uuid);
    }

    public function filter(Request $request)
    {
        return $this->service->filter($request->all());
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->destroy($id);

        return response()->json(['message' => 'Registro deletado com sucesso.'], 200);
    }

    public function restore(int $id): JsonResponse
    {
        $this->service->restore($id);

        return response()->json(['message' => 'Registro restaurado com sucesso.'], 200);
    }
}
