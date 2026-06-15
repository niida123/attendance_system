{{-- resources/views/attendance/my_attendance.blade.php --}}
@extends('adminlte::page')

@section('title', 'My Attendance')

@section('content_header')
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h1 class="m-0 font-weight-bold" style="color:#1a1f36;font-size:1.4rem;letter-spacing:-0.3px;">
                <i class="fas fa-user-clock mr-2" style="color:#4f46e5;"></i> My Attendance
            </h1>
            <ol class="breadcrumb mt-1 mb-0" style="background:transparent;padding:0;font-size:.8rem;">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" style="color:#4f46e5;">Home</a></li>
                <li class="breadcrumb-item active" style="color:#6b7280;">My Attendance</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card"
                style="border:none;border-radius:16px;box-shadow:0 1px 3px rgba(0,0,0,.08),0 8px 32px rgba(79,70,229,.07);overflow:hidden;">

                {{-- Card Header --}}
                <div class="card-header" style="background:#fff;border-bottom:1px solid #f0f0f5;padding:18px 24px;">

                    {{-- Top row: title + filter bar --}}
                    <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap:12px;">

                        {{-- Left: icon + title --}}
                        <div class="d-flex align-items-center">
                            <div
                                style="width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,#4f46e5,#7c3aed);display:flex;align-items:center;justify-content:center;margin-right:12px;flex-shrink:0;">
                                <i class="fas fa-user-clock" style="color:#fff;font-size:.85rem;"></i>
                            </div>
                            <div>
                                <h3 class="mb-0 font-weight-bold" style="font-size:1rem;color:#1a1f36;">
                                    My Attendance Records
                                </h3>
                                <small style="color:#9ca3af;font-size:.75rem;">
                                    View your check-ins, check-outs, working hours, and attendance status.
                                </small>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="card-body" style="padding:24px;background:#fafbff;">

                    {{-- Stats Row --}}
                    <div class="row mb-4" id="statsRow">

                        {{-- Today's Status --}}
                        <div class="col-6 col-md-3 mb-3 mb-md-0">
                            <div
                                style="background:#fff;border-radius:12px;padding:16px 20px;border:1px solid #f0f0f5;display:flex;align-items:center;gap:14px;">
                                <div
                                    style="width:42px;height:42px;border-radius:10px;background:#eef2ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas fa-calendar-check" style="color:#4f46e5;"></i>
                                </div>
                                <div>
                                    <div class="font-weight-bold" id="stat-status"
                                        style="font-size:1.4rem;color:#1a1f36;line-height:1;">—</div>
                                    <div style="font-size:.75rem;color:#9ca3af;margin-top:2px;">Today's Status</div>
                                </div>
                            </div>
                        </div>

                        {{-- Present --}}
                        <div class="col-6 col-md-3 mb-3 mb-md-0">
                            <div
                                style="background:#fff;border-radius:12px;padding:16px 20px;border:1px solid #f0f0f5;display:flex;align-items:center;gap:14px;">
                                <div
                                    style="width:42px;height:42px;border-radius:10px;background:#d1fae5;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas fa-check-circle" style="color:#059669;"></i>
                                </div>
                                <div>
                                    <div class="font-weight-bold" id="stat-present"
                                        style="font-size:1.4rem;color:#1a1f36;line-height:1;">—</div>
                                    <div style="font-size:.75rem;color:#9ca3af;margin-top:2px;">Present This Month</div>
                                </div>
                            </div>
                        </div>

                        {{-- Late --}}
                        <div class="col-6 col-md-3 mb-3 mb-md-0">
                            <div
                                style="background:#fff;border-radius:12px;padding:16px 20px;border:1px solid #f0f0f5;display:flex;align-items:center;gap:14px;">
                                <div
                                    style="width:42px;height:42px;border-radius:10px;background:#fef3c7;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas fa-exclamation-circle" style="color:#d97706;"></i>
                                </div>
                                <div>
                                    <div class="font-weight-bold" id="stat-late"
                                        style="font-size:1.4rem;color:#1a1f36;line-height:1;">—</div>
                                    <div style="font-size:.75rem;color:#9ca3af;margin-top:2px;">Late This Month</div>
                                </div>
                            </div>
                        </div>

                        {{-- Attendance Rate --}}
                        <div class="col-6 col-md-3 mb-3 mb-md-0">
                            <div
                                style="background:#fff;border-radius:12px;padding:16px 20px;border:1px solid #f0f0f5;display:flex;align-items:center;gap:14px;">
                                <div
                                    style="width:42px;height:42px;border-radius:10px;background:#ede9fe;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas fa-chart-line" style="color:#7c3aed;"></i>
                                </div>
                                <div>
                                    <div class="font-weight-bold" id="stat-rate"
                                        style="font-size:1.4rem;color:#1a1f36;line-height:1;">—</div>
                                    <div style="font-size:.75rem;color:#9ca3af;margin-top:2px;">Attendance Rate</div>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Filter Strip --}}
                    <div class="d-flex align-items-center ml-auto mt-3" style="gap:10px;flex-wrap:wrap;">
                        <div class="filter-box filter-search-box">
                            <div style="position:relative;">
                                <i class="fas fa-search"
                                    style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#9ca3af;"></i>

                                <input type="text" id="searchAttendance" class="form-control form-control-sm"
                                    placeholder="Search attendance..."
                                    style="padding-left:34px;height:40px;border-radius:12px;">

                                <i class="fas fa-times" id="clearSearch"
                                    style="display:none;position:absolute;right:12px;top:50%;transform:translateY(-50%);cursor:pointer;color:#6b7280;">
                                </i>
                            </div>
                        </div>

                        <div class="filter-box filter-date-box">
                            <div class="filter-date-range">
                                <div class="filter-date-field">
                                    <input type="date" id="filterDateFrom" class="form-control form-control-sm"
                                        style="height:40px;border-radius:12px;">
                                </div>

                                <div class="filter-date-separator" style="margin:0 10px;">to</div>

                                <div class="filter-date-field">
                                    <input type="date" id="filterDateTo" class="form-control form-control-sm"
                                        style="height:40px;border-radius:12px;">
                                </div>
                            </div>
                        </div>

                        <div class="filter-box filter-status-box">
                            <select id="filterStatus" class="form-control form-control-sm"
                                style="height:40px;border-radius:12px;">
                                <option value="">All Status</option>
                                <option value="Present">Present</option>
                                <option value="Late">Late</option>
                                <option value="Absent">Absent</option>
                                <option value="Leave">Leave</option>
                            </select>
                        </div>

                        <div class="filter-box filter-reset-box">
                            <button id="btnResetFilter" class="btn btn-sm btn-block"
                                style="height:40px;border-radius:12px;border:1.5px solid #e5e7eb;background:#fff;color:#6b7280;font-weight:600;">
                                <i class="fas fa-undo mr-1"></i> Reset
                            </button>
                        </div>
                    </div>

                    {{-- Table --}}
                    <div style="background:#fff;border-radius:12px;border:1px solid #f0f0f5;overflow:hidden;">
                        <div style="overflow-x:auto;-webkit-overflow-scrolling:touch;">
                            <table id="myAttendanceTable" class="table table-hover w-100 mb-0" style="min-width:700px;">
                                <thead>
                                    <tr style="background:#f8f9ff;">
                                        <th width="50"
                                            style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">
                                            #</th>
                                        <th width="200"
                                            style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">
                                            Date</th>
                                        <th width="130"
                                            style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">
                                            Check In</th>
                                        <th width="130"
                                            style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">
                                            Check Out</th>
                                        <th width="120" class="text-center"
                                            style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">
                                            Status</th>
                                        <th width="120" class="text-center"
                                            style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">
                                            Hours</th>
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

{{-- ============================================================
     Styles
     ============================================================ --}}
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
    <style>
        #myAttendanceTable tbody tr {
            transition: background .15s;
        }

        #myAttendanceTable tbody tr:hover {
            background: #f5f6ff !important;
        }

        #myAttendanceTable tbody td {
            padding: 13px 20px;
            vertical-align: middle;
            font-size: .875rem;
            color: #374151;
            border-color: #f3f4f6;
        }

        /* ── Status badges ── */
        .badge-present {
            background: #ecfdf5;
            color: #059669;
        }

        .badge-late {
            background: #fef3c7;
            color: #d97706;
        }

        .badge-absent {
            background: #fef2f2;
            color: #ef4444;
        }

        .badge-leave {
            background: #ede9fe;
            color: #7c3aed;
        }

        .badge-present,
        .badge-late,
        .badge-absent,
        .badge-leave {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: .75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            white-space: nowrap;
            line-height: 1;
        }

        /* ── Row number pill ── */
        .row-num {
            width: 26px;
            height: 26px;
            border-radius: 6px;
            background: #f3f4f6;
            color: #6b7280;
            font-size: .75rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* ── Today highlight ── */
        .today-row {
            background: #f5f6ff !important;
        }

        .filter-box {
            min-width: 0;
        }

        .filter-search-box {
            flex: 1 1 300px;
        }

        .filter-date-box {
            flex: 1 1 380px;
        }

        .filter-status-box {
            flex: 0 0 170px;
        }

        .filter-reset-box {
            flex: 0 0 120px;
        }

        .filter-date-range {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .filter-date-field {
            flex: 1 1 0;
            min-width: 0;
        }

        .filter-date-separator {
            flex: 0 0 auto;
            margin-top: 18px;
            color: #9ca3af;
            font-size: .85rem;
            font-weight: 600;
        }

        /* ── Filter inputs focus ── */
        #searchAttendance:focus,
        #filterDateFrom:focus,
        #filterDateTo:focus,
        #filterStatus:focus {
            border-color: #4f46e5 !important;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, .12) !important;
            outline: none;
        }

        #btnResetFilter:hover {
            background: #f3f4f6 !important;
            border-color: #d1d5db !important;
        }

        /* ── DataTable pagination ── */
        .dataTables_wrapper .dataTables_info {
            font-size: .78rem;
            color: #9ca3af;
            padding-top: 14px;
            padding-left: 4px;
        }

        .dataTables_wrapper .dataTables_paginate {
            padding-top: 10px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 8px !important;
            margin: 0 2px;
            border: none !important;
            background: transparent !important;
            font-size: .82rem;
            color: #6b7280 !important;
            padding: 5px 11px !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: linear-gradient(135deg, #4f46e5, #7c3aed) !important;
            color: #fff !important;
            border: none !important;
            box-shadow: 0 3px 10px rgba(79, 70, 229, .35);
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #eef2ff !important;
            color: #4f46e5 !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
            color: #d1d5db !important;
        }

        @media (max-width:768px) {
            .card-body {
                padding: 16px !important;
            }

            .attendance-filter-strip {
                padding: 12px;
                gap: 10px;
            }

            .filter-search-box,
            .filter-date-box,
            .filter-status-box,
            .filter-reset-box {
                flex: 1 1 100%;
            }

            .filter-date-range {
                flex-direction: column;
                align-items: stretch;
                gap: 8px;
            }

            .filter-date-separator {
                margin-top: 0;
                text-align: center;
            }

            #statsRow .col-6 {
                margin-bottom: 10px;
            }

            .card-header .d-flex {
                flex-direction: column;
                align-items: flex-start !important;
            }
        }
    </style>
@stop

{{-- ============================================================
     Scripts
     ============================================================ --}}
@section('js')
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {

            const RECENT_URL = "{{ route('attendance.recent') }}";
            const TODAY_URL = "{{ route('attendance.today') }}";

            /* ── Helpers ──────────────────────────────────────── */
            function formatTime(t) {
                if (!t) return '<span style="color:#d1d5db;">—</span>';
                const [h, m] = t.split(':');
                const hour = parseInt(h);
                const ampm = hour >= 12 ? 'PM' : 'AM';
                const hour12 = hour % 12 || 12;
                return `${String(hour12).padStart(2,'0')}:${m} <span style="font-size:.7rem;font-weight:700;">${ampm}</span>`;
            }

            function fmtDate(dateStr) {
                const [year, month, day] = dateStr.split('T')[0].split('-').map(Number);
                const d = new Date(year, month - 1, day);
                return d.toLocaleDateString('en-US', {
                    weekday: 'short',
                    month: 'short',
                    day: 'numeric',
                    year: 'numeric'
                });
            }

            function statusBadge(s) {
                const map = {
                    'Present': ['badge-present', 'fa-check-circle', 'Present'],
                    'Late': ['badge-late', 'fa-exclamation-circle', 'Late'],
                    'Absent': ['badge-absent', 'fa-times-circle', 'Absent'],
                    'Leave': ['badge-leave', 'fa-calendar-minus', 'Leave'],
                };
                const [cls, icon, label] = map[s] || ['badge-leave', 'fa-circle', s];
                return `<span class="${cls}"><i class="fas ${icon}" style="font-size:.5rem;"></i>${label}</span>`;
            }

            /* ── Today's status ───────────────────────────────── */
            $.getJSON(TODAY_URL, function(json) {
                if (json.success && json.attendance) {
                    $('#stat-status').html(statusBadge(json.attendance.status));
                }
            });

            /* ── DataTable ────────────────────────────────────── */
            const now = new Date();
            const todayStr =
                `${now.getFullYear()}-${String(now.getMonth()+1).padStart(2,'0')}-${String(now.getDate()).padStart(2,'0')}`;

            // ── Custom filter: date range + status ──────────────
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex, rowData) {
                if (settings.nTable.id !== 'myAttendanceTable') return true;

                const dateFrom = $('#filterDateFrom').val();
                const dateTo = $('#filterDateTo').val();
                const status = $('#filterStatus').val();
                const rowDate = rowData.attendance_date.split('T')[0]; // YYYY-MM-DD
                const rowStatus = rowData.status;

                if (dateFrom && rowDate < dateFrom) return false;
                if (dateTo && rowDate > dateTo) return false;
                if (status && rowStatus !== status) return false;

                return true;
            });

            const table = $('#myAttendanceTable').DataTable({
                processing: true,
                serverSide: false,
                dom: 't<"d-flex align-items-center justify-content-between px-1 pt-2"ip>',
                ajax: {
                    url: RECENT_URL,
                    type: 'GET',
                    dataSrc: function(json) {
                        const records = json.data || [];

                        // Monthly stats (always based on full data, not filtered view)
                        const currentMonth = now.getMonth();
                        const currentYear = now.getFullYear();

                        const monthRecords = records.filter(r => {
                            const [y, m] = r.attendance_date.split('T')[0].split('-').map(
                                Number);
                            return (m - 1) === currentMonth && y === currentYear;
                        });

                        const lateCount = monthRecords.filter(r => r.status === 'Late').length;
                        const presentCount = monthRecords.filter(r => ['Present', 'Late'].includes(r
                            .status)).length;
                        const rate = monthRecords.length ?
                            ((presentCount / monthRecords.length) * 100).toFixed(1) :
                            0;

                        $('#stat-present').text(presentCount);
                        $('#stat-late').text(lateCount);
                        $('#stat-rate').text(rate + '%');

                        return records;
                    },
                    error: function() {
                        console.error('Failed to load attendance records.');
                    }
                },
                order: [
                    [1, 'desc']
                ],
                language: {
                    processing: '<i class="fas fa-spinner fa-spin mr-2" style="color:#4f46e5;"></i><span style="color:#4f46e5;">Loading...</span>',
                    emptyTable: '<div style="padding:40px 0;color:#9ca3af;"><i class="fas fa-inbox" style="font-size:2rem;display:block;margin-bottom:10px;opacity:.4;"></i>No attendance records found.</div>',
                    info: 'Showing _START_–_END_ of <strong>_TOTAL_</strong> records',
                    paginate: {
                        previous: '<i class="fas fa-chevron-left"></i>',
                        next: '<i class="fas fa-chevron-right"></i>'
                    }
                },
                rowCallback: function(row, data) {
                    if (data.attendance_date.split('T')[0] === todayStr) {
                        $(row).addClass('today-row');
                    }
                },
                columns: [
                    // #
                    {
                        data: null,
                        render: (d, t, r, m) =>
                            `<span class="row-num">${m.row + m.settings._iDisplayStart + 1}</span>`,
                        orderable: false
                    },
                    // Date
                    {
                        data: 'attendance_date',
                        render: d => {
                            const rowDate = d.split('T')[0];
                            const isToday = rowDate === todayStr;
                            return `${isToday ? '<span class="badge badge-primary mr-1" style="font-size:.68rem;">Today</span>' : ''}
                                    <span style="color:#1a1f36;font-weight:${isToday ? '700' : '400'};">${fmtDate(d)}</span>`;
                        }
                    },
                    // Check In
                    {
                        data: 'check_in',
                        render: t => t ?
                            `<span style="color:#059669;font-weight:600;">${formatTime(t)}</span>` :
                            '<span style="color:#d1d5db;">—</span>'
                    },
                    // Check Out
                    {
                        data: 'check_out',
                        render: t => t ?
                            `<span style="color:#ef4444;font-weight:600;">${formatTime(t)}</span>` :
                            '<span style="color:#d1d5db;">—</span>'
                    },
                    // Status
                    {
                        data: 'status',
                        className: 'text-center',
                        render: s => statusBadge(s)
                    },
                    // Hours
                    {
                        data: 'working_hours',
                        className: 'text-center',
                        render: h => h != null ?
                            `<span style="font-weight:600;color:#1a1f36;">${h} hr</span>` :
                            '<span style="color:#d1d5db;">—</span>'
                    },
                ]
            });

            /* ── Search ───────────────────────────────────────── */
            $('#searchAttendance').on('keyup', function() {
                const val = $(this).val();
                $('#clearSearch').toggle(val.length > 0);
                table.search(val).draw();
            });

            $('#clearSearch').on('click', function() {
                $('#searchAttendance').val('').trigger('keyup');
            });

            /* ── Date range + status filter ───────────────────── */
            $('#filterDateFrom, #filterDateTo, #filterStatus').on('change', function() {
                table.draw();
            });

            /* ── Reset all filters ────────────────────────────── */
            $('#btnResetFilter').on('click', function() {
                $('#searchAttendance').val('');
                $('#clearSearch').hide();
                $('#filterDateFrom').val('');
                $('#filterDateTo').val('');
                $('#filterStatus').val('');
                table.search('').draw();
            });

        });
    </script>
@stop
