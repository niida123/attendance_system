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
use App\Http\Controllers\LeaveRequestController; // ← ADD THIS

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Employees
    Route::get('employees/data', [EmployeeController::class, 'getData']);
    Route::get('positions/by-department/{department_id}', [PositionController::class, 'byDepartment'])->name('positions.byDepartment');
    Route::resource('employees', EmployeeController::class);

    // Departments
    Route::get('departments/data', [DepartmentController::class, 'getData']);
    Route::resource('departments', DepartmentController::class);

    // Positions
    Route::get('positions/data', [PositionController::class, 'getData'])->name('positions.data');
    Route::resource('positions', PositionController::class);

    // Roles
    Route::get('roles/data', [RoleController::class, 'getData'])->name('roles.data');
    Route::resource('roles', RoleController::class);

    // Attendance
    Route::get('attendance/data',   [AttendanceController::class, 'getData'])->name('attendance.data');
    Route::get('attendance/today',  [AttendanceController::class, 'today'])->name('attendance.today');
    Route::get('attendance/recent', [AttendanceController::class, 'recent'])->name('attendance.recent');
    Route::get('my-attendance',     [AttendanceController::class, 'myAttendance'])->name('attendance.my');
    Route::get('check-in-out',      [AttendanceController::class, 'showCheckinPage'])->name('attendance.checkin.page');
    Route::post('attendance/check-in',  [AttendanceController::class, 'checkIn'])->name('attendance.checkin');
    Route::post('attendance/check-out', [AttendanceController::class, 'checkOut'])->name('attendance.checkout');
    Route::resource('attendance', AttendanceController::class);

    // Attendance Logs
    Route::get('attendance-logs/data', [AttendanceLogController::class, 'getData'])->name('attendance-logs.data');
    Route::resource('attendance-logs', AttendanceLogController::class);

    // Leave Types
    Route::get('leave-types/data', [LeaveTypeController::class, 'getData'])->name('leave-types.data');
    Route::resource('leave-types', LeaveTypeController::class);

    // ── Leave Requests ─────────────────────────────────────────────
    Route::get('leave-requests/data',        [LeaveRequestController::class, 'getData'])->name('leave-requests.data');
    Route::get('leave-requests/my-data',     [LeaveRequestController::class, 'myData'])->name('leave-requests.my-data');
    Route::get('leave-requests/my-balance',  [LeaveRequestController::class, 'myBalance'])->name('leave-requests.my-balance');
    Route::get('leave-requests/my-leave',    [LeaveRequestController::class, 'myLeave'])->name('leave-requests.my-leave');
    Route::post('leave-requests/{id}/approve', [LeaveRequestController::class, 'approve'])->name('leave-requests.approve');
    Route::post('leave-requests/{id}/reject',  [LeaveRequestController::class, 'reject'])->name('leave-requests.reject');
    Route::post('leave-requests/{id}/cancel',  [LeaveRequestController::class, 'cancel'])->name('leave-requests.cancel');
    Route::resource('leave-requests', LeaveRequestController::class);
    // ───────────────────────────────────────────────────────────────

    // Holidays
    Route::get('holidays/data', [HolidayController::class, 'getData'])->name('holidays.data');
    Route::resource('holidays', HolidayController::class);

    // Employee Shifts
    Route::get('employee_shifts/data', [EmployeeShiftController::class, 'getData'])->name('employee_shifts.data');
    Route::resource('employee_shifts', EmployeeShiftController::class);

    // Shifts
    Route::get('shifts/data', [ShiftController::class, 'getData'])->name('shifts.data');
    Route::resource('shifts', ShiftController::class);

    // Users
    Route::get('users/data', [UserController::class, 'getData'])->name('users.data');
    Route::resource('users', UserController::class);

    // Old leaves resource (keep if LeaveController is still used, remove if replaced)
    Route::resource('leaves', LeaveController::class);

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';