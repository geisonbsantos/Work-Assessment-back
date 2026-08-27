<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StoreUpdateUserFormRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    /**
     * CPF normalizado (só dígitos) antes da validação — achado M8.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('cpf')) {
            $this->merge(['cpf' => User::normalizeCpf($this->input('cpf'))]);
        }
    }

    public function rules()
    {
        $id = $this->route('id');
        $isUpdate = in_array($this->method(), ['PUT', 'PATCH'], true);

        return [
            'name' => 'required|max:255|string',
            'cpf' => [
                'required',
                'cpf',
                Rule::unique('users', 'cpf')->ignore($id)->withoutTrashed(),
            ],
            'email' => [
                'required', 'email', 'min:3', 'max:255',
                Rule::unique('users', 'email')->ignore($id)->withoutTrashed(),
            ],
            'profile_id' => 'required|exists:profiles,id',
            'unity_id' => $isUpdate ? 'sometimes|required|exists:unities,id' : 'required|exists:unities,id',
            'sector_id' => $isUpdate ? 'sometimes|required|exists:sectors,id' : 'required|exists:sectors,id',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $v) {
            // Só um ADMINISTRADOR pode atribuir/definir o perfil ADMINISTRADOR (id 1) — achado H5.
            if ((int) $this->input('profile_id') === 1 && (int) optional($this->user())->profile_id !== 1) {
                $v->errors()->add('profile_id', 'Você não pode atribuir o perfil ADMINISTRADOR.');
            }
        });
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'error' => 'Erro no envio de dados.',
            'details' => $validator->errors()->messages(),
        ], 422));
    }
}
