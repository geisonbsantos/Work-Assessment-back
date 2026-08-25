<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class CreateSlugHelpers
{
    public static function prepareDataForStore(array $data): array
    {
        // Garantir que a chave 'name' ou 'description' existe antes de tentar criar o slug
        if (isset($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        } elseif (isset($data['description'])) {
            $data['slug'] = Str::slug($data['description']);
        }

        return $data;
    }
}
