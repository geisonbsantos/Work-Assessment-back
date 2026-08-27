<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CustomUserLogService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CustomUserLogController extends Controller
{
    public function __construct(private CustomUserLogService $service) {}

    public function index(Request $request): Response
    {
        if ($request->input()) {
            return response($this->service->applyFilter($request->input()), 200);
        }

        return response($this->service->getAll(), 200);
    }

    /**
     * O log funcional é do sistema: user_id / user_profile_id vêm sempre do
     * usuário autenticado, nunca do corpo da requisição (achado M5).
     */
    public function store(Request $request)
    {
        $data = $request->validate(['action' => 'required|string|max:255']);

        $this->service->store([
            'action' => $data['action'],
            'user_id' => $request->user()->id,
            'user_profile_id' => $request->user()->profile_id,
        ]);

        return response()->json(['message' => 'Registro inserido com sucesso.'], 201);
    }
}
