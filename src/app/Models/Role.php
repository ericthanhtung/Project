<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends AbstractModel
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'status',
    ];

    public function users() {
        return $this->hasMany(User::class);
    }
}
