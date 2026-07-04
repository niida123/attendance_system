<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmployeeShift;
use App\Models\Employee;
use App\Models\Shift;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Department;

class EmployeeShiftController extends Controller
{
    /**
     * Render the employee shifts page.
     */
    public function index()
    {
        $employees   = Employee::select('employee_id', 'first_name', 'last_name')->get();
        $shifts      = Shift::select('shift_id', 'shift_name')->get();
        $departments = \App\Models\Department::select('department_id', 'department_name')->get();
        return view('employee_shifts.index', compact('employees', 'shifts', 'departments'));
    }

    /**
     * Return JSON list for DataTable.
     * Route: GET /employee_shifts/data
     */
    public function getData()
    {
        try {
            $employeeShifts = EmployeeShift::select(
                'employee_shifts.employee_shift_id',
                'employee_shifts.employee_id',
                'employee_shifts.shift_id',
                'employee_shifts.effective_from',
                'employee_shifts.effective_to',

                DB::raw("CONCAT(employees.first_name, ' ', employees.last_name) AS employee_name"),
                'employees.photo',

                'shifts.shift_name',
                'shifts.start_time as shift_start',
                'shifts.end_time as shift_end',

                'positions.position_name',
                'departments.department_name'
            )
            ->join('employees', 'employees.employee_id', '=', 'employee_shifts.employee_id')
            ->join('shifts', 'shifts.shift_id', '=', 'employee_shifts.shift_id')
            ->join('positions', 'positions.position_id', '=', 'employees.position_id')
            ->join('departments', 'departments.department_id', '=', 'employees.department_id')
            ->orderBy('employee_shifts.employee_shift_id', 'desc')
            ->get();

            return response()->json([
                'success' => true,
                'data'    => $employeeShifts,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created employee shift.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,employee_id',
            'shift_id' => 'required|exists:shifts,shift_id',
            'effective_from' => 'required|date',
            'effective_to' => 'nullable|date|after:effective_from',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $employeeShift = EmployeeShift::create($request->only([
            'employee_id',
            'shift_id',
            'effective_from',
            'effective_to',
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Employee shift created successfully.',
            'data'    => $employeeShift,
        ], 201);
    }

    /**
     * Return a single employee shift for editing.
     */
    public function show($id)
    {
        $employeeShift = EmployeeShift::findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $employeeShift,
        ]);
    }

    /**
     * Update the specified employee shift.
     */
    public function update(Request $request, $id)
    {
        $employeeShift = EmployeeShift::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,employee_id',
            'shift_id' => 'required|exists:shifts,shift_id',
            'effective_from' => 'required|date',
            'effective_to' => 'nullable|date|after:effective_from',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }
        $employeeShift->update($request->only([
            'employee_id',
            'shift_id',
            'effective_from',
            'effective_to',
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Employee shift updated successfully.',
            'data'    => $employeeShift,
        ]);
    }

    /**
     * Remove the specified employee shift.
     */
    public function destroy($id)
    {
        try {
            $employeeShift = EmployeeShift::findOrFail($id);

            // Check if attendance records exist for this employee during the shift period
            $hasAttendance = DB::table('attendance')
                ->where('employee_id', $employeeShift->employee_id)
                ->when($employeeShift->effective_from, function ($query) use ($employeeShift) {
                    $query->where('attendance_date', '>=', $employeeShift->effective_from);
                })
                ->when($employeeShift->effective_to, function ($query) use ($employeeShift) {
                    $query->where('attendance_date', '<=', $employeeShift->effective_to);
                })
                ->exists();

            if ($hasAttendance) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete: attendance records exist for this employee during this shift period.',
                ], 422);
            }

            $employeeShift->delete();

            return response()->json([
                'success' => true,
                'message' => 'Employee shift deleted successfully.',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
