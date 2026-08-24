<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreUpdateExpertiseAreaFormRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'description' => 'required|string|max:255|unique:ExpertiseAreas,description',
            'slug' => 'required|string|max:50|unique:ExpertiseAreas,slug',
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $rules['description'] = "required|string|max:255|unique:ExpertiseAreas,description,{$this->segment(3)},id";
            $rules['slug'] = "required|string|max:50|unique:ExpertiseAreas,slug,{$this->segment(3)},id";
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
