<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class ExpertiseAreaSlugHelpers
{
    public static function prepareDataForStore(array $data): array
    {
        if (isset($data['description'])) {
            $data['slug'] = Str::slug($data['description']);
        }

        return $data;
    }
}
