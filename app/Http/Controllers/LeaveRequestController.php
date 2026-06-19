<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\Attendance;

class LeaveRequestController extends Controller
{
    // ─────────────────────────────────────────────────────────────
    //  VIEWS
    // ─────────────────────────────────────────────────────────────

    /** Admin: all leave requests */
    public function index()
    {
        return view('leave_requests.index');
    }

    /** Employee: my leave requests */
    public function myLeave()
    {
        return view('leave_requests.my_leave');
    }

    /** Show create form */
    public function create()
    {
        $leaveTypes = LeaveType::where('status', 'Active')->get();
        return view('leave_requests.create', compact('leaveTypes'));
    }

    // ─────────────────────────────────────────────────────────────
    //  DATA ENDPOINTS (JSON)
    // ─────────────────────────────────────────────────────────────

    /**
     * All leave requests — admin table
     */
    public function getData()
    {
        try {
            $requests = LeaveRequest::with(['employee', 'leaveType', 'approver'])
                ->latest('created_at')
                ->get()
                ->map(fn($r) => $this->formatRow($r));

            return response()->json(['success' => true, 'data' => $requests]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * My leave requests — employee view
     */
    public function myData()
    {
        try {
            $employeeId = Auth::user()->employee_id;

            $requests = LeaveRequest::with(['leaveType', 'approver'])
                ->where('employee_id', $employeeId)
                ->latest('created_at')
                ->get()
                ->map(fn($r) => $this->formatRow($r));

            return response()->json(['success' => true, 'data' => $requests]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Leave balance summary for logged-in employee
     */
    public function myBalance()
    {
        try {
            $employeeId = Auth::user()->employee_id;
            $year       = Carbon::now()->year;

            $leaveTypes = LeaveType::where('status', 'Active')->get();

            $balance = $leaveTypes->map(function ($type) use ($employeeId, $year) {
                $used = LeaveRequest::where('employee_id', $employeeId)
                    ->where('leave_type_id', $type->leave_type_id)
                    ->where('status', 'Approved')
                    ->whereYear('start_date', $year)
                    ->sum('total_days');

                $pending = LeaveRequest::where('employee_id', $employeeId)
                    ->where('leave_type_id', $type->leave_type_id)
                    ->where('status', 'Pending')
                    ->whereYear('start_date', $year)
                    ->sum('total_days');

                $remaining = max(0, $type->max_days_per_year - $used);

                return [
                    'leave_type_id'   => $type->leave_type_id,
                    'leave_type_name' => $type->leave_name,
                    'max_days_per_year' => $type->max_days_per_year,
                    'days_used'        => (int) $used,
                    'days_pending'     => (int) $pending,
                    'days_remaining'   => (int) $remaining, 
                ];
            });

            return response()->json(['success' => true, 'data' => $balance]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // ─────────────────────────────────────────────────────────────
    //  STORE (Employee submits a leave request)
    // ─────────────────────────────────────────────────────────────

    public function store(Request $request)
    {
        $request->validate([
            'leave_type_id' => 'required|exists:leave_types,leave_type_id',
            'start_date'    => 'required|date|after_or_equal:today',
            'end_date'      => 'required|date|after_or_equal:start_date',
            'reason'        => 'nullable|string|max:1000',
        ]);

        try {
            $employeeId = Auth::user()->employee_id;
            $startDate  = Carbon::parse($request->start_date);
            $endDate    = Carbon::parse($request->end_date);
            $leaveType  = LeaveType::findOrFail($request->leave_type_id);

            // ── 1. Calculate working days (exclude weekends & holidays) ──
            $totalDays = $this->countWorkingDays($startDate, $endDate);

            if ($totalDays <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Selected dates fall on weekends or holidays only. Please choose working days.',
                ], 422);
            }

            // ── 2. Check leave balance ───────────────────────────────────
            $year = $startDate->year;
            $used = LeaveRequest::where('employee_id', $employeeId)
                ->where('leave_type_id', $leaveType->leave_type_id)
                ->where('status', 'Approved')
                ->whereYear('start_date', $year)
                ->sum('total_days');

            $remaining = $leaveType->max_days_per_year - $used;

            if ($totalDays > $remaining) {
                return response()->json([
                    'success' => false,
                    'message' => "Insufficient leave balance. You have {$remaining} day(s) remaining for {$leaveType->leave_name}.",
                ], 422);
            }

            // ── 3. Check for overlapping approved/pending requests ───────
            $overlap = LeaveRequest::where('employee_id', $employeeId)
                ->whereIn('status', ['Pending', 'Approved'])
                ->where(function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('start_date', [$startDate, $endDate])
                      ->orWhereBetween('end_date', [$startDate, $endDate])
                      ->orWhere(function ($q2) use ($startDate, $endDate) {
                          $q2->where('start_date', '<=', $startDate)
                             ->where('end_date', '>=', $endDate);
                      });
                })
                ->first();

            if ($overlap) {
                return response()->json([
                    'success' => false,
                    'message' => "You already have a {$overlap->status} leave request overlapping these dates ({$overlap->start_date} – {$overlap->end_date}).",
                ], 422);
            }

            // ── 4. Create the request ────────────────────────────────────
            $leaveRequest = LeaveRequest::create([
                'employee_id'   => $employeeId,
                'leave_type_id' => $leaveType->leave_type_id,
                'start_date'    => $startDate->format('Y-m-d'),
                'end_date'      => $endDate->format('Y-m-d'),
                'total_days'    => $totalDays,
                'reason'        => $request->reason,
                'status'        => 'Pending',
            ]);

            return response()->json([
                'success' => true,
                'message' => "Leave request submitted successfully for {$totalDays} working day(s).",
                'data'    => $leaveRequest,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // ─────────────────────────────────────────────────────────────
    //  SHOW
    // ─────────────────────────────────────────────────────────────

    public function show(string $id)
    {
        try {
            $leaveRequest = LeaveRequest::with(['employee', 'leaveType', 'approver'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data'    => $this->formatRow($leaveRequest),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // ─────────────────────────────────────────────────────────────
    //  APPROVE (Admin)
    // ─────────────────────────────────────────────────────────────

    public function approve(string $id)
    {
        try {
            $leaveRequest = LeaveRequest::findOrFail($id);

            if ($leaveRequest->status !== 'Pending') {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot approve a request that is already {$leaveRequest->status}.",
                ], 422);
            }

            $approverEmployeeId = Auth::user()->employee_id;

            DB::transaction(function () use ($leaveRequest, $approverEmployeeId) {

                $leaveRequest->update([
                    'status'        => 'Approved',
                    'approved_by'   => $approverEmployeeId,
                    'approval_date' => Carbon::now()->format('Y-m-d'),
                ]);

                // ── Mark attendance as Leave for each working day ──────
                $current = Carbon::parse($leaveRequest->start_date);
                $end     = Carbon::parse($leaveRequest->end_date);

                while ($current->lte($end)) {
                    $dateStr = $current->format('Y-m-d');

                    // Skip weekends
                    if (!$current->isWeekend()) {
                        // Skip holidays
                        $isHoliday = Holiday::where('status', 'Active')
                            ->whereDate('start_date', '<=', $dateStr)
                            ->where(function ($q) use ($dateStr) {
                                $q->whereNull('end_date')
                                  ->orWhereDate('end_date', '>=', $dateStr);
                            })
                            ->exists();

                        if (!$isHoliday) {
                            Attendance::updateOrCreate(
                                [
                                    'employee_id'     => $leaveRequest->employee_id,
                                    'attendance_date' => $dateStr,
                                ],
                                [
                                    'status'        => 'Leave',
                                    'check_in'      => null,
                                    'check_out'     => null,
                                    'working_hours' => 0,
                                    'late_minutes'  => 0,
                                ]
                            );
                        }
                    }

                    $current->addDay();
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Leave request approved successfully.',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // ─────────────────────────────────────────────────────────────
    //  REJECT (Admin)
    // ─────────────────────────────────────────────────────────────

    public function reject(Request $request, string $id)
    {
        try {
            $leaveRequest = LeaveRequest::findOrFail($id);

            if ($leaveRequest->status !== 'Pending') {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot reject a request that is already {$leaveRequest->status}.",
                ], 422);
            }

            $leaveRequest->update([
                'status'        => 'Rejected',
                'approved_by'   => Auth::user()->employee_id,
                'approval_date' => Carbon::now()->format('Y-m-d'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Leave request rejected.',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // ─────────────────────────────────────────────────────────────
    //  CANCEL (Employee cancels their own pending request)
    // ─────────────────────────────────────────────────────────────

    public function cancel(string $id)
    {
        try {
            $employeeId   = Auth::user()->employee_id;
            $leaveRequest = LeaveRequest::where('employee_id', $employeeId)
                ->findOrFail($id);

            if ($leaveRequest->status !== 'Pending') {
                return response()->json([
                    'success' => false,
                    'message' => "Only pending requests can be cancelled. This request is already {$leaveRequest->status}.",
                ], 422);
            }

            $leaveRequest->update(['status' => 'Rejected']);

            return response()->json([
                'success' => true,
                'message' => 'Leave request cancelled successfully.',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // ─────────────────────────────────────────────────────────────
    //  DELETE (Admin only)
    // ─────────────────────────────────────────────────────────────

    public function destroy(string $id)
    {
        try {
            $leaveRequest = LeaveRequest::findOrFail($id);

            // If it was approved, revert attendance records back to Absent
            if ($leaveRequest->status === 'Approved') {
                Attendance::where('employee_id', $leaveRequest->employee_id)
                    ->where('status', 'Leave')
                    ->whereBetween('attendance_date', [
                        $leaveRequest->start_date,
                        $leaveRequest->end_date,
                    ])
                    ->update([
                        'status'        => 'Absent',
                        'working_hours' => 0,
                        'late_minutes'  => 0,
                    ]);
            }

            $leaveRequest->delete();

            return response()->json([
                'success' => true,
                'message' => 'Leave request deleted successfully.',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // ─────────────────────────────────────────────────────────────
    //  HELPERS
    // ─────────────────────────────────────────────────────────────

    /**
     * Count working days between two dates
     * Excludes: weekends + active holidays
     */
    private function countWorkingDays(Carbon $start, Carbon $end): int
    {
        // Fetch all active holidays in the range once
        $holidays = Holiday::where('status', 'Active')
            ->where('start_date', '<=', $end->format('Y-m-d'))
            ->where(function ($q) use ($start) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', $start->format('Y-m-d'));
            })
            ->get();

        $count   = 0;
        $current = $start->copy();

        while ($current->lte($end)) {
            // Skip weekends
            if ($current->isWeekend()) {
                $current->addDay();
                continue;
            }

            // Skip holidays
            $isHoliday = $holidays->first(function ($h) use ($current) {
                $hStart = Carbon::parse($h->start_date);
                $hEnd   = $h->end_date ? Carbon::parse($h->end_date) : $hStart;
                return $current->between($hStart, $hEnd);
            });

            if (!$isHoliday) {
                $count++;
            }

            $current->addDay();
        }

        return $count;
    }

    /**
     * Format a LeaveRequest model for JSON response
     */
    private function formatRow(LeaveRequest $r): array
    {
        return [
            'leave_request_id' => $r->leave_request_id,
            'employee_id'      => $r->employee_id,
            'employee'         => $r->employee ? [
                'employee_id'   => $r->employee->employee_id,
                'first_name'    => $r->employee->first_name,
                'last_name'     => $r->employee->last_name,
                'employee_code' => $r->employee->employee_code ?? null,
                'photo'         => $r->employee->photo ?? null,
            ] : null,
            'leave_type'       => $r->leaveType ? [
                'leave_type_id'   => $r->leaveType->leave_type_id,
                'leave_type_name' => $r->leaveType->leave_name,
                'max_days_per_year' => $r->leaveType->max_days_per_year,
            ] : null,
            'start_date'       => $r->start_date?->format('Y-m-d'),
            'end_date'         => $r->end_date?->format('Y-m-d'),
            'total_days'       => $r->total_days,
            'reason'           => $r->reason,
            'status'           => $r->status,
            'approved_by'      => $r->approver ? [
                'employee_id' => $r->approver->employee_id,
                'name'        => $r->approver->first_name . ' ' . $r->approver->last_name,
            ] : null,
            'approval_date'    => $r->approval_date?->format('Y-m-d'),
            'created_at'       => $r->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}