<?php

namespace App\Domain\Persistence\Seeders;

use App\Domain\Persistence\Models\RoleModel;
use App\Domain\Persistence\Models\UserModel;
use App\Shared\Enums\RoleEnum;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserModel::insertOrIgnore([
            'id' => 1,
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin'),
            'role_id' => RoleEnum::ADMIN,
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
