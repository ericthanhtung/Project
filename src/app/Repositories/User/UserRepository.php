<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\EloquentAbstractRepository;

class UserRepository extends EloquentAbstractRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }
}
