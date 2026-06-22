<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Define users with their respective roles
        $users = [
            [
                'username'     => 'Super Admin',
                'email'    => 'superadmin@gmail.com',
                'password' => Hash::make('12345678'),
                'role'     => 'Super Admin',
            ],
            [
                'username'     => 'HR Manager',
                'email'    => 'hr@gmail.com',
                'password' => Hash::make('12345678'),
                'role'     => 'HR',
            ],
            [
                'username'     => 'Attendance Officer',
                'email'    => 'attendance@gmail.com',
                'password' => Hash::make('12345678'),
                'role'     => 'Attendance Officer',
            ],
            [
                'username'     => 'Employee One',
                'email'    => 'employee@gmail.com',
                'password' => Hash::make('12345678'),
                'role'     => 'Employee',
            ],
        ];

        // Create or update users and assign roles
        foreach ($users as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'username' => $data['username'],
                    'password' => $data['password'],
                ]
            );

            //  Assign role via Spatie
            $user->syncRoles([$data['role']]);
        }
    }
}