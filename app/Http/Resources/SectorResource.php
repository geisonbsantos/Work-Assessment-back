<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SectorResource extends JsonResource
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
            'unity_id' => $this->unity_id,
            'unity_description' => $this->unity->description,
            'unity_slug' => $this->unity->slug,
            'created_at' => $this->created_at->format('d-m-Y'),
            'deleted_at' => $this->deleted_at ? $this->deleted_at->format('d-m-Y') : null,
        ];
    }
}
