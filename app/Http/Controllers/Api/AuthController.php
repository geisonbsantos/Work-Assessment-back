<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginFormRequest;
use App\Http\Requests\ValidCredentialsFormRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    public function __construct(
        private UserService $service
    ) {}

    public function login(LoginFormRequest $request): JsonResponse
    {
        $request->validated();
        $response = $this->service->login($request);

        return response()->json(['message' => 'Autenticado com sucesso!', 'token' => $response], 200);
    }

    public function validCredentials(ValidCredentialsFormRequest $request): JsonResponse
    {
        $user = $this->service->validCredentials($request);

        return response()->json(['message' => 'Credenciais válidas!', 'user' => $user], 200);
    }

    public function me(Request $request): Response
    {
        return response($this->service->loggedInUser($request), 200);
    }

    public function logout(Request $request): Response
    {
        $this->service->logout($request);

        return response()->noContent();
    }
}
