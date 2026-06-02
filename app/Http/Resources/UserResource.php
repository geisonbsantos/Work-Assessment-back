<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'cpf' => $this->maskCpf($this->cpf),
            'email' => $this->email,
            'profile' => $this->profile->name,
            'profile_id' => $this->profile_id,
            'created_at' => $this->created_at,
            'deleted_at' => $this->deleted_at,
            'abilities' => $this->abilities,
            'abilitys' => $this->profile->abilitys,
        ];
    }

    private function maskCpf(?string $cpf): ?string
    {
        if (! $cpf) {
            return null;
        }

        $cpf = preg_replace('/\D/', '', $cpf);

        if (strlen($cpf) !== 11) {
            return $cpf;
        }

        return sprintf('***.***.%s-%s', substr($cpf, 6, 3), substr($cpf, 9, 2));
    }
}
