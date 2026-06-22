<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

     app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Role::firstOrCreate(['name' => 'Super Admin'],  ['guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'HR'],            ['guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Attendance Officer'], ['guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Employee'],      ['guard_name' => 'web']);
    }
}
