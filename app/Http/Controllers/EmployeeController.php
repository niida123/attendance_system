<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    public function index()
    {
        $departments = Department::orderBy('department_name')->get();
        $positions   = Position::orderBy('position_name')->get();

        return view('employees.index', compact('departments', 'positions'));
    }

    public function getData()
    {
        $employees = Employee::with(['department', 'position'])
            ->select(
                'employee_id',
                'employee_code',
                'first_name',
                'last_name',
                'email',
                'photo',
                'gender',
                'hire_date',
                'basic_salary',
                'status',
                'department_id',
                'position_id'
            )
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $employees,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_code' => 'required|string|max:20|unique:employees,employee_code',
            'first_name'    => 'required|string|max:100',
            'last_name'     => 'required|string|max:100',
            'gender'        => 'required|in:Male,Female',
            'date_of_birth' => 'nullable|date|before:today',
            'phone'         => 'nullable|string|max:20',
            'email'         => 'nullable|email|max:150|unique:employees,email',
            'address'       => 'nullable|string|max:255',
            'photo'         => 'nullable|image|mimes:jpeg,png|max:2048',
            'department_id' => 'required|exists:departments,department_id',
            'position_id'   => 'required|exists:positions,position_id',
            'hire_date'     => 'required|date',
            'basic_salary'  => 'nullable|numeric|min:0|max:9999999999.99',
            'status'        => 'required|in:Active,Inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('employees/photos', 'public');
        }

        $employee = Employee::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Employee created successfully.',
            'data'    => $employee,
        ]);
    }

    public function show(Employee $employee)
    {
        $employee->load(['department', 'position']);

        return response()->json([
            'success' => true,
            'data'    => $employee,
        ]);
    }

    public function update(Request $request, Employee $employee)
    {
        $validator = Validator::make($request->all(), [
            'employee_code' => 'required|string|max:20|unique:employees,employee_code,'.$employee->employee_id.',employee_id',
            'first_name'    => 'required|string|max:100',
            'last_name'     => 'required|string|max:100',
            'gender'        => 'required|in:Male,Female',
            'date_of_birth' => 'nullable|date|before:today',
            'phone'         => 'nullable|string|max:20',
            'email'         => 'nullable|email|max:150|unique:employees,email,'.$employee->employee_id.',employee_id',
            'address'       => 'nullable|string|max:255',
            'photo'         => 'nullable|image|mimes:jpeg,png|max:2048',
            'department_id' => 'required|exists:departments,department_id',
            'position_id'   => 'required|exists:positions,position_id',
            'hire_date'     => 'required|date',
            'basic_salary'  => 'nullable|numeric|min:0|max:9999999999.99',
            'status'        => 'required|in:Active,Inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        if ($request->hasFile('photo')) {
            if ($employee->photo) {
                Storage::disk('public')->delete($employee->photo);
            }
            $data['photo'] = $request->file('photo')->store('employees/photos', 'public');
        }

        $employee->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Employee updated successfully.',
            'data'    => $employee,
        ]);
    }

    public function destroy(Employee $employee)
    {
        if ($employee->photo) {
            Storage::disk('public')->delete($employee->photo);
        }

        $employee->delete();

        return response()->json([
            'success' => true,
            'message' => 'Employee deleted successfully.',
        ]);
    }
}