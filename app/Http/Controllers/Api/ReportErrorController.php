<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReportErrorFormRequest;
use App\Mail\ReportBugMail;
use Illuminate\Support\Facades\Mail;

class ReportErrorController extends Controller
{
    public function sendEmailReport(ReportErrorFormRequest $request): void
    {
        $request->validated();
        Mail::to('sistemas.cotic@saude.ba.gov.br')
            ->send(new ReportBugMail($request->all()));
    }
}
