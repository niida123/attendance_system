{{-- resources/views/reports/monthly.blade.php --}}
@extends('adminlte::page')

@section('title', 'Monthly Attendance Report')

@section('content_header')
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h1 class="m-0 font-weight-bold" style="color:#1a1f36;font-size:1.4rem;letter-spacing:-0.3px;">
                <i class="fas fa-calendar-alt mr-2" style="color:#4f46e5;"></i> Monthly Attendance Report
            </h1>
            <ol class="breadcrumb mt-1 mb-0" style="background:transparent;padding:0;font-size:.8rem;">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" style="color:#4f46e5;">Home</a></li>
                <li class="breadcrumb-item"><span style="color:#6b7280;">Reports</span></li>
                <li class="breadcrumb-item active" style="color:#6b7280;">Monthly Attendance</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card" style="border:none;border-radius:16px;box-shadow:0 1px 3px rgba(0,0,0,.08),0 8px 32px rgba(79,70,229,.07);overflow:hidden;">

            <div class="card-header d-flex align-items-center justify-content-between flex-wrap" style="background:#fff;border-bottom:1px solid #f0f0f5;padding:18px 24px;gap:10px;">
                <div class="d-flex align-items-center">
                    <div style="width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,#4f46e5,#7c3aed);display:flex;align-items:center;justify-content:center;margin-right:12px;">
                        <i class="fas fa-calendar-alt" style="color:#fff;font-size:.85rem;"></i>
                    </div>
                    <div>
                        <h3 class="mb-0 font-weight-bold" style="font-size:1rem;color:#1a1f36;">Monthly Attendance</h3>
                        <small style="color:#9ca3af;font-size:.75rem;">Per-employee attendance totals for a selected month.</small>
                    </div>
                </div>

                <div class="d-flex ml-auto" style="gap:8px;">
                    <button id="btnPrint" class="btn btn-sm" style="height:40px;border-radius:12px;border:1.5px solid #e5e7eb;background:#fff;color:#374151;font-weight:600;padding:0 16px;">
                        <i class="fas fa-print mr-1"></i> Print
                    </button>
                    <button id="btnExportPdf" class="btn btn-sm" style="height:40px;border-radius:12px;border:none;background:linear-gradient(135deg,#ef4444,#dc2626);color:#fff;font-weight:600;padding:0 16px;">
                        <i class="fas fa-file-pdf mr-1"></i> PDF
                    </button>
                    <button id="btnExportExcel" class="btn btn-sm" style="height:40px;border-radius:12px;border:none;background:linear-gradient(135deg,#059669,#047857);color:#fff;font-weight:600;padding:0 16px;">
                        <i class="fas fa-file-excel mr-1"></i> Excel
                    </button>
                </div>
            </div>

            <div class="card-body" style="padding:24px;background:#fafbff;">

                {{-- Summary cards --}}
                <div class="row mb-4" id="statsRow">
                    @php
                        $cards = [
                            ['total_employees','Employees','fa-users','#eef2ff','#4f46e5'],
                            ['total_present','Present','fa-check-circle','#d1fae5','#059669'],
                            ['total_late','Late','fa-exclamation-circle','#fef3c7','#d97706'],
                            ['total_absent','Absent','fa-times-circle','#fef2f2','#ef4444'],
                            ['total_leave','Leave','fa-plane-departure','#ede9fe','#7c3aed'],
                            ['total_holiday','Holiday','fa-umbrella-beach','#fef9c3','#a16207'],
                            ['total_working_hours','Working Hrs','fa-business-time','#e0f2fe','#0369a1'],
                            ['total_overtime_hours','Overtime Hrs','fa-hourglass-half','#fce7f3','#db2777'],
                        ];
                    @endphp
                    @foreach($cards as [$key, $label, $icon, $bg, $color])
                        <div class="col-6 col-md-3 col-lg-2 mb-3" style="flex:0 0 25%;max-width:25%;">
                            <div style="background:#fff;border-radius:12px;padding:16px 14px;border:1px solid #f0f0f5;display:flex;align-items:center;gap:10px;height:100%;">
                                <div style="width:38px;height:38px;border-radius:10px;background:{{ $bg }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas {{ $icon }}" style="color:{{ $color }};font-size:.85rem;"></i>
                                </div>
                                <div>
                                    <div class="font-weight-bold" id="stat_{{ $key }}" style="font-size:1.15rem;color:#1a1f36;line-height:1;">—</div>
                                    <div style="font-size:.68rem;color:#9ca3af;margin-top:2px;">{{ $label }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Filters --}}
                <div class="d-flex align-items-center flex-wrap mb-4" style="gap:10px;">
                    <div style="width:120px;">
                        <select id="filterYear" class="form-control form-control-sm select2" style="width:100%;"></select>
                    </div>
                    <div style="width:150px;">
                        <select id="filterMonth" class="form-control form-control-sm select2" style="width:100%;">
                            @foreach(['January','February','March','April','May','June','July','August','September','October','November','December'] as $i => $m)
                                <option value="{{ $i+1 }}">{{ $m }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="width:190px;flex:1 1 150px;min-width:150px;">
                        <select id="filterOffice" class="form-control form-control-sm select2" style="width:100%;">
                            <option value="">All Offices</option>
                            @foreach($offices as $o)
                                <option value="{{ $o->office_id }}">{{ $o->office_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="width:190px;flex:1 1 150px;min-width:150px;">
                        <select id="filterDepartment" class="form-control form-control-sm select2" style="width:100%;">
                            <option value="">All Departments</option>
                            @foreach($departments as $d)
                                <option value="{{ $d->department_id }}">{{ $d->department_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button id="btnReset" class="btn btn-sm btn-filter-reset">
                        <i class="fas fa-undo mr-1"></i> Reset
                    </button>
                </div>

                {{-- Table --}}
                <div style="background:#fff;border-radius:12px;border:1px solid #f0f0f5;overflow:hidden;">
                    <div style="overflow-x:auto;-webkit-overflow-scrolling:touch;">
                        <table id="reportTable" class="table table-hover w-100 mb-0" style="min-width:1100px;">
                            <thead>
                                <tr style="background:#f8f9ff;">
                                    <th style="padding:14px 16px;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">No.</th>
                                    <th style="padding:14px 16px;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">Code</th>
                                    <th style="padding:14px 16px;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">Employee</th>
                                    <th style="padding:14px 16px;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">Department</th>
                                    <th style="padding:14px 16px;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">Office</th>
                                    <th class="text-center" style="padding:14px 16px;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">Present</th>
                                    <th class="text-center" style="padding:14px 16px;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">Late</th>
                                    <th class="text-center" style="padding:14px 16px;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">Absent</th>
                                    <th class="text-center" style="padding:14px 16px;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">Leave</th>
                                    <th class="text-center" style="padding:14px 16px;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">Holiday</th>
                                    <th class="text-center" style="padding:14px 16px;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">Working Hrs</th>
                                    <th class="text-center" style="padding:14px 16px;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">Overtime Hrs</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap4-theme@1.0.0/dist/select2-bootstrap4.min.css">
    @include('reports.partials.report-styles')
@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    $(document).ready(function () {
        toastr.options = { closeButton: true, progressBar: true, positionClass: 'toast-top-right', timeOut: 3000 };

        // Year dropdown: current year +/- 5
        const nowYear = new Date().getFullYear();
        const $year = $('#filterYear');
        for (let y = nowYear; y >= nowYear - 5; y--) $year.append(`<option value="${y}">${y}</option>`);
        $('#filterMonth').val(new Date().getMonth() + 1);

        $('.select2').select2({ theme: 'bootstrap4', width: '100%' });

        const DATA_URL  = '{{ route("reports.monthly.data") }}';
        const PDF_URL   = '{{ route("reports.monthly.export.pdf") }}';
        const EXCEL_URL = '{{ route("reports.monthly.export.excel") }}';

        function currentFilters() {
            return {
                year: $('#filterYear').val() || nowYear,
                month: $('#filterMonth').val(),
                office_id: $('#filterOffice').val(),
                department_id: $('#filterDepartment').val(),
            };
        }

        const table = $('#reportTable').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            dom: 't<"d-flex align-items-center justify-content-between px-1 pt-2"ip>',
            ajax: {
                url: DATA_URL,
                type: 'GET',
                data: (d) => Object.assign(d, currentFilters()),
                dataSrc: (json) => { updateStats(json.summary); return json.data; },
                error: () => toastr.error('Failed to load report data.')
            },
            order: [[0, 'asc']],
            language: {
                processing: '<i class="fas fa-spinner fa-spin mr-2" style="color:#4f46e5;"></i><span style="color:#4f46e5;">Loading...</span>',
                emptyTable: '<div style="padding:40px 0;color:#9ca3af;"><i class="fas fa-inbox" style="font-size:2rem;display:block;margin-bottom:10px;opacity:.4;"></i>No attendance records found.</div>',
                info: 'Showing _START_–_END_ of <strong>_TOTAL_</strong> employees',
                paginate: { previous: '<i class="fas fa-chevron-left"></i>', next: '<i class="fas fa-chevron-right"></i>' }
            },
            columns: [
                { data: 'no', orderable: false, className: 'text-center' },
                { data: 'employee_code' },
                { data: 'employee_name' },
                { data: 'department' },
                { data: 'office' },
                { data: 'present_days', className: 'text-center', render: v => `<span style="color:#059669;font-weight:600;">${v}</span>` },
                { data: 'late_days', className: 'text-center', render: v => v ? `<span style="color:#d97706;font-weight:600;">${v}</span>` : '0' },
                { data: 'absent_days', className: 'text-center', render: v => v ? `<span style="color:#ef4444;font-weight:600;">${v}</span>` : '0' },
                { data: 'leave_days', className: 'text-center' },
                { data: 'holiday_days', className: 'text-center' },
                { data: 'total_working_hours', className: 'text-center' },
                { data: 'total_overtime_hours', className: 'text-center', render: v => v ? `<span style="color:#4f46e5;font-weight:600;">${v}</span>` : '0' },
            ]
        });
        $('#filterYear, #filterMonth, #filterOffice, #filterDepartment').on('change', () => table.ajax.reload());

        function updateStats(s) {
            if (!s) return;
            $('#stat_total_employees').text(s.total_employees);
            $('#stat_total_present').text(s.total_present);
            $('#stat_total_late').text(s.total_late);
            $('#stat_total_absent').text(s.total_absent);
            $('#stat_total_leave').text(s.total_leave);
            $('#stat_total_holiday').text(s.total_holiday);
            $('#stat_total_working_hours').text(s.total_working_hours);
            $('#stat_total_overtime_hours').text(s.total_overtime_hours);
        }


        $('#btnReset').on('click', function () {
            $('#filterYear').val(nowYear).trigger('change');
            $('#filterMonth').val(new Date().getMonth() + 1).trigger('change');
            $('#filterOffice, #filterDepartment').val('').trigger('change');
            table.ajax.reload();
        });

        $('#btnPrint').on('click', () => window.print());

        function buildQuery() { return $.param(currentFilters()); }
        $('#btnExportPdf').on('click', () => window.location.href = PDF_URL + '?' + buildQuery());
        $('#btnExportExcel').on('click', () => window.location.href = EXCEL_URL + '?' + buildQuery());
    });
    </script>
@stop
