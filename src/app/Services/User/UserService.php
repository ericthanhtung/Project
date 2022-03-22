<?php

namespace App\Services\User;

use App\Constants\Status;
use App\Constants\TypeWeb;
use App\Models\Role;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\AbstractService;
use Illuminate\Support\Facades\Hash;

class UserService extends AbstractService implements UserServiceInterface
{
    private $userRepository;

    public function __construct(
        UserRepositoryInterface $userRepository
    ) {
        $this->userRepository = $userRepository;
    }
    public function register(array $data)
    {
        try {
            $this->userRepository->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role_id' => Role::where('status', Status::PUBLIC)->where('type', TypeWeb::WEB_CUSTOMER)->first()->id,
                'status' => Status::PUBLIC,
            ]);
            return true;
        } catch (\Exception $e) {
            report($e);
            return false;
        }
    }
}
