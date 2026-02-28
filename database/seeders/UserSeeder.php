<?php
  
namespace Database\Seeders;
  
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@local.email',
                'role' => 'admin',
            ],
            [
                'name' => 'Manager',
                'email' => 'manager@local.email',
                'role' => 'manager',
            ],
            [
                'name' => 'Cenzor',
                'email' => 'auditor@local.email',
                'role' => 'auditor',
            ],
            [
                'name' => 'Rezident',
                'email' => 'resident@local.email',
                'role' => 'resident',
            ]
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                [
                    'email' => $userData['email'],
                    'name' => $userData['name'],
                    'password' => Hash::make('AL-' . $userData['role']),
                ]
            );

            $user->assignRole($userData['role']);
        }
    }
}