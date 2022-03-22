<?php

namespace App\Services\Authentication;

use App\Constants\Status;
use App\Constants\TypeWeb;
use App\Models\RefreshToken;
use App\Services\AbstractService;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthenticationService extends AbstractService implements AuthenticationServiceInterface
{
    public function login(array $data, string $guard)
    {
        try {
            if (Auth::guard($guard)->attempt([
                'email' => $data['email'],
                'password' => $data['password'],
                'status' => 1
            ]) &&
                Auth::guard($guard)->user()->role->type == TypeWeb::WEB_ADMIN &&
                Auth::guard($guard)->user()->role->status == Status::PUBLIC
            ) {
                return Auth::guard($guard)->user();
            }
        } catch (\Exception $e) {
            report($e);
            return false;
        }
    }

    public function generateJwtToken($user = null): string
    {
        try {
            $refreshToken = $this->generateRefreshToken();
            $user->refreshToken()->create([
                'token' => $refreshToken,
            ]);
            return JWT::encode([
                'id' => $user->id,
                'email' => $user->email,
                'avatar_path' => $user->avatar_path,
                'token' => $refreshToken,
                'iat' => now()->timestamp,
            ], config('jwt.secret_key'), config('jwt.alg'));
        } catch (\Exception $e) {
            report($e);
            return false;
        }
    }

    public function generateRefreshToken(): string
    {
        $refreshToken = Str::random(64);
        if (RefreshToken::where('token', $refreshToken)->first()) {
            $this->generateRefreshToken();
        }

        return $refreshToken;
    }

    public function logout(string $guard)
    {
        try {
            Auth::guard($guard)->logout();
            return true;
        } catch (\Exception $e) {
            report($e);
            return false;
        }
    }

    public function customerLogin(array $data, string $guard)
    {
        try {
            if (Auth::guard($guard)->attempt([
                    'email' => $data['email'],
                    'password' => $data['password'],
                    'status' => 1
                ]) &&
                Auth::guard($guard)->user()->role->status == Status::PUBLIC
            ) {
                return Auth::guard($guard)->user();
            }
        } catch (\Exception $e) {
            report($e);
            return false;
        }
    }
}
