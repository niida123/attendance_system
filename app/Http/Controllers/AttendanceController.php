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
                        'device_name'          => $latestLog ? $latestLog->device_name  : null,
                        'gps_location'         => $latestLog ? $latestLog->gps_location : null,
                        'latitude'             => $latestLog ? $latestLog->latitude : null,
                        'longitude'            => $latestLog ? $latestLog->longitude : null,
                        'distance_from_office' => $latestLog ? $latestLog->distance_from_office : null,
                        'is_verified'          => $latestLog ? $latestLog->is_verified : null,
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

                $latestLog = AttendanceLog::where('employee_id', $row->employee_id)
                    ->whereDate('log_datetime', $row->attendance_date)
                    ->latest('log_datetime')
                    ->first();

                return [
                    'attendance_id'  => $row->attendance_id,
                    'attendance_date'=> $row->attendance_date,
                    'check_in'       => $row->check_in,
                    'check_out'      => $row->check_out,
                    'working_hours'  => $row->working_hours,
                    'late_minutes'   => $row->late_minutes,
                    'overtime_hours' => $row->overtime_hours,
                    'status'         => $row->status,
                    'is_verified'    => $latestLog?->is_verified,
                    'distance_from_office' => $latestLog?->distance_from_office,
                    'employee'       => $emp ? [
                        'employee_id'   => $emp->employee_id,
                        'first_name'    => $emp->first_name,
                        'last_name'     => $emp->last_name,
                        'department'    => $emp->department?->department_name ?? null,
                        'department_id' => $emp->department?->department_id  ?? null,
                    ] : null,
                ];
            });

        return response()->json(['success' => true, 'data' => $attendances]);
    }

    /**
     * Check In
     */
    /**
     * How many minutes before shift start an employee is allowed to check in.
     */
    private const EARLY_CHECKIN_GRACE_MINUTES = 30;

    public function checkIn(Request $request)
    {
        try {
            $employeeId = Auth::user()->employee_id;
            $today = Carbon::now('Asia/Phnom_Penh')->format('Y-m-d');
            $now   = Carbon::now('Asia/Phnom_Penh');

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

            if (!$shift) {
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

            /* ── Shift time window checks ─────────────────────────── */
            $shiftStart = Carbon::parse($today . ' ' . $shift->start_time, 'Asia/Phnom_Penh');
            $earliestAllowed = $shiftStart->copy()->subMinutes(self::EARLY_CHECKIN_GRACE_MINUTES);

            if ($now->lt($earliestAllowed)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot check in before ' . $earliestAllowed->format('h:i A') .
                                '. Your shift starts at ' . $shiftStart->format('h:i A') . '.',
                ], 422);
            }

            // Late threshold: shift's configured grace period (e.g. late_after_minutes), fallback 0
            $lateAfterMinutes = $shift->late_after_minutes ?? 0;
            $lateThreshold = $shiftStart->copy()->addMinutes($lateAfterMinutes);

            $lateMinutes = 0;
            $status = 'Present';

            if ($now->gt($lateThreshold)) {
                $lateMinutes = $lateThreshold->diffInMinutes($now);
                $status = 'Late';
            }

            /* ── GPS verification ─────────────────────────────────── */
            $employee = Employee::with('office')->find($employeeId);
            $office   = $employee?->office;

            $lat = $request->filled('latitude') ? (float) $request->latitude : null;
            $lng = $request->filled('longitude') ? (float) $request->longitude : null;

            $distance = null;
            $isVerified = false;

            if ($office && $lat !== null && $lng !== null) {
                $distance = $this->calculateDistance($office->latitude, $office->longitude, $lat, $lng);
                $allowedRadius = $office->radius_meters ?? 200;
                $isVerified = $distance !== null && $distance <= $allowedRadius;
            }

            AttendanceLog::create([
                'employee_id'          => $employeeId,
                'office_id'            => $office?->office_id,
                'log_datetime'         => $now,
                'log_type'             => 'Check In',
                'ip_address'           => $request->ip(),
                'device_name'          => substr($request->userAgent(), 0, 500),
                'gps_location'         => $request->gps_location,
                'latitude'             => $lat,
                'longitude'            => $lng,
                'distance_from_office' => $distance,
                'is_verified'          => $isVerified,
            ]);

            $attendance = Attendance::firstOrCreate(
                ['employee_id' => $employeeId, 'attendance_date' => $today],
                ['status' => $status]
            );

            if (!$attendance->check_in) {
                $attendance->check_in     = $now->format('H:i:s');
                $attendance->status       = $status;
                $attendance->late_minutes = $lateMinutes;
                $attendance->save();
            }

            return response()->json([
                'success' => true,
                'message' => $status === 'Late'
                    ? "Checked in successfully. You are {$lateMinutes} minute(s) late."
                    : 'Checked in successfully.',
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

        $employee = Employee::with('office')->find($employeeId);
        $office   = $employee?->office;

        $distance = null;
        $isVerified = false;

        if ($office && $request->latitude && $request->longitude) {
            $distance = $this->calculateDistance(
                $office->latitude,
                $office->longitude,
                $request->latitude,
                $request->longitude
            );

            $allowedRadius = $office->radius_meters ?? 200;
            $isVerified = $distance !== null && $distance <= $allowedRadius;
        }

        AttendanceLog::create([
            'employee_id'          => $employeeId,
            'office_id'            => $office?->office_id,
            'log_datetime'         => Carbon::now('Asia/Phnom_Penh'),
            'log_type'             => 'Check Out',
            'ip_address'           => $request->ip(),
            'device_name'          => substr($request->userAgent(), 0, 500),
            'gps_location'         => $request->gps_location,
            'latitude'             => $request->latitude,
            'longitude'            => $request->longitude,
            'distance_from_office' => $distance,
            'is_verified'          => $isVerified,
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

    /**
     * Calculate distance in meters between two GPS coordinates.
     */
    private function calculateDistance($lat1, $lng1, $lat2, $lng2)
    {
        if (is_null($lat1) || is_null($lng1) || is_null($lat2) || is_null($lng2)) {
            return null;
        }

        $earthRadius = 6371000; // meters

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2);
    }
}