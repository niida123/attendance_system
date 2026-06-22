<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [

            // Dashboard
            'dashboard.view',

            // Employee Management
            'employee.view',
            'department.view',
            'position.view',

            // User & Role Management
            'user.view',
            'role.view',

            // Attendance
            'attendance.check',
            'attendance.my',
            'attendance.view',

            // Shift
            'shift.view',
            'employee_shift.view',

            // Leave
            'leave.my',
            'leave.view',
            'leave_type.view',

            // Holiday
            'holiday.view',
            'holiday.create',
            'holiday.edit',
            'holiday.delete',

            // Profile
            'profile.view',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        // Create Roles and Assign Permissions
        $superAdmin       = Role::firstOrCreate(['name' => 'Super Admin',        'guard_name' => 'web']);
        $hr               = Role::firstOrCreate(['name' => 'HR',                 'guard_name' => 'web']);
        $attendanceOfficer = Role::firstOrCreate(['name' => 'Attendance Officer', 'guard_name' => 'web']);
        $employee         = Role::firstOrCreate(['name' => 'Employee',           'guard_name' => 'web']);

        // Super Admin
        $superAdmin->syncPermissions(Permission::all());

        // HR
        $hr->syncPermissions([
            'dashboard.view',

            'employee.view',
            'department.view',
            'position.view',

            'attendance.check',
            'attendance.my',
            'attendance.view',

            'leave.my',
            'leave.view',
            'leave_type.view',

            'holiday.view',
            'holiday.create',
            'holiday.edit',

            'profile.view',
        ]);

        // Attendance Officer
        $attendanceOfficer->syncPermissions([
            'dashboard.view',

            'attendance.check',
            'attendance.my',
            'attendance.view',

            'shift.view',
            'employee_shift.view',

            'holiday.view',

            'profile.view',
        ]);

        // Employee
        $employee->syncPermissions([
            'dashboard.view',

            'attendance.check',
            'attendance.my',

            'leave.my',

            'holiday.view',

            'profile.view',
        ]);
    }
}