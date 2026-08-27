<?php

namespace App\Models;

use App\Observers\UserUpdatedObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use OwenIt\Auditing\Contracts\Auditable;

#[ObservedBy([UserUpdatedObserver::class])]
class User extends Authenticatable implements Auditable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'cpf',
        'email',
        'profile_id',
        'unity_id',
        'sector_id',
        'password',
    ];

    /**
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected function setPasswordAttribute($value): void
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function setNameAttribute($value): void
    {
        $this->attributes['name'] = mb_strtoupper($value, 'UTF-8');
    }

    /**
     * CPF é sempre gravado só com dígitos (achado M8).
     */
    public function setCpfAttribute($value): void
    {
        $this->attributes['cpf'] = self::normalizeCpf($value);
    }

    public static function normalizeCpf($value): string
    {
        return preg_replace('/\D/', '', (string) $value) ?? '';
    }

    public function profile()
    {
        return $this->belongsTo(Profile::class, 'profile_id');
    }

    public function unity()
    {
        return $this->belongsTo(Unity::class, 'unity_id');
    }

    public function sector()
    {
        return $this->belongsTo(Sector::class, 'sector_id');
    }

    public function expertiseAreas()
    {
        return $this->belongsToMany(ExpertiseArea::class, 'user_expertise_areas', 'user_id', 'expertise_area_id');
    }

    public function user_expertise_areas()
    {
        return $this->hasMany(UserExpertiseArea::class);
    }
}
