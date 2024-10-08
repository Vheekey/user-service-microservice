<?php

namespace App\Domain\Persistence\Seeders;

use App\Application\UseCases\GenerateAuthToken;
use App\Domain\Persistence\Models\UserModel;
use App\Domain\User\Entities\User;
use App\Shared\Enums\RoleEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    private GenerateAuthToken $generateAuthToken;

    public function __construct(GenerateAuthToken $generateAuthToken)
    {
        $this->generateAuthToken = $generateAuthToken;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $name = 'Admin';
            $email = 'admin@admin.com';
            $password = bcrypt('admin');
            $role = RoleEnum::ADMIN;

            $inserted = UserModel::insertOrIgnore([
                'id' => 1,
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'role_id' => $role,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            if ($inserted) {
                $userEntity = $this->createUserEntity($email, $name, $password, $role);

                $this->generateAuthTokenForUser($userEntity);
            }
        });
    }

    private function createUserEntity(string $email, string $name, string $password, RoleEnum $role): User
    {
        return new User($email, $name, $password, $role->value);
    }

    private function generateAuthTokenForUser(User $user): void
    {
        $this->generateAuthToken->execute($user, ['is:admin']);
    }
}
