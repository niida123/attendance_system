<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Office;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DailyAttendanceExport;
use App\Exports\MonthlyAttendanceExport;
use App\Exports\QuarterlyAttendanceExport;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * Attendance Reports Module
 * ---------------------------------------------------------------
 * Provides Daily / Monthly / Quarterly attendance reports with:
 *  - Server-side DataTables pagination, search & sort
 *  - Summary cards
 *  - Print / PDF / Excel export
 *
 * NOTE ON ASSUMPTIONS
 * ---------------------------------------------------------------
 * - Employee model has: employee_id, employee_code, first_name, last_name,
 *   department_id, office_id, and relations department()/office().
 * - Attendance model has: employee_id, attendance_date, check_in, check_out,
 *   working_hours, late_minutes, overtime_hours, status
 *   (status values used: Present, Late, Absent, Leave, Holiday, Half Day).
 * - If your "Leave" status is tracked in a separate leaves table instead of
 *   attendance.status, adjust the SUM(CASE WHEN...) blocks below accordingly.
 */
class AttendanceReportController extends Controller
{
    /* =========================================================
     |  PAGE VIEWS (menu: Reports > Daily / Monthly / Quarterly)
     |=========================================================*/

    public function dailyPage()
    {
        return view('reports.daily', [
            'offices'     => Office::orderBy('office_name')->get(),
            'departments' => Department::orderBy('department_name')->get(),
            'employees'   => Employee::orderBy('first_name')->get(['employee_id', 'employee_code', 'first_name', 'last_name']),
        ]);
    }

    public function monthlyPage()
    {
        return view('reports.monthly', [
            'offices'     => Office::orderBy('office_name')->get(),
            'departments' => Department::orderBy('department_name')->get(),
        ]);
    }

    public function quarterlyPage()
    {
        return view('reports.quarterly', [
            'offices'     => Office::orderBy('office_name')->get(),
            'departments' => Department::orderBy('department_name')->get(),
        ]);
    }

    /* =========================================================
     |  DAILY REPORT — server-side DataTables JSON
     |=========================================================*/

    public function dailyData(Request $request)
    {
        $date = $request->input('date', Carbon::now('Asia/Phnom_Penh')->format('Y-m-d'));

        $employeesQuery = $this->filteredEmployeeQuery($request);

        $recordsTotal    = Employee::count();
        $recordsFiltered = (clone $employeesQuery)->count();

        [$start, $length] = $this->pagingParams($request);

        $employees = $employeesQuery
            ->with(['department', 'office'])
            ->orderBy('employees.first_name')
            ->skip($start)->take($length)
            ->get();

        $holiday = DB::table('holidays')
            ->where('status', 'Active')
            ->whereDate('start_date', '<=', $date)
            ->where(function ($q) use ($date) {
                $q->whereNull('end_date')->orWhereDate('end_date', '>=', $date);
            })->first();

        $attendances = Attendance::whereIn('employee_id', $employees->pluck('employee_id'))
            ->where('attendance_date', $date)
            ->get()->keyBy('employee_id');

        $data = [];
        $i = $start;
        foreach ($employees as $emp) {
            $att    = $attendances->get($emp->employee_id);
            $status = $att->status ?? ($holiday ? 'Holiday' : 'Absent');

            $data[] = [
                'no'             => ++$i,
                'employee_code'  => $emp->employee_code ?? $emp->employee_id,
                'employee_name'  => trim($emp->first_name . ' ' . $emp->last_name),
                'department'     => $emp->department->department_name ?? '—',
                'office'         => $emp->office->office_name ?? '—',
                'check_in'       => $att->check_in ?? null,
                'check_out'      => $att->check_out ?? null,
                'working_hours'  => $att->working_hours ?? null,
                'late_minutes'   => $att->late_minutes ?? 0,
                'overtime_hours' => $att->overtime_hours ?? 0,
                'status'         => $status,
            ];
        }

        return response()->json([
            'draw'            => (int) $request->input('draw', 1),
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $data,
            'summary'         => $this->dailySummary($request, $date, $holiday),
        ]);
    }

    private function dailySummary(Request $request, string $date, $holiday): array
    {
        $employeesQuery = $this->filteredEmployeeQuery($request);
        $empIds         = (clone $employeesQuery)->pluck('employees.employee_id');

        $attendances = Attendance::whereIn('employee_id', $empIds)
            ->where('attendance_date', $date)
            ->get()->keyBy('employee_id');

        $summary = [
            'total_employees' => $empIds->count(),
            'present' => 0, 'late' => 0, 'absent' => 0, 'leave' => 0, 'holiday' => 0,
        ];

        foreach ($empIds as $eid) {
            $att    = $attendances->get($eid);
            $status = $att->status ?? ($holiday ? 'Holiday' : 'Absent');

            match ($status) {
                'Present' => $summary['present']++,
                'Late'    => $summary['late']++,
                'Absent'  => $summary['absent']++,
                'Leave'   => $summary['leave']++,
                'Holiday' => $summary['holiday']++,
                default   => null,
            };
        }

        return $summary;
    }

    /* =========================================================
     |  MONTHLY / QUARTERLY REPORT — shared range-based logic
     |=========================================================*/

    public function monthlyData(Request $request)
    {
        [$from, $to] = $this->monthRange($request);
        return $this->rangeReportJson($request, $from, $to);
    }

    public function quarterlyData(Request $request)
    {
        [$from, $to] = $this->quarterRange($request);
        return $this->rangeReportJson($request, $from, $to);
    }

    private function rangeReportJson(Request $request, string $from, string $to)
    {
        $employeesQuery = $this->filteredEmployeeQuery($request);

        $recordsTotal    = Employee::count();
        $recordsFiltered = (clone $employeesQuery)->count();

        [$start, $length] = $this->pagingParams($request);

        $employees = $employeesQuery
            ->with(['department', 'office'])
            ->orderBy('employees.first_name')
            ->skip($start)->take($length)
            ->get();

        $stats = $this->aggregateStats($employees->pluck('employee_id'), $from, $to);

        $data = [];
        $i = $start;
        foreach ($employees as $emp) {
            $s = $stats->get($emp->employee_id);
            $data[] = [
                'no'                   => ++$i,
                'employee_code'        => $emp->employee_code ?? $emp->employee_id,
                'employee_name'        => trim($emp->first_name . ' ' . $emp->last_name),
                'department'           => $emp->department->department_name ?? '—',
                'office'               => $emp->office->office_name ?? '—',
                'present_days'         => $s->present_days ?? 0,
                'late_days'            => $s->late_days ?? 0,
                'absent_days'          => $s->absent_days ?? 0,
                'leave_days'           => $s->leave_days ?? 0,
                'holiday_days'         => $s->holiday_days ?? 0,
                'total_working_hours'  => round($s->total_working_hours ?? 0, 2),
                'total_overtime_hours' => round($s->total_overtime_hours ?? 0, 2),
            ];
        }

        return response()->json([
            'draw'            => (int) $request->input('draw', 1),
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $data,
            'summary'         => $this->rangeSummary($request, $from, $to),
        ]);
    }

    private function rangeSummary(Request $request, string $from, string $to): array
    {
        $employeesQuery = $this->filteredEmployeeQuery($request);
        $empIds         = (clone $employeesQuery)->pluck('employees.employee_id');

        $row = Attendance::selectRaw("
                SUM(CASE WHEN status = 'Present' THEN 1 ELSE 0 END) as present,
                SUM(CASE WHEN status = 'Late' THEN 1 ELSE 0 END) as late,
                SUM(CASE WHEN status = 'Absent' THEN 1 ELSE 0 END) as absent,
                SUM(CASE WHEN status = 'Leave' THEN 1 ELSE 0 END) as `leave`,
                SUM(CASE WHEN status = 'Holiday' THEN 1 ELSE 0 END) as holiday,
                SUM(working_hours) as working_hours,
                SUM(overtime_hours) as overtime_hours
            ")
            ->whereIn('employee_id', $empIds)
            ->whereBetween('attendance_date', [$from, $to])
            ->first();

        return [
            'total_employees'      => $empIds->count(),
            'total_present'        => (int) ($row->present ?? 0),
            'total_late'           => (int) ($row->late ?? 0),
            'total_absent'         => (int) ($row->absent ?? 0),
            'total_leave'          => (int) ($row->leave ?? 0),
            'total_holiday'        => (int) ($row->holiday ?? 0),
            'total_working_hours'  => round($row->working_hours ?? 0, 2),
            'total_overtime_hours' => round($row->overtime_hours ?? 0, 2),
        ];
    }

    /* =========================================================
     |  EXPORTS — Excel & PDF (Daily / Monthly / Quarterly)
     |=========================================================*/

    public function exportDailyExcel(Request $request)
    {
        $date = $request->input('date', Carbon::now('Asia/Phnom_Penh')->format('Y-m-d'));
        [$rows, $summary, $meta] = $this->fullDailyData($request, $date);

        return Excel::download(
            new DailyAttendanceExport($rows, $summary, $meta),
            'daily-attendance-' . $date . '.xlsx'
        );
    }

    public function exportDailyPdf(Request $request)
    {
        $date = $request->input('date', Carbon::now('Asia/Phnom_Penh')->format('Y-m-d'));
        [$rows, $summary, $meta] = $this->fullDailyData($request, $date);

        $pdf = Pdf::loadView('reports.pdf.daily_pdf', compact('rows', 'summary', 'meta'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('daily-attendance-' . $date . '.pdf');
    }

    public function exportMonthlyExcel(Request $request)
    {
        [$from, $to] = $this->monthRange($request);
        [$rows, $summary, $meta] = $this->fullRangeData($request, $from, $to, 'Monthly');

        return Excel::download(
            new MonthlyAttendanceExport($rows, $summary, $meta),
            'monthly-attendance-' . $from . '.xlsx'
        );
    }

    public function exportMonthlyPdf(Request $request)
    {
        [$from, $to] = $this->monthRange($request);
        [$rows, $summary, $meta] = $this->fullRangeData($request, $from, $to, 'Monthly');

        $pdf = Pdf::loadView('reports.pdf.monthly_pdf', compact('rows', 'summary', 'meta'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('monthly-attendance-' . $from . '.pdf');
    }

    public function exportQuarterlyExcel(Request $request)
    {
        [$from, $to] = $this->quarterRange($request);
        [$rows, $summary, $meta] = $this->fullRangeData($request, $from, $to, 'Quarterly');

        return Excel::download(
            new QuarterlyAttendanceExport($rows, $summary, $meta),
            'quarterly-attendance-' . $from . '.xlsx'
        );
    }

    public function exportQuarterlyPdf(Request $request)
    {
        [$from, $to] = $this->quarterRange($request);
        [$rows, $summary, $meta] = $this->fullRangeData($request, $from, $to, 'Quarterly');

        $pdf = Pdf::loadView('reports.pdf.quarterly_pdf', compact('rows', 'summary', 'meta'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('quarterly-attendance-' . $from . '.pdf');
    }

    /* =========================================================
     |  FULL (UN-PAGINATED) DATA BUILDERS — used by exports
     |=========================================================*/

    private function fullDailyData(Request $request, string $date): array
    {
        $employeesQuery = $this->filteredEmployeeQuery($request);
        $employees      = $employeesQuery->with(['department', 'office'])->orderBy('employees.first_name')->get();

        $holiday = DB::table('holidays')
            ->where('status', 'Active')
            ->whereDate('start_date', '<=', $date)
            ->where(function ($q) use ($date) {
                $q->whereNull('end_date')->orWhereDate('end_date', '>=', $date);
            })->first();

        $attendances = Attendance::whereIn('employee_id', $employees->pluck('employee_id'))
            ->where('attendance_date', $date)->get()->keyBy('employee_id');

        $rows = [];
        $i = 0;
        foreach ($employees as $emp) {
            $att    = $attendances->get($emp->employee_id);
            $status = $att->status ?? ($holiday ? 'Holiday' : 'Absent');

            $rows[] = [
                'no'             => ++$i,
                'employee_code'  => $emp->employee_code ?? $emp->employee_id,
                'employee_name'  => trim($emp->first_name . ' ' . $emp->last_name),
                'department'     => $emp->department->department_name ?? '—',
                'office'         => $emp->office->office_name ?? '—',
                'check_in'       => $att->check_in ?? null,
                'check_out'      => $att->check_out ?? null,
                'working_hours'  => $att->working_hours ?? null,
                'late_minutes'   => $att->late_minutes ?? 0,
                'overtime_hours' => $att->overtime_hours ?? 0,
                'status'         => $status,
            ];
        }

        $summary = $this->dailySummary($request, $date, $holiday);
        $meta    = $this->exportMeta($request, 'Daily Attendance Report', "Date: " . Carbon::parse($date)->format('d M Y'));

        return [$rows, $summary, $meta];
    }

    private function fullRangeData(Request $request, string $from, string $to, string $label): array
    {
        $employeesQuery = $this->filteredEmployeeQuery($request);
        $employees      = $employeesQuery->with(['department', 'office'])->orderBy('employees.first_name')->get();

        $stats = $this->aggregateStats($employees->pluck('employee_id'), $from, $to);

        $rows = [];
        $i = 0;
        foreach ($employees as $emp) {
            $s = $stats->get($emp->employee_id);
            $rows[] = [
                'no'                   => ++$i,
                'employee_code'        => $emp->employee_code ?? $emp->employee_id,
                'employee_name'        => trim($emp->first_name . ' ' . $emp->last_name),
                'department'           => $emp->department->department_name ?? '—',
                'office'               => $emp->office->office_name ?? '—',
                'present_days'         => $s->present_days ?? 0,
                'late_days'            => $s->late_days ?? 0,
                'absent_days'          => $s->absent_days ?? 0,
                'leave_days'           => $s->leave_days ?? 0,
                'holiday_days'         => $s->holiday_days ?? 0,
                'total_working_hours'  => round($s->total_working_hours ?? 0, 2),
                'total_overtime_hours' => round($s->total_overtime_hours ?? 0, 2),
            ];
        }

        $summary   = $this->rangeSummary($request, $from, $to);
        $periodStr = Carbon::parse($from)->format('d M Y') . ' – ' . Carbon::parse($to)->format('d M Y');
        $meta      = $this->exportMeta($request, $label . ' Attendance Report', 'Period: ' . $periodStr);

        return [$rows, $summary, $meta];
    }

    /* =========================================================
     |  SHARED HELPERS
     |=========================================================*/

    /**
     * Base employee query joined to department/office, filtered by
     * office_id, department_id, employee_id (daily only) and search term.
     */
    private function filteredEmployeeQuery(Request $request)
    {
        $query = Employee::query()
            ->select('employees.*')
            ->leftJoin('departments', 'departments.department_id', '=', 'employees.department_id')
            ->leftJoin('offices', 'offices.office_id', '=', 'employees.office_id')
            ->when($request->filled('office_id'), fn($q) => $q->where('employees.office_id', $request->office_id))
            ->when($request->filled('department_id'), fn($q) => $q->where('employees.department_id', $request->department_id))
            ->when($request->filled('employee_id'), fn($q) => $q->where('employees.employee_id', $request->employee_id));

            $search = $request->input('search.value') ?? $request->input('search');

            if (is_array($search)) {
                $search = null;
            }

            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('employees.first_name', 'like', "%{$search}%")
                        ->orWhere('employees.last_name', 'like', "%{$search}%")
                        ->orWhere('employees.employee_code', 'like', "%{$search}%");
                });
            }

        return $query;
    }

    /**
     * Aggregated per-employee attendance stats between two dates (inclusive).
     */
    private function aggregateStats($employeeIds, string $from, string $to)
    {
        return Attendance::selectRaw("
                employee_id,
                SUM(CASE WHEN status = 'Present' THEN 1 ELSE 0 END) as present_days,
                SUM(CASE WHEN status = 'Late' THEN 1 ELSE 0 END) as late_days,
                SUM(CASE WHEN status = 'Absent' THEN 1 ELSE 0 END) as absent_days,
                SUM(CASE WHEN status = 'Leave' THEN 1 ELSE 0 END) as `leave_days`,
                SUM(CASE WHEN status = 'Leave' THEN 1 ELSE 0 END) as `leave`,
                SUM(CASE WHEN status = 'Holiday' THEN 1 ELSE 0 END) as holiday_days,
                SUM(working_hours) as total_working_hours,
                SUM(overtime_hours) as total_overtime_hours
            ")
            ->whereIn('employee_id', $employeeIds)
            ->whereBetween('attendance_date', [$from, $to])
            ->groupBy('employee_id')
            ->get()
            ->keyBy('employee_id');
    }

    private function pagingParams(Request $request): array
    {
        $start  = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 25);
        $length = $length > 0 ? $length : 25;

        return [$start, $length];
    }

    private function monthRange(Request $request): array
    {
        $year  = (int) $request->input('year', now()->year);
        $month = (int) $request->input('month', now()->month);

        $from = Carbon::create($year, $month, 1)->startOfMonth()->format('Y-m-d');
        $to   = Carbon::create($year, $month, 1)->endOfMonth()->format('Y-m-d');

        return [$from, $to];
    }

    private function quarterRange(Request $request): array
    {
        $year    = (int) $request->input('year', now()->year);
        $quarter = (int) $request->input('quarter', (int) ceil(now()->month / 3));
        $quarter = max(1, min(4, $quarter));

        $startMonth = ($quarter - 1) * 3 + 1;
        $from = Carbon::create($year, $startMonth, 1)->startOfMonth()->format('Y-m-d');
        $to   = Carbon::create($year, $startMonth, 1)->addMonths(2)->endOfMonth()->format('Y-m-d');

        return [$from, $to];
    }

    /**
     * Meta info shown in exported Excel/PDF headers (company, filters, date).
     */
    private function exportMeta(Request $request, string $title, string $period): array
    {
        $officeName = $request->filled('office_id')
            ? Office::where('office_id', $request->office_id)->value('office_name')
            : 'All Offices';

        $deptName = $request->filled('department_id')
            ? Department::where('department_id', $request->department_id)->value('department_name')
            : 'All Departments';

        $employeeName = $request->filled('employee_id')
            ? Employee::where('employee_id', $request->employee_id)->value(DB::raw("CONCAT(first_name,' ',last_name)"))
            : null;

        return [
            'company_name'    => config('app.name', 'Company Name'),
            'company_logo'    => public_path('images/logo.png'), // adjust to your actual logo path
            'title'           => $title,
            'period'          => $period,
            'office'          => $officeName ?? 'All Offices',
            'department'      => $deptName ?? 'All Departments',
            'employee'        => $employeeName,
            'generated_at'    => Carbon::now('Asia/Phnom_Penh')->format('d M Y, h:i A'),
            'generated_by'    => auth()->user()->name ?? 'System',
        ];
    }
}
