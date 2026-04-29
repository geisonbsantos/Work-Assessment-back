<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreUpdateAbilityFormRequest;
use App\Services\AbilityService;
use Illuminate\Http\JsonResponse;

class AbilityController extends CrudController
{
    public function __construct(
        private AbilityService $service
    ) {
        parent::__construct($service);
    }

    protected function beforeStore(StoreUpdateAbilityFormRequest $request): JsonResponse
    {
        $request->validated();

        return $this->store($request);
    }

    protected function beforeUpdate(StoreUpdateAbilityFormRequest $request, string $uuid): JsonResponse
    {
        $request->validated();

        return $this->update($request, $uuid);
    }
}
