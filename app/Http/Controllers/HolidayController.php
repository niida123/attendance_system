<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HolidayController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('holidays.index');
    }

    /*
     * Return JSON list for DataTable.
     * Route: GET /holidays/data
     */
    public function getData()
    {
        $holidays = Holiday::select(
            'holiday_id',
            'holiday_name',
            'start_date',
            'end_date',
            'description',
            'is_paid',
            'status',
        )->latest()->get();

        return response()->json([
            'success' => true,
            'data'    => $holidays,
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'holiday_name' => 'required|string|max:255|unique:holidays,holiday_name',
            'start_date'   => 'nullable|date',
            'end_date'     => 'nullable|date|after_or_equal:start_date',
            'is_paid'      => 'required|boolean',
            'description'  => 'nullable|string|max:1000',
            'status'       => 'required|in:Active,Inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        // Create the holiday
        $holiday = Holiday::create($request->only([
            'holiday_name',
            'is_paid',
            'description',
            'status',
            'start_date',
            'end_date'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Holiday created successfully.',
            'data'    => $holiday,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $holiday = Holiday::findOrFail($id);
        return response()->json([
            'success' => true,
            'data'    => $holiday,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $holiday = Holiday::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'holiday_name' => 'required|string|max:255|unique:holidays,holiday_name,' . $id . ',holiday_id',
            'is_paid'      => 'required|boolean',
            'description'  => 'nullable|string|max:1000',
            'status'       => 'required|in:Active,Inactive',
            'start_date'   => 'nullable|date',
            'end_date'     => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        // Update the holiday
        $holiday->update($request->only([
            'holiday_name',
            'is_paid',
            'description',
            'status',
            'start_date',
            'end_date'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Holiday updated successfully.',
            'data'    => $holiday,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $holiday = Holiday::findOrFail($id);
        $holiday->delete();

        return response()->json([
            'success' => true,
            'message' => 'Holiday deleted successfully.',
        ]);
    }
}
