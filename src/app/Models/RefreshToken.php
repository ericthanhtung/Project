<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class RefreshToken extends AbstractModel
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'token',
        'expired_at',
        'type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
