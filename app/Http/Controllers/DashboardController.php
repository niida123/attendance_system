<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\Holiday;
use App\Models\EmployeeShift;
use App\Models\Shift;
use App\Models\Role;

class DashboardController extends Controller
{
    /**
     * Show the dashboard view.
     */
    public function index()
    {
        return view('dashboard');
    }

    /**
     * Return dashboard data as JSON (role-aware).
     */
    public function getData()
    {
        $user  = Auth::user();
        $today = Carbon::now('Asia/Phnom_Penh')->format('Y-m-d');
        $month = Carbon::now('Asia/Phnom_Penh')->format('Y-m');

        // ── Employee (personal) dashboard ──────────────────────
        if ($user->hasRole('Employee') && $user->employee_id) {
            return $this->employeeData($user, $today, $month);
        }

        // ── Admin / HR dashboard ────────────────────────────────
        return $this->adminData($today, $month);
    }

    // ── Admin Data ─────────────────────────────────────────────
    private function adminData(string $today, string $month): \Illuminate\Http\JsonResponse
    {
        $totalEmployees  = Employee::where('status', 'Active')->count();
        $presentToday    = Attendance::where('attendance_date', $today)
                            ->whereIn('status', ['Present', 'Late'])->count();
        $lateToday       = Attendance::where('attendance_date', $today)
                            ->where('status', 'Late')->count();
        $onLeaveToday    = Attendance::where('attendance_date', $today)
                            ->where('status', 'Leave')->count();
        $absentToday     = $totalEmployees - $presentToday - $onLeaveToday;
        $absentToday     = max(0, $absentToday);
        $pendingLeaves   = LeaveRequest::where('status', 'Pending')->count();

        // Today's holiday
        $holiday = Holiday::where('status', 'Active')
            ->whereDate('start_date', '<=', $today)
            ->where(function ($q) use ($today) {
                $q->whereNull('end_date')->orWhereDate('end_date', '>=', $today);
            })->first();

        // Monthly attendance trend (last 6 months)
        $trend = [];
        for ($i = 5; $i >= 0; $i--) {
            $m     = Carbon::now('Asia/Phnom_Penh')->subMonths($i);
            $label = $m->format('M Y');
            $ym    = $m->format('Y-m');

            $present = Attendance::whereRaw("DATE_FORMAT(attendance_date, '%Y-%m') = ?", [$ym])
                        ->whereIn('status', ['Present', 'Late'])->count();
            $absent  = Attendance::whereRaw("DATE_FORMAT(attendance_date, '%Y-%m') = ?", [$ym])
                        ->where('status', 'Absent')->count();
            $late    = Attendance::whereRaw("DATE_FORMAT(attendance_date, '%Y-%m') = ?", [$ym])
                        ->where('status', 'Late')->count();

            $trend[] = ['month' => $label, 'present' => $present, 'absent' => $absent, 'late' => $late];
        }

        // Absent employees today
        $checkedInIds = Attendance::where('attendance_date', $today)
                        ->whereNotNull('check_in')->pluck('employee_id');
        $absentList = Employee::where('status', 'Active')
                        ->whereNotIn('employee_id', $checkedInIds)
                        ->with(['department', 'position'])
                        ->take(8)->get()
                        ->map(fn($e) => [
                            'name'       => $e->first_name . ' ' . $e->last_name,
                            'department' => $e->department?->department_name ?? '—',
                            'position'   => $e->position?->position_name ?? '—',
                            'photo'      => $e->photo ? asset('storage/' . $e->photo) : null,
                            'initials'   => strtoupper(substr($e->first_name,0,1) . substr($e->last_name,0,1)),
                        ]);

        // Pending leave requests
        $pendingList = LeaveRequest::where('status', 'Pending')
                        ->with(['employee', 'leaveType'])
                        ->latest()->take(5)->get()
                        ->map(fn($r) => [
                            'id'         => $r->leave_request_id,
                            'name'       => $r->employee?->first_name . ' ' . $r->employee?->last_name,
                            'leave_type' => $r->leaveType?->leave_name ?? '—',
                            'start_date' => $r->start_date,
                            'end_date'   => $r->end_date,
                            'total_days' => $r->total_days,
                            'photo'      => $r->employee?->photo ? asset('storage/' . $r->employee->photo) : null,
                            'initials'   => strtoupper(substr($r->employee?->first_name,0,1) . substr($r->employee?->last_name,0,1)),
                        ]);

        // Department breakdown
        $deptBreakdown = Employee::where('status', 'Active')
                            ->with('department')
                            ->get()
                            ->groupBy('department_id')
                            ->map(fn($group) => [
                                'name'  => $group->first()->department?->department_name ?? 'Unknown',
                                'count' => $group->count(),
                            ])->values();

        return response()->json([
            'role'    => 'admin',
            'today'   => $today,
            'holiday' => $holiday ? $holiday->holiday_name : null,
            'stats'   => [
                'total_employees' => $totalEmployees,
                'present_today'   => $presentToday,
                'absent_today'    => $absentToday,
                'late_today'      => $lateToday,
                'on_leave_today'  => $onLeaveToday,
                'pending_leaves'  => $pendingLeaves,
            ],
            'trend'          => $trend,
            'absent_list'    => $absentList,
            'pending_list'   => $pendingList,
            'dept_breakdown' => $deptBreakdown,
        ]);
    }

    // ── Employee Data ──────────────────────────────────────────
    private function employeeData($user, string $today, string $month): \Illuminate\Http\JsonResponse
    {
        $emp = $user->employee()->with(['department', 'position'])->first();
        if (!$emp) {
            return response()->json(['role' => 'employee', 'no_employee' => true]);
        }

        $empId = $emp->employee_id;

        // Today's attendance
        $todayAtt = Attendance::where('employee_id', $empId)
                        ->where('attendance_date', $today)->first();

        // This month summary
        $monthlyStats = Attendance::where('employee_id', $empId)
                        ->whereRaw("DATE_FORMAT(attendance_date, '%Y-%m') = ?", [$month])
                        ->selectRaw("
                            SUM(status = 'Present') as present,
                            SUM(status = 'Late')    as late,
                            SUM(status = 'Absent')  as absent,
                            SUM(status = 'Leave')   as on_leave,
                            ROUND(SUM(working_hours), 1) as total_hours
                        ")->first();

        // Leave balance
        $leaveBalance = LeaveRequest::where('employee_id', $empId)
                        ->where('status', 'Approved')
                        ->whereYear('start_date', Carbon::now()->year)
                        ->with('leaveType')
                        ->get()
                        ->groupBy('leave_type_id')
                        ->map(fn($group) => [
                            'type' => $group->first()->leaveType?->leave_name ?? '—',
                            'max'  => $group->first()->leaveType?->max_days_per_year ?? 0,
                            'used' => $group->sum('total_days'),
                        ])->values();

        // Recent attendance (last 7)
        $recent = Attendance::where('employee_id', $empId)
                    ->orderByDesc('attendance_date')
                    ->take(7)->get()
                    ->map(fn($a) => [
                        'date'          => $a->attendance_date,
                        'check_in'      => $a->check_in,
                        'check_out'     => $a->check_out,
                        'working_hours' => $a->working_hours,
                        'status'        => $a->status,
                        'late_minutes'  => $a->late_minutes,
                    ]);

        // Current shift
        $shift = EmployeeShift::with('shift')
                    ->where('employee_id', $empId)
                    ->where('effective_from', '<=', $today)
                    ->where(fn($q) => $q->whereNull('effective_to')->orWhere('effective_to', '>=', $today))
                    ->latest('effective_from')->first()?->shift;

        return response()->json([
            'role'     => 'employee',
            'today'    => $today,
            'employee' => [
                'name'       => $emp->first_name . ' ' . $emp->last_name,
                'position'   => $emp->position?->position_name ?? '—',
                'department' => $emp->department?->department_name ?? '—',
                'photo'      => $emp->photo ? asset('storage/' . $emp->photo) : null,
                'initials'   => strtoupper(substr($emp->first_name,0,1) . substr($emp->last_name,0,1)),
            ],
            'today_attendance' => $todayAtt,
            'monthly_stats'    => $monthlyStats,
            'leave_balance'    => $leaveBalance,
            'recent'           => $recent,
            'shift'            => $shift,
        ]);
    }
}