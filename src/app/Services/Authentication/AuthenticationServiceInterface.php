<?php

namespace App\Services\Authentication;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;

interface AuthenticationServiceInterface
{
    public function login(array $data, string $guard);

    public function generateJwtToken(User $user): string;

    public function generateRefreshToken(): string;

    public function logout(string $guard);
}
