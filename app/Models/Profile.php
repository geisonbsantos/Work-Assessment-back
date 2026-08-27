<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
    ];

    protected $hidden = [
        'updated_at',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'profile_id', 'id');
    }

    public function abilities()
    {
        return $this->belongsToMany(Ability::class, 'profile_abilities', 'profile_id', 'ability_id');
    }

    /**
     * @deprecated usar abilities(); mantido enquanto UserResource depende (RPI-0004 / L7).
     */
    public function abilitys()
    {
        return $this->hasMany(ProfileAbility::class, 'profile_id', 'id');
    }
}
