<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends AbstractModel
{
    use HasApiTokens, HasFactory, Notifiable;

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar_path',
        'role_id',
        'status',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function refreshToken()
    {
        return $this->hasMany(RefreshToken::class, 'user_id');
    }
}
