<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shift;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class ShiftController extends Controller
{
    public function index()
    {
        return view('shifts.index');
    }

    public function getData()
    {
        $shifts = Shift::select(
            'shift_id as id',
            'shift_name',
            'start_time',
            'end_time',
            'late_after_minutes',
            'working_hours',
            'status',
        )->latest()->get();

        return response()->json([
            'success' => true,
            'data'    => $shifts,
        ]);
    }

    public function store(Request $request)
    {
        // Convert 12-hour to 24-hour before validation
        if ($request->start_time) {
            $request->merge(['start_time' => date('H:i', strtotime($request->start_time))]);
        }
        if ($request->end_time) {
            $request->merge(['end_time' => date('H:i', strtotime($request->end_time))]);
        }

        $validator = Validator::make($request->all(), [
            'shift_name'         => 'required|string|max:255|unique:shifts,shift_name',
            'start_time'         => 'required|date_format:H:i',
            'end_time'           => 'required|date_format:H:i',
            'late_after_minutes' => 'required|integer|min:0',
            'working_hours'      => 'required|numeric|min:0',
            'status'             => 'required|in:Active,Inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $startTime = Carbon::createFromFormat('H:i', $request->start_time);
        $endTime = Carbon::createFromFormat('H:i', $request->end_time);

        if ($startTime->equalTo($endTime)) {
            return response()->json([
                'success' => false,
                'errors'  => ['end_time' => ['End time must be different from start time.']],
            ], 422);
        }

        $shift = Shift::create($request->only([
            'shift_name',
            'start_time',
            'end_time',
            'late_after_minutes',
            'working_hours',
            'status',
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Shift created successfully.',
            'data'    => $shift,
        ], 201);
    }

    public function show(string $id)
    {
        $shift = Shift::findOrFail($id);

        // Convert to 24-hour format for the time input
        $shift->start_time = date('H:i', strtotime($shift->start_time));
        $shift->end_time   = date('H:i', strtotime($shift->end_time));

        return response()->json([
            'success' => true,
            'data'    => $shift,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $shift = Shift::findOrFail($id);

        // Convert 12-hour to 24-hour before validation
        if ($request->start_time) {
            $request->merge(['start_time' => date('H:i', strtotime($request->start_time))]);
        }
        if ($request->end_time) {
            $request->merge(['end_time' => date('H:i', strtotime($request->end_time))]);
        }

        $validator = Validator::make($request->all(), [
            'shift_name'         => 'required|string|max:255|unique:shifts,shift_name,' . $id . ',shift_id',
            'start_time'         => 'required|date_format:H:i',
            'end_time'           => 'required|date_format:H:i',
            'late_after_minutes' => 'required|integer|min:0',
            'working_hours'      => 'required|numeric|min:0',
            'status'             => 'required|in:Active,Inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors occurred.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $startTime = Carbon::createFromFormat('H:i', $request->start_time);
        $endTime = Carbon::createFromFormat('H:i', $request->end_time);

        if ($startTime->equalTo($endTime)) {
            return response()->json([
                'success' => false,
                'errors'  => ['end_time' => ['End time must be different from start time.']],
            ], 422);
        }

        $shift->update($request->only([
            'shift_name',
            'start_time',
            'end_time',
            'late_after_minutes',
            'working_hours',
            'status',
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Shift updated successfully.',
            'data'    => $shift,
        ]);
    }

    public function destroy(string $id)
    {
        $shift = Shift::findOrFail($id);

        if ($shift->employees()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete "' . $shift->shift_name . '": this shift has linked employees.',
            ], 422);
        }

        $shift->delete();

        return response()->json([
            'success' => true,
            'message' => 'Shift deleted successfully.'
        ]);
    }
}