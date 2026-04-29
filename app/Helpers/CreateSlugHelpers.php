<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class CreateSlugHelpers
{
    public static function prepareDataForStore(array $data): array
    {
        if (isset($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        return $data;
    }
}
