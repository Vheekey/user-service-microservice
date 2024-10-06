<?php

namespace App\Domain\Persistence\Seeders;

use App\Domain\Persistence\Models\RoleModel;
use App\Shared\Enums\RoleEnum;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RoleModel::insertOrIgnore(RoleEnum::all());
    }
}
