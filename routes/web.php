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



Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| AUTH PROTECTED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('employees/data', [EmployeeController::class, 'getData']);
    Route::get('positions/by-department/{department_id}', [PositionController::class, 'byDepartment'])
     ->name('positions.byDepartment');
    Route::resource('employees', EmployeeController::class);
    Route::get('departments/data', [DepartmentController::class, 'getData']);
    Route::resource('departments', DepartmentController::class);
    Route::get('positions/data', [PositionController::class, 'getData'])->name('positions.data');
    Route::resource('positions', PositionController::class);
    Route::get('roles/data', [RoleController::class, 'getData'])->name('roles.data');
    Route::resource('roles', RoleController::class);
    Route::get('attendance/data',    [AttendanceController::class, 'getData'])->name('attendance.data');
    Route::get('attendance/today',   [AttendanceController::class, 'today'])->name('attendance.today');
    Route::get('attendance/recent',  [AttendanceController::class, 'recent'])->name('attendance.recent');
    Route::get('my-attendance', [AttendanceController::class, 'myAttendance'])->name('attendance.my');
    Route::get('check-in-out', [AttendanceController::class, 'showCheckinPage'])->name('attendance.checkin.page');
    Route::post('attendance/check-in',  [AttendanceController::class, 'checkIn'])->name('attendance.checkin');
    Route::post('attendance/check-out', [AttendanceController::class, 'checkOut'])->name('attendance.checkout');
    Route::resource('attendance', AttendanceController::class);
    Route::resource('attendance-logs', AttendanceLogController::class);
    Route::resource('leaves', LeaveController::class);
    Route::get('leave-types/data', [LeaveTypeController::class, 'getData'])->name('leave-types.data');
    Route::resource('leave-types', LeaveTypeController::class);
    Route::get('holidays/data', [HolidayController::class, 'getData'])->name('holidays.data');
    Route::resource('holidays', HolidayController::class);
    Route::get('shifts/data', [ShiftController::class, 'getData'])->name('shifts.data');
    Route::resource('shifts', ShiftController::class);
    Route::get('users/data', [UserController::class, 'getData'])->name('users.data');
    Route::resource('users', UserController::class);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
