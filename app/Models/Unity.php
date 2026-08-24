<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use OwenIt\Auditing\Contracts\Auditable;

class Unity extends Authenticatable implements Auditable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'description',
        'slug',
        'cnes',
        'municipality',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'unity_id', 'id');
    }

    public function sectors()
    {
        return $this->hasMany(Sector::class, 'unity_id', 'id');
    }
}
