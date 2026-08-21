<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\CrudController;
use App\Http\Requests\StoreUpdateExpertiseAreaFormRequest;
use App\Services\ExpertiseAreaService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use App\Http\Requests\AttachExpertiseAreaAbilitiesFormRequest;

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

    protected function getAbilities(int $id): Response
    {
        $response = $this->service->getAbilities($id);

        return response($response, 200);
    }

    protected function storeAbilities(AttachExpertiseAreaAbilitiesFormRequest $request, int $id): JsonResponse
    {
        $request->validated();
        $this->service->storeAbilities($request->all(), $id);

        return response()->json(['message' => 'Vínculo realizado com sucesso.'], 200);
    }
}
