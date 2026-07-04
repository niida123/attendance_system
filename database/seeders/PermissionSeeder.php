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

            /*
            |--------------------------------------------------------------------------
            | Dashboard
            |--------------------------------------------------------------------------
            */
            'dashboard.view',

            /*
            |--------------------------------------------------------------------------
            | Office Management
            |--------------------------------------------------------------------------
            */
            'office.view',
            'office.create',
            'office.edit',
            'office.delete',

            /*
            |--------------------------------------------------------------------------
            | Department Management
            |--------------------------------------------------------------------------
            */
            'department.view',
            'department.create',
            'department.edit',
            'department.delete',

            /*
            |--------------------------------------------------------------------------
            | Position Management
            |--------------------------------------------------------------------------
            */
            'position.view',
            'position.create',
            'position.edit',
            'position.delete',

            /*
            |--------------------------------------------------------------------------
            | Employee Management
            |--------------------------------------------------------------------------
            */
            'employee.view',
            'employee.create',
            'employee.edit',
            'employee.delete',
            'employee.export',

            /*
            |--------------------------------------------------------------------------
            | User Management
            |--------------------------------------------------------------------------
            */
            'user.view',
            'user.create',
            'user.edit',
            'user.delete',

            /*
            |--------------------------------------------------------------------------
            | Role Management
            |--------------------------------------------------------------------------
            */
            'role.view',
            'role.create',
            'role.edit',
            'role.delete',

            /*
            |--------------------------------------------------------------------------
            | Shift Management
            |--------------------------------------------------------------------------
            */
            'shift.view',
            'shift.create',
            'shift.edit',
            'shift.delete',

            /*
            |--------------------------------------------------------------------------
            | Employee Shift
            |--------------------------------------------------------------------------
            */
            'employee_shift.view',
            'employee_shift.assign',
            'employee_shift.edit',
            'employee_shift.delete',
            'employee_shift.export',

            /*
            |--------------------------------------------------------------------------
            | Attendance
            |--------------------------------------------------------------------------
            */
            'attendance.check',
            'attendance.my',
            'attendance.view',
            'attendance.edit',
            'attendance.delete',
            'attendance.export',

            /*
            |--------------------------------------------------------------------------
            | Leave
            |--------------------------------------------------------------------------
            */
            'leave.my',
            'leave.view',
            'leave.create',
            'leave.approve',
            'leave.reject',
            'leave.delete',
            'leave.export',

            /*
            |--------------------------------------------------------------------------
            | Leave Type
            |--------------------------------------------------------------------------
            */
            'leave_type.view',
            'leave_type.create',
            'leave_type.edit',
            'leave_type.delete',

            /*
            |--------------------------------------------------------------------------
            | Holiday
            |--------------------------------------------------------------------------
            */
            'holiday.view',
            'holiday.create',
            'holiday.edit',
            'holiday.delete',

            /*
            |--------------------------------------------------------------------------
            | Reports
            |--------------------------------------------------------------------------
            */
            'reports.daily',
            'reports.monthly',
            'reports.quarterly',
            'reports.export',

            /*
            |--------------------------------------------------------------------------
            | Profile
            |--------------------------------------------------------------------------
            */
            'profile.view',
            'profile.edit',
            'profile.change_password',
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
            // Dashboard
            'dashboard.view',

            'office.view',
            'office.create',
            'office.edit',

            'department.view',
            'department.create',
            'department.edit',

            'position.view',
            'position.create',
            'position.edit',

            'employee.view',
            'employee.create',
            'employee.edit',

            'shift.view',
            'shift.create',
            'shift.edit',
            'shift.delete',

            'employee_shift.view',
            'employee_shift.assign',
            'employee_shift.edit',
            'employee_shift.delete',

            'attendance.view',
            'attendance.edit',
            'attendance.export',
            'attendance.check',
            'attendance.my',

            'leave.view',
            'leave.create',
            'leave.my',
            'leave.approve',
            'leave.reject',

            'leave_type.view',
            'leave_type.create',
            'leave_type.edit',

            'holiday.create',
            'holiday.edit',

            'reports.daily',
            'reports.monthly',
            'reports.quarterly',
            'reports.export',
        ]);

        // Attendance Officer
        $attendanceOfficer->syncPermissions([
            // Dashboard
            'dashboard.view',

            // Attendance
            'attendance.check',
            'attendance.my',
            'attendance.view',
            'attendance.edit',
            'attendance.export',

            // Shift
            'shift.view',

            // Employee Shift
            'employee_shift.view',

            // Leave
            'leave.my',
            'leave.create',   // Apply for own leave
            'leave.view',     // View all leave requests

            // Holiday
            'holiday.view',

            // Reports
            'reports.daily',
            'reports.monthly',
            'reports.quarterly',
            'reports.export',

            // Profile
            'profile.view',
            'profile.edit',
            'profile.change_password',
        ]);

        // Employee
        $employee->syncPermissions([
            // Dashboard
            'dashboard.view',

            // Attendance
            'attendance.check',
            'attendance.my',

            // Leave
            'leave.my',
            'leave.create',

            // Holiday
            'holiday.view',

            // Profile
            'profile.view',
            'profile.edit',
            'profile.change_password',
        ]);
    }
}