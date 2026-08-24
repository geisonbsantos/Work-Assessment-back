<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UnityResource extends JsonResource
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
            'description' => $this->description,
            'slug' => $this->slug,
            'cnes' => $this->cnes,
            'municipality' => $this->municipality,
            'created_at' => $this->created_at->format('d-m-Y'),
            'deleted_at' => $this->deleted_at ? $this->deleted_at->format('d-m-Y') : null,
        ];
    }
}
