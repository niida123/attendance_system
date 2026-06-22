<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceLogController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmployeeShiftController;
use App\Http\Controllers\LeaveRequestController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {

    // Dashboard — everyone with dashboard.view (HR + Employee both have this)
    Route::middleware('permission:dashboard.view')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/data', [DashboardController::class, 'getData'])->name('dashboard.data');

    });

    // Employees
    Route::middleware('permission:employee.view')->group(function () {
        Route::get('employees/data', [EmployeeController::class, 'getData']);
        Route::get('positions/by-department/{department_id}', [PositionController::class, 'byDepartment'])->name('positions.byDepartment');
        Route::resource('employees', EmployeeController::class);
    });

    // Departments
    Route::middleware('permission:department.view')->group(function () {
        Route::get('departments/data', [DepartmentController::class, 'getData']);
        Route::resource('departments', DepartmentController::class);
    });

    // Positions
    Route::middleware('permission:position.view')->group(function () {
        Route::get('positions/data', [PositionController::class, 'getData'])->name('positions.data');
        Route::resource('positions', PositionController::class);
    });

    // Roles
    Route::middleware('permission:role.view')->group(function () {
        Route::get('roles/data', [RoleController::class, 'getData'])->name('roles.data');
        Route::resource('roles', RoleController::class);
    });

    // Attendance — split by permission, since check-in/my/view are different audiences
    Route::middleware('permission:attendance.check')->group(function () {
        Route::get('check-in-out', [AttendanceController::class, 'showCheckinPage'])->name('attendance.checkin.page');
        Route::get('attendance/today',  [AttendanceController::class, 'today'])->name('attendance.today');   // ← move here
        Route::get('attendance/recent', [AttendanceController::class, 'recent'])->name('attendance.recent');
        Route::post('attendance/check-in', [AttendanceController::class, 'checkIn'])->name('attendance.checkin');
        Route::post('attendance/check-out', [AttendanceController::class, 'checkOut'])->name('attendance.checkout');
    });

    Route::middleware('permission:attendance.my')->group(function () {
        Route::get('my-attendance', [AttendanceController::class, 'myAttendance'])->name('attendance.my');
    });

    Route::middleware('permission:attendance.view')->group(function () {
        Route::get('attendance/data', [AttendanceController::class, 'getData'])->name('attendance.data');
        Route::resource('attendance', AttendanceController::class);

        // Attendance Logs reuse the same permission per our earlier discussion
        Route::get('attendance-logs/data', [AttendanceLogController::class, 'getData'])->name('attendance-logs.data');
        Route::resource('attendance-logs', AttendanceLogController::class);
    });

    // Shifts
    Route::middleware('permission:shift.view')->group(function () {
        Route::get('shifts/data', [ShiftController::class, 'getData'])->name('shifts.data');
        Route::resource('shifts', ShiftController::class);
    });

    // Employee Shifts
    Route::middleware('permission:employee_shift.view')->group(function () {
        Route::get('employee_shifts/data', [EmployeeShiftController::class, 'getData'])->name('employee_shifts.data');
        Route::resource('employee_shifts', EmployeeShiftController::class);
    });

    // Leave Requests — split by view vs my, same pattern as attendance
    Route::middleware('permission:leave.my')->group(function () {
        Route::get('leave-requests/my-data', [LeaveRequestController::class, 'myData'])->name('leave-requests.my-data');
        Route::get('leave-requests/my-balance', [LeaveRequestController::class, 'myBalance'])->name('leave-requests.my-balance');
        Route::get('leave-requests/my-leave', [LeaveRequestController::class, 'myLeave'])->name('leave-requests.my-leave');
        Route::post('leave-requests/{id}/cancel', [LeaveRequestController::class, 'cancel'])->name('leave-requests.cancel');
        Route::post('leave-requests', [LeaveRequestController::class, 'store'])->name('leave-requests.store'); // ← add this
    });

    Route::middleware('permission:leave.view')->group(function () {
        Route::get('leave-requests/data', [LeaveRequestController::class, 'getData'])->name('leave-requests.data');
        Route::post('leave-requests/{id}/approve', [LeaveRequestController::class, 'approve'])->name('leave-requests.approve');
        Route::post('leave-requests/{id}/reject', [LeaveRequestController::class, 'reject'])->name('leave-requests.reject');
        // Replace resource with explicit routes, excluding store (now defined above)
        Route::get('leave-requests', [LeaveRequestController::class, 'index'])->name('leave-requests.index');
        Route::get('leave-requests/{id}', [LeaveRequestController::class, 'show'])->name('leave-requests.show');
        Route::put('leave-requests/{id}', [LeaveRequestController::class, 'update'])->name('leave-requests.update');
        Route::delete('leave-requests/{id}', [LeaveRequestController::class, 'destroy'])->name('leave-requests.destroy');

        Route::resource('leaves', LeaveController::class);
    });

    // Leave Types
    Route::middleware('permission:leave_type.view')->group(function () {
        Route::get('leave-types/data', [LeaveTypeController::class, 'getData'])->name('leave-types.data');
        Route::resource('leave-types', LeaveTypeController::class);
    });

    // View Holidays
    Route::middleware('permission:holiday.view')->group(function () {
        Route::get('holidays', [HolidayController::class, 'index'])->name('holidays.index');
        Route::get('holidays/data', [HolidayController::class, 'getData'])->name('holidays.data');
        Route::get('holidays/{holiday}', [HolidayController::class, 'show'])->name('holidays.show');
    });

    // Create Holiday
    Route::middleware('permission:holiday.create')->group(function () {
        Route::post('holidays', [HolidayController::class, 'store'])->name('holidays.store');
    });

    // Edit Holiday
    Route::middleware('permission:holiday.edit')->group(function () {
        Route::put('holidays/{holiday}', [HolidayController::class, 'update'])->name('holidays.update');
    });

    // Delete Holiday
    Route::middleware('permission:holiday.delete')->group(function () {
        Route::delete('holidays/{holiday}', [HolidayController::class, 'destroy'])->name('holidays.destroy');
    });

    // Users — only Super Admin has this permission per the seeder
    Route::middleware('permission:user.view')->group(function () {
        Route::get('users/data', [UserController::class, 'getData'])->name('users.data');
        Route::resource('users', UserController::class);
    });

    // Profile — every authenticated role has profile.view
    // Route::middleware('permission:profile.view')->group(function () {
    //     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    //     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    //     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // });

    Route::middleware('permission:profile.view')->group(function () {
    Route::get('/profile',          [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/data',     [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile/info',    [ProfileController::class, 'updateInfo'])->name('profile.update-info');
    Route::post('/profile/password',[ProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::post('/profile/avatar',  [ProfileController::class, 'updateAvatar'])->name('profile.update-avatar');
    });

});

require __DIR__.'/auth.php';