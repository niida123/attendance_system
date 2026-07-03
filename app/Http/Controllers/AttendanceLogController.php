<?php

namespace App\Http\Controllers;

use App\Models\AttendanceLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Employee;
use App\Models\Office;
use Illuminate\Support\Facades\DB;

class AttendanceLogController extends Controller
{
    /**
     * Render the attendance logs page.
     */
    public function index()
    {
        return view('attendance_logs.index');
    }

    /**
     * Return JSON list for DataTable.
     * Route: GET /attendance-logs/data
     */
    public function getData()
    {
        $logs = AttendanceLog::with('employee:employee_id,employee_code,first_name,last_name,photo')
            ->select(
                'log_id',
                'employee_id',
                'office_id',
                'log_datetime',
                'log_type',
                'device_name',
                'ip_address',
                'gps_location',
                'latitude',
                'longitude',
                'distance_from_office',
                'is_verified',
                'created_at'
            )->latest()->get();

        return response()->json([
            'success' => true,
            'data'    => $logs,
        ]);
    }

    /**
     * Employee clocks IN or OUT.
     * No manual input needed — everything is auto-filled.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'log_type'     => 'required|in:IN,OUT',
            'gps_location' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user = Auth::user();

        if (! $user || ! $user->employee) {
            return response()->json([
                'success' => false,
                'message' => 'User is not linked to an employee record.',
            ], 422);
        }

        $log = AttendanceLog::create([
            'employee_id'  => $user->employee_id, // auto from logged-in user
            'office_id'    => $user->employee->office_id, // auto from employee's office
            'log_type'     => $request->log_type,          // only IN or OUT button
            'log_datetime' => now(),                        // auto current time
            'ip_address'   => $request->ip(),              // auto from request
            'device_name'  => $request->userAgent(),       // auto from browser
            'gps_location' => $request->gps_location,      // auto from JS GPS
            'latitude'     => $request->latitude,          // auto from JS GPS
            'longitude'    => $request->longitude,         // auto from JS GPS
            'distance_from_office' => $request->distance_from_office, // auto from JS GPS
            'is_verified'    => false,                     // auto set to false initially
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Attendance logged successfully.',
            'data'    => $log->load('employee:employee_id,employee_code,first_name,last_name,photo'),
        ], 201);
    }

    /**
     * Return a single attendance log for viewing.
     */
    public function show($id)
    {
        $log = AttendanceLog::with('employee:employee_id,employee_code,first_name,last_name,photo')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $log,
        ]);
    }

    /**
     * Remove the specified attendance log.
     */
    public function destroy($id)
    {
        $log = AttendanceLog::findOrFail($id);

        $log->delete();

        return response()->json([
            'success' => true,
            'message' => 'Attendance log deleted successfully.',
        ]);
    }

}