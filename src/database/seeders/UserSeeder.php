<?php

namespace Database\Seeders;

use App\Constants\Status;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('Abcd@123'),
            'role_id' => Role::where('status', Status::PUBLIC)->first()->id,
            'status' => Status::PUBLIC,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
