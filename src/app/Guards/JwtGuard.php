<?php

namespace App\Guards;

use App\Constants\Status;
use App\Models\RefreshToken;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;

class JwtGuard implements Guard
{
    protected UserProvider $provider;
    protected Request $request;
    protected array $config;
    protected ?Authenticatable $user = null;
    protected ?string $refreshToken = null;

    public function __construct(UserProvider $provider, Request $request, array $config)
    {
        $this->provider = $provider;
        $this->request = $request;
        $this->config = $config;
    }

    public function check()
    {
        return $this->validate();
    }

    public function attempt(array $credentials = [])
    {
        if (empty($credentials)) {
            return false;
        }


        return $this->validate($credentials);
    }

    public function guest()
    {
        return !$this->check();
    }

    public function user()
    {
        return $this->user;
    }

    public function id()
    {
        if ($user = $this->user()) {
            return $user->id;
        }
        return null;
    }

    public function validate(array $credentials = [])
    {
        $user = null;
        if (!empty($credentials)) {
            $user = $this->provider->retrieveByCredentials($credentials);
            if (!$user || !$this->provider->validateCredentials($user, $credentials)) {
                return false;
            }
        }

        if ($user) {
            $this->setUser($user);
            return true;
        }

        $token = $this->request->bearerToken();
        if (!$token) {
            return false;
        }

        $accessCredentials = JWT::decode($token, new Key($this->config['secret_key'], $this->config['alg']));
        if (!$accessCredentials) {
            return false;
        }
        $tokenEntity = RefreshToken::where([
            'token' => $accessCredentials->token,
            'user_id' => $accessCredentials->id,
        ])->first();

        if (!$tokenEntity) {
            return false;
        }

        if (!$tokenEntity->user || $tokenEntity->user->status != Status::PUBLIC) {
            return false;
        }
        $this->setRefreshToken($tokenEntity->token);
        $this->setUser($tokenEntity->user);
        return true;
    }

    public function setUser(Authenticatable $user)
    {
        $this->user = $user;
    }

    public function logout()
    {
        $this->user()->refreshToken()->where([
            'token' => $this->getRefreshToken(),
        ])->delete();
        $this->user = null;
        $this->refreshToken = null;
    }

    public function setRefreshToken(string $refreshToken)
    {
        $this->refreshToken = $refreshToken;
    }

    public function getRefreshToken()
    {
        return $this->refreshToken;
    }
}
