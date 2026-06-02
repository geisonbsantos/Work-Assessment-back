<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class ReportErrorFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('cpf')) {
            $cpf = preg_replace('/\D/', '', $this->input('cpf'));
            $this->merge(['cpf_hash' => hash('sha256', $cpf)]);
        }

        return;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'description' => 'required|string',
            'subject' => 'required|string',
            'cpf' => 'required|cpf',
            'cpf_hash' => [
                'required',
                Rule::exists('users', 'cpf_hash'),
            ],
            'email' => 'required|exists:users,email',
            'name' => 'required|string',
            'system' => 'required|string',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        $response = response()->json([
            'error' => 'Erro no envio de dados.',
            'details' => $errors->messages(),
        ], 422);
        throw new HttpResponseException($response);
    }
}
