<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreUpdateExpertiseAreaFormRequest;
use App\Services\ExpertiseAreaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExpertiseAreaController extends CrudController
{
    public function __construct(ExpertiseAreaService $service)
    {
        parent::__construct($service);
    }

    protected function beforeStore(StoreUpdateExpertiseAreaFormRequest $request): JsonResponse
    {
        $request->validated();

        return $this->store($request);
    }

    protected function beforeUpdate(StoreUpdateExpertiseAreaFormRequest $request, string $uuid): JsonResponse
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
