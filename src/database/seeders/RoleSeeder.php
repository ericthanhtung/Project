<?php

namespace Database\Seeders;

use App\Constants\Status;
use App\Constants\TypeWeb;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            [
                'name' => 'customer',
                'status' => Status::PUBLIC,
                'type' => TypeWeb::WEB_CUSTOMER,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'admin',
                'status' => Status::PUBLIC,
                'type' => TypeWeb::WEB_ADMIN,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
