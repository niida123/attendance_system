<?php

namespace App\Http\Controllers;

use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\View\View;

class OfficeController extends Controller
{
    /**
     * GET /api/offices
     * List offices, optionally filtered by status / search keyword, paginated.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Office::query();

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('search')) {
            $keyword = $request->input('search');
            $query->where(function ($q) use ($keyword) {
                $q->where('office_name', 'like', "%{$keyword}%")
                  ->orWhere('office_code', 'like', "%{$keyword}%")
                  ->orWhere('address', 'like', "%{$keyword}%");
            });
        }

        $offices = $query->orderBy('office_name')
            ->paginate($request->input('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $offices,
        ]);
    }

    /**
     * POST /api/offices
     * Create a new office.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = $this->validator($request);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        // Business rule: default status is 'active' unless explicitly provided
        $data['status'] = $data['status'] ?? 'active';

        $office = Office::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Office created successfully.',
            'data' => $office,
        ], 201);
    }

    /**
     * GET /api/offices/{office}
     * Show a single office, with related counts.
     */
    public function show(Office $office): JsonResponse
    {
        $office->loadCount(['employees', 'attendanceLogs']);

        return response()->json([
            'success' => true,
            'data' => $office,
        ]);
    }

    /**
     * PUT/PATCH /api/offices/{office}
     * Update an existing office.
     */
    public function update(Request $request, Office $office): JsonResponse
    {
        $validator = $this->validator($request, $office->office_id);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Business rule: prevent deactivating an office that still has active employees,
        // unless the caller explicitly forces it.
        $incomingStatus = $request->input('status');
        if ($incomingStatus === 'inactive'
            && $office->status !== 'inactive'
            && !$request->boolean('force')
            && $office->employees()->where('status', 'active')->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot deactivate an office that still has active employees assigned. Reassign employees first or pass force=true.',
            ], 409);
        }

        $office->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Office updated successfully.',
            'data' => $office->fresh(),
        ]);
    }

    /**
     * DELETE /api/offices/{office}
     * Delete an office, blocking deletion if dependent records exist.
     */
    public function destroy(Office $office): JsonResponse
    {
        // Business rule: don't allow deleting an office that has employees or attendance history.
        if ($office->employees()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete office: employees are still assigned to it.',
            ], 409);
        }

        if ($office->attendanceLogs()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete office: attendance logs exist for it. Consider deactivating it instead.',
            ], 409);
        }

        $office->delete();

        return response()->json([
            'success' => true,
            'message' => 'Office deleted successfully.',
        ]);
    }

    /**
     * PATCH /api/offices/{office}/toggle-status
     * Convenience endpoint to activate/deactivate an office.
     */
    public function toggleStatus(Office $office): JsonResponse
    {
        $newStatus = $office->status === 'active' ? 'inactive' : 'active';

        if ($newStatus === 'inactive' && $office->employees()->where('status', 'active')->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot deactivate an office that still has active employees assigned.',
            ], 409);
        }

        $office->update(['status' => $newStatus]);

        return response()->json([
            'success' => true,
            'message' => "Office status changed to {$newStatus}.",
            'data' => $office->fresh(),
        ]);
    }

    /**
     * Shared validation rules for store/update.
     */
    protected function validator(Request $request, $officeId = null)
    {
        return Validator::make($request->all(), [
            'office_code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('offices', 'office_code')->ignore($officeId, 'office_id'),
            ],
            'office_name' => ['required', 'string', 'max:150'],
            'address' => ['required', 'string', 'max:255'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'allowed_radius' => ['required', 'integer', 'min:10', 'max:5000'],
            'office_ip' => ['nullable', 'ip'],
            'office_wifi_name' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', Rule::in(['active', 'inactive'])],
        ], [
            'office_code.unique' => 'This office code is already in use.',
            'latitude.between' => 'Latitude must be between -90 and 90 degrees.',
            'longitude.between' => 'Longitude must be between -180 and 180 degrees.',
            'allowed_radius.min' => 'Allowed radius must be at least 10 meters.',
            'allowed_radius.max' => 'Allowed radius cannot exceed 5000 meters.',
        ]);
    }

    /**
     * GET /offices
     * Render the office management page.
     */
    public function page(): View
    {
        return view('offices.index');
    }
    
    /**
     * GET /offices-data
     * Flat JSON feed (no pagination) for the DataTable on the office page.
     * Includes employees_count for the "Employees Assigned" stat card.
     */
    public function data(): JsonResponse
    {
        $offices = Office::withCount('employees')
            ->orderBy('office_name')
            ->get();
    
        return response()->json([
            'success' => true,
            'data' => $offices,
        ]);
    }
}