<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use OwenIt\Auditing\Contracts\Auditable;

class UserExpertiseArea extends Authenticatable implements Auditable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'user_id',
        'expertise_area_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function expertiseArea()
    {
        return $this->belongsTo(ExpertiseArea::class);
    }
}
