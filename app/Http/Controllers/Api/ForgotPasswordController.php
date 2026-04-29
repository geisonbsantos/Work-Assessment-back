<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordFormRequest;
use App\Http\Requests\ResetPasswordFormRequest;
use App\Http\Requests\ValidTokenFormRequest;
use App\Services\ForgotPasswordService;
use Illuminate\Http\JsonResponse;

class ForgotPasswordController extends Controller
{
    public function __construct(
        private ForgotPasswordService $forgotPasswordService
    ) {}

    public function sendEmail(ForgotPasswordFormRequest $request): JsonResponse
    {
        $request->validated();
        $this->forgotPasswordService->sendEmail($request->all());

        return response()->json(['message' => 'Enviamos o link de redefinição de senha por e-mail!'], 200);
    }

    public function validToken(ValidTokenFormRequest $request): JsonResponse
    {
        $request->validated();
        $this->forgotPasswordService->validToken($request->all());

        return response()->json(['message' => 'O seu código é válido!'], 200);
    }

    public function resetPassword(ResetPasswordFormRequest $request): JsonResponse
    {
        $request->validated();
        $this->forgotPasswordService->resetPassword($request->all());

        return response()->json(['message' => 'Sua senha foi alterada!'], 200);
    }
}
