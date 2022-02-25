<?php

namespace App\Http\Controllers\Admin;

use App\Facades\ResponseFormat;
use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\LoginRequest;
use App\Services\Authentication\AuthenticationServiceInterface;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    private const GUARD = 'admin';
    private AuthenticationServiceInterface $authenticationService;

    public function __construct(AuthenticationServiceInterface $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $data = $request->only(['email', 'password']);

        $user = $this->authenticationService->login($data, self::GUARD);

        if (!$user) {
            return ResponseFormat::failure('auth.false', 401);
        }
        $token = $this->authenticationService->generateJwtToken($user);

        return ResponseFormat::success([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function logout(): JsonResponse
    {
        $this->authenticationService->logout(self::GUARD);

        return ResponseFormat::success();
    }
}
