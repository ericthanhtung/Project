<?php

namespace App\Http\Controllers\Customer;

use App\Facades\ResponseFormat;
use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\LoginRequest;
use App\Http\Requests\Authentication\RegisterRequest;
use App\Services\Authentication\AuthenticationServiceInterface;
use App\Services\User\UserServiceInterface;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    private const GUARD = 'user';
    private AuthenticationServiceInterface $authenticationService;
    private UserServiceInterface $userService;

    public function __construct(
        AuthenticationServiceInterface $authenticationService,
        UserServiceInterface $userService
    ) {
        $this->authenticationService = $authenticationService;
        $this->userService = $userService;
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->only('name','email', 'password');
        $result = $this->userService->register($data);

        if (!$result) {
            return ResponseFormat::failure('errors.system_error', 401);
        }

        return ResponseFormat::success();
    }

    public function customerLogin(LoginRequest $request): JsonResponse
    {
        $data = $request->only(['email', 'password']);

        $user = $this->authenticationService->customerLogin($data, self::GUARD);

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

