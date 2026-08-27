<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReportErrorFormRequest;
use App\Mail\ReportBugMail;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

class ReportErrorController extends Controller
{
    public function sendEmailReport(ReportErrorFormRequest $request): JsonResponse
    {
        $data = $request->validated();

        Mail::to(config('mail.report_to', 'sistemas.cotic@saude.ba.gov.br'))
            ->queue(new ReportBugMail($data));

        return response()->json(['message' => 'Relato enviado. Obrigado!'], 202);
    }
}
