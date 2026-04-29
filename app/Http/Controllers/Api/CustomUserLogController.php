<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CustomUserLogService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CustomUserLogController extends Controller
{
    public function __construct(private Request $request, private CustomUserLogService $service)
    {
    }

    public function index(): Response
    {
        if ($this->request->input()) {
            return response($this->service->applyFilter($this->request->input(), 200));
        } else {
            return response($this->service->getAll(), 200);
        }
    }

    public function store(Request $request)
    {
        $this->service->store($request->all());

        return response()->json(['message' => 'Registro inserido com sucesso.'], 201);
    }
}
