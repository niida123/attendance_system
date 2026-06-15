<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\Holiday;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $user       = Auth::user();
        $employeeId = $user->employee_id;
        $today = Carbon::now('Asia/Phnom_Penh')->format('Y-m-d');

        $employee = \App\Models\Employee::with('position')
            ->findOrFail($employeeId);

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
                'check_in' => null,
                'check_out' => null,
                'working_hours' => null,
                'status' => 'Holiday',
                'late_minutes' => null,
            ];
        }

        // Get active shift for today
        $shift = \App\Models\EmployeeShift::with('shift')
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
            'success'    => true,
            'employee'   => $employee,
            'attendance' => $attendance,
            'shift'      => $shift,
            'is_holiday' => (bool) $holiday,
            'holiday'    => $holiday ? [
                'holiday_id' => $holiday->holiday_id,
                'holiday_name' => $holiday->holiday_name,
                'start_date' => $holiday->start_date,
                'end_date' => $holiday->end_date,
            ] : null,
        ]);
    }

    /**
     * Recent 7 records for logged-in employee (used by checkin.blade.php)
     */
    public function recent()
    {
        $employeeId = Auth::user()->employee_id;
        $today = Carbon::now('Asia/Phnom_Penh')->format('Y-m-d');

        $attendances = Attendance::where('employee_id', $employeeId)
            ->latest('attendance_date')
            ->take(7)
            ->get()
            ->map(function ($row) {

                return [
                    'attendance_date' => Carbon::parse($row->attendance_date)
                        ->timezone('Asia/Phnom_Penh')
                        ->format('Y-m-d'),

                    'check_in' => $row->check_in
                        ? Carbon::createFromFormat('H:i:s', $row->check_in)
                        ->format('H:i:s')
                        : null,

                    'check_out' => $row->check_out
                        ? Carbon::createFromFormat('H:i:s', $row->check_out)
                        ->format('H:i:s')
                        : null,

                    'working_hours' => $row->working_hours,
                    'status' => $row->status,
                    'late_minutes' => $row->late_minutes,
                ];
            });

        $todayHoliday = Holiday::where('status', 'Active')
            ->whereDate('start_date', '<=', $today)
            ->where(function ($q) use ($today) {
                $q->whereNull('end_date')
                    ->orWhereDate('end_date', '>=', $today);
            })
            ->first();

        $hasTodayAttendance = $attendances->contains(function ($row) use ($today) {
            return $row['attendance_date'] === $today;
        });

        if ($todayHoliday && !$hasTodayAttendance) {
            $attendances->prepend([
                'attendance_date' => $today,
                'check_in' => null,
                'check_out' => null,
                'working_hours' => null,
                'status' => 'Holiday',
                'late_minutes' => null,
            ]);

            $attendances = $attendances->take(7)->values();
        }

        return response()->json([
            'success' => true,
            'data'    => $attendances,
        ]);
    }

    /**
     * All attendance records — admin table (used by attendance/index.blade.php)
     */
    public function getData()
    {
        $attendances = Attendance::with('employee')
            ->latest('attendance_date')
            ->get();

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
        $employeeId = Auth::user()->employee_id;
        $today = Carbon::now('Asia/Phnom_Penh')->format('Y-m-d');

        // Save log
        AttendanceLog::create([
            'employee_id' => $employeeId,
            'log_datetime' => Carbon::now('Asia/Phnom_Penh'),
            'log_type' => 'Check In',
            'ip_address' => $request->ip(),
        ]);

        // Create or get attendance
        $attendance = Attendance::firstOrCreate(
            [
                'employee_id' => $employeeId,
                'attendance_date' => $today
            ],
            [
                'status' => 'Present',
            ]
        );

        if (!$attendance->check_in) {
            $attendance->check_in = Carbon::now('Asia/Phnom_Penh')->format('H:i:s');
            $attendance->status = 'Present';
            $attendance->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Checked in successfully.',
            'data'    => $attendance,
        ]);
    }


    /**
     * Check Out
     */
    public function checkOut(Request $request)
    {
        $employeeId = Auth::user()->employee_id;
        $today = Carbon::now('Asia/Phnom_Penh')->format('Y-m-d');

        // Save log
        AttendanceLog::create([
            'employee_id'  => $employeeId,
            'log_datetime' => Carbon::now('Asia/Phnom_Penh'),
            'log_type'     => 'Check Out',
            'ip_address'   => $request->ip(),
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

            // ← Fix: anchor both times to today to avoid timezone issues
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
