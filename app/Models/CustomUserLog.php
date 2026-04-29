<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomUserLog extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'user_profile_id', 'action'];

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->toW3cString();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function profile()
    {
        return $this->belongsTo(Profile::class, 'user_profile_id', 'id');
    }
}
