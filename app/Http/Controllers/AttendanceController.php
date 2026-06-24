<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\Holiday;
use App\Models\Employee;
use App\Models\EmployeeShift;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index()
    {
        return view('attendance.index');
    }

    public function myAttendance()
    {
        return view('attendance.my_attendance');
    }

    public function showCheckinPage()
    {
        return view('attendance.checkin');
    }

    /**
     * Today's attendance for logged-in employee (used by checkin.blade.php)
     */
    public function today()
    {
        try {
            $user = Auth::user();

            if (!$user || !$user->employee_id) {
                return response()->json([
                    'success'     => false,
                    'no_employee' => true,
                    'user_avatar' => $user->avatar ? asset('storage/' . $user->avatar) : null,
                    'message'     => 'No employee linked to this account.',
                ]);
            }

            $employeeId = $user->employee_id;
            $today = Carbon::now('Asia/Phnom_Penh')->format('Y-m-d');

            $employee = Employee::with('position')->findOrFail($employeeId);

            $attendance = Attendance::where('employee_id', $employeeId)
                ->where('attendance_date', $today)
                ->first();

            $holiday = Holiday::where('status', 'Active')
                ->whereDate('start_date', '<=', $today)
                ->where(function ($q) use ($today) {
                    $q->whereNull('end_date')
                        ->orWhereDate('end_date', '>=', $today);
                })
                ->first();

            if (!$attendance && $holiday) {
                $attendance = (object) [
                    'attendance_date' => $today,
                    'check_in'        => null,
                    'check_out'       => null,
                    'working_hours'   => null,
                    'status'          => 'Holiday',
                    'late_minutes'    => null,
                ];
            }

            $shift = EmployeeShift::with('shift')
                ->where('employee_id', $employeeId)
                ->where('effective_from', '<=', $today)
                ->where(function ($q) use ($today) {
                    $q->whereNull('effective_to')
                        ->orWhere('effective_to', '>=', $today);
                })
                ->latest('effective_from')
                ->first()
                ?->shift;

            return response()->json([
                'success'     => true,
                'employee'    => $employee,
                'attendance'  => $attendance,
                'shift'       => $shift,
                'is_holiday'  => (bool) $holiday,
                'user_avatar' => $user->avatar ? asset('storage/' . $user->avatar) : null,
                'holiday'     => $holiday ? [
                    'holiday_id'   => $holiday->holiday_id,
                    'holiday_name' => $holiday->holiday_name,
                    'start_date'   => $holiday->start_date,
                    'end_date'     => $holiday->end_date,
                ] : null,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'file'    => basename($e->getFile()),
                'line'    => $e->getLine(),
            ], 500);
        }
    }

    /**
     * Recent 7 records for logged-in employee (used by checkin.blade.php)
     */
    public function recent()
    {
        try {
            $user = Auth::user();

            if (!$user || !$user->employee_id) {
                return response()->json(['success' => true, 'data' => []]);
            }

            $employeeId = $user->employee_id;

            $attendances = Attendance::where('employee_id', $employeeId)
                ->latest('attendance_date')
                ->take(7)
                ->get()
                ->map(function ($row) use ($employeeId) {

                    $latestLog = AttendanceLog::where('employee_id', $employeeId)
                        ->whereDate('log_datetime', $row->attendance_date)
                        ->latest('log_datetime')
                        ->first();

                    return [
                        'attendance_date' => Carbon::parse($row->attendance_date)
                            ->timezone('Asia/Phnom_Penh')
                            ->format('Y-m-d'),
                        'check_in'        => $row->check_in
                            ? Carbon::createFromFormat('H:i:s', $row->check_in)->format('H:i:s')
                            : null,
                        'check_out'       => $row->check_out
                            ? Carbon::createFromFormat('H:i:s', $row->check_out)->format('H:i:s')
                            : null,
                        'working_hours'   => $row->working_hours,
                        'status'          => $row->status,
                        'late_minutes'    => $row->late_minutes,
                        'device_name'     => $latestLog ? $latestLog->device_name  : null,
                        'gps_location'    => $latestLog ? $latestLog->gps_location : null,
                    ];
                });

            return response()->json(['success' => true, 'data' => $attendances]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ], 500);
        }
    }

    /**
     * All attendance records — admin table (used by attendance/index.blade.php)
     * Eager-loads employee -> department so the JS filter can read employee.department
     */
    public function getData()
    {
        $attendances = Attendance::with(['employee.department'])
            ->latest('attendance_date')
            ->get()
            ->map(function ($row) {
                $emp = $row->employee;

                return [
                    'attendance_id'  => $row->attendance_id,
                    'attendance_date'=> $row->attendance_date,
                    'check_in'       => $row->check_in,
                    'check_out'      => $row->check_out,
                    'working_hours'  => $row->working_hours,
                    'late_minutes'   => $row->late_minutes,
                    'overtime_hours' => $row->overtime_hours,
                    'status'         => $row->status,
                    'employee'       => $emp ? [
                        'employee_id'   => $emp->employee_id,
                        'first_name'    => $emp->first_name,
                        'last_name'     => $emp->last_name,
                        // Flat string read by the JS department filter
                        'department'    => $emp->department?->department_name ?? null,
                        'department_id' => $emp->department?->department_id  ?? null,
                    ] : null,
                ];
            });

        return response()->json([
            'success' => true,
            'data'    => $attendances,
        ]);
    }

    /**
     * Check In
     */
    public function checkIn(Request $request)
    {
        try {
            $employeeId = Auth::user()->employee_id;
            $today = Carbon::now('Asia/Phnom_Penh')->format('Y-m-d');

            $hasShift = EmployeeShift::where('employee_id', $employeeId)
                ->where('effective_from', '<=', $today)
                ->where(function ($q) use ($today) {
                    $q->whereNull('effective_to')
                        ->orWhere('effective_to', '>=', $today);
                })
                ->exists();

            if (!$hasShift) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have no shift assigned for today. Please contact your administrator.',
                ], 422);
            }

            $existing = Attendance::where('employee_id', $employeeId)
                ->where('attendance_date', $today)
                ->whereNotNull('check_in')
                ->first();

            if ($existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already checked in today.',
                ]);
            }

            AttendanceLog::create([
                'employee_id'  => $employeeId,
                'log_datetime' => Carbon::now('Asia/Phnom_Penh'),
                'log_type'     => 'Check In',
                'ip_address'   => $request->ip(),
                'device_name'  => substr($request->userAgent(), 0, 500),
                'gps_location' => $request->gps_location,
            ]);

            $attendance = Attendance::firstOrCreate(
                ['employee_id' => $employeeId, 'attendance_date' => $today],
                ['status' => 'Present']
            );

            if (!$attendance->check_in) {
                $attendance->check_in = Carbon::now('Asia/Phnom_Penh')->format('H:i:s');
                $attendance->status   = 'Present';
                $attendance->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Checked in successfully.',
                'data'    => $attendance,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => basename($e->getFile()),
            ], 500);
        }
    }

    /**
     * Check Out
     */
    public function checkOut(Request $request)
    {
        $user = Auth::user();

        if (!$user->employee_id) {
            return response()->json([
                'success' => false,
                'message' => 'Your account is not linked to an employee record.',
            ], 422);
        }

        $employeeId = $user->employee_id;
        $today = Carbon::now('Asia/Phnom_Penh')->format('Y-m-d');

        AttendanceLog::create([
            'employee_id'  => $employeeId,
            'log_datetime' => Carbon::now('Asia/Phnom_Penh'),
            'log_type'     => 'Check Out',
            'ip_address'   => $request->ip(),
            'device_name'  => substr($request->userAgent(), 0, 500),
            'gps_location' => $request->gps_location,
        ]);

        $attendance = Attendance::where('employee_id', $employeeId)
            ->where('attendance_date', $today)
            ->first();

        if (!$attendance) {
            return response()->json([
                'success' => false,
                'message' => 'No check-in record found for today.',
            ], 404);
        }

        if (!$attendance->check_out) {
            $attendance->check_out = Carbon::now('Asia/Phnom_Penh')->format('H:i:s');

            $checkIn  = Carbon::parse($today . ' ' . $attendance->check_in);
            $checkOut = Carbon::parse($today . ' ' . $attendance->check_out);

            $hours = $checkIn->diffInMinutes($checkOut) / 60;
            $attendance->working_hours = round($hours, 2);
            $attendance->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Checked out successfully.',
            'data'    => $attendance,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $attendance = Attendance::findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $attendance,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();

        return response()->json([
            'success' => true,
            'message' => 'Attendance deleted successfully.',
        ]);
    }
}