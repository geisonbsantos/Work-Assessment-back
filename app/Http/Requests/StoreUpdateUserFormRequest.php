<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StoreUpdateUserFormRequest extends FormRequest
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
        $rules = [
            'name' => 'required|max:255|string',
            'cpf' => 'required|cpf',
            'cpf_hash' => [
                'required',
                Rule::unique('users', 'cpf_hash'),
            ],
            'email' => 'required|unique:users,email|min:3|max:255|email',
            'profile_id' => 'required|exists:profiles,id',
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $rules['cpf_hash'] = [
                'required',
                Rule::unique('users', 'cpf_hash')->ignore($this->segment(3), 'id'),
            ];
            $rules['email'] = "required|unique:users,email,{$this->segment(3)},id|min:3|max:255|email";
        }

        return $rules;
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
