<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ProfileUserUnity extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'profile_id',
        'user_unity_id',
        'user_sector_id',
    ];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    public function user_unity()
    {
        return $this->belongsTo(UserUnity::class);
    }

    public function user_sector()
    {
        return $this->belongsTo(UserSector::class);
    }
}
