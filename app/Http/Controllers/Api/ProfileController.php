<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\AttachProfileAbilitiesFormRequest;
use App\Http\Requests\StoreUpdateProfileFormRequest;
use App\Services\ProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ProfileController extends CrudController
{
    public function __construct(private ProfileService $service)
    {
        parent::__construct($service);
    }

    protected function beforeStore(StoreUpdateProfileFormRequest $request): JsonResponse
    {
        $request->validated();

        return $this->store($request);
    }

    protected function beforeUpdate(StoreUpdateProfileFormRequest $request, string $uuid): JsonResponse
    {
        $request->validated();

        return $this->update($request, $uuid);
    }

    protected function getAbilities(int $id): Response
    {
        $response = $this->service->getAbilities($id);

        return response($response, 200);
    }

    protected function storeAbilities(AttachProfileAbilitiesFormRequest $request, int $id): JsonResponse
    {
        $request->validated();
        $this->service->storeAbilities($request->all(), $id);

        return response()->json(['message' => 'Vínculo realizado com sucesso.'], 200);
    }
}
