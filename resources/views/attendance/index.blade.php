{{-- resources/views/attendance/index.blade.php --}}
@extends('adminlte::page')

@section('title', 'Attendance - Attendance System')

@section('content_header')
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h1 class="m-0 font-weight-bold" style="color:#1a1f36;font-size:1.4rem;letter-spacing:-0.3px;">
                <i class="fas fa-clock mr-2" style="color:#4f46e5;"></i> Attendance
            </h1>
            <ol class="breadcrumb mt-1 mb-0" style="background:transparent;padding:0;font-size:.8rem;">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" style="color:#4f46e5;">Home</a></li>
                <li class="breadcrumb-item active" style="color:#6b7280;">Attendance</li>
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
                <div class="card-header d-flex align-items-center justify-content-between flex-wrap"
                    style="background:#fff;border-bottom:1px solid #f0f0f5;padding:18px 24px;gap:10px;">

                    <div class="d-flex align-items-center">
                        <div
                            style="width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,#4f46e5,#7c3aed);display:flex;align-items:center;justify-content:center;margin-right:12px;">
                            <i class="fas fa-list" style="color:#fff;font-size:.85rem;"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 font-weight-bold" style="font-size:1rem;color:#1a1f36;">
                                Attendance Records
                            </h3>
                            <small style="color:#9ca3af;font-size:.75rem;">
                                Track employee check-ins, check-outs, working hours, and attendance status.
                            </small>
                        </div>
                    </div>

                    {{-- Right Side --}}
                    <div class="d-flex align-items-center ml-auto mt-3" style="gap:10px;flex-wrap:wrap;">

                        {{-- Search --}}
                        <div style="position:relative;width:250px;min-width:180px;">
                            <input type="text" class="form-control form-control-sm" placeholder="Search employee..."
                                id="searchAttendance" style="padding-right:35px;">
                            <i class="fas fa-times" id="clearSearch"
                                style="display:none;position:absolute;right:10px;top:50%;transform:translateY(-50%);cursor:pointer;color:#6b7280;"></i>
                        </div>


                    </div>
                </div>

                <div class="card-body" style="padding:24px;background:#fafbff;">

                    {{-- Stats Row --}}
                    <div class="row mb-4" id="statsRow">
                        <div class="col-6 col-md-3 mb-3 mb-md-0">
                            <div
                                style="background:#fff;border-radius:12px;padding:16px 20px;border:1px solid #f0f0f5;display:flex;align-items:center;gap:14px;">
                                <div
                                    style="width:42px;height:42px;border-radius:10px;background:#eef2ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas fa-users" style="color:#4f46e5;"></i>
                                </div>
                                <div>
                                    <div class="font-weight-bold" id="statTotal"
                                        style="font-size:1.4rem;color:#1a1f36;line-height:1;">—</div>
                                    <div style="font-size:.75rem;color:#9ca3af;margin-top:2px;">Total Records</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3 mb-md-0">
                            <div
                                style="background:#fff;border-radius:12px;padding:16px 20px;border:1px solid #f0f0f5;display:flex;align-items:center;gap:14px;">
                                <div
                                    style="width:42px;height:42px;border-radius:10px;background:#d1fae5;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas fa-check-circle" style="color:#059669;"></i>
                                </div>
                                <div>
                                    <div class="font-weight-bold" id="statPresent"
                                        style="font-size:1.4rem;color:#1a1f36;line-height:1;">—</div>
                                    <div style="font-size:.75rem;color:#9ca3af;margin-top:2px;">Present</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3 mb-md-0">
                            <div
                                style="background:#fff;border-radius:12px;padding:16px 20px;border:1px solid #f0f0f5;display:flex;align-items:center;gap:14px;">
                                <div
                                    style="width:42px;height:42px;border-radius:10px;background:#fef3c7;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas fa-exclamation-circle" style="color:#d97706;"></i>
                                </div>
                                <div>
                                    <div class="font-weight-bold" id="statLate"
                                        style="font-size:1.4rem;color:#1a1f36;line-height:1;">—</div>
                                    <div style="font-size:.75rem;color:#9ca3af;margin-top:2px;">Late</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3 mb-md-0">
                            <div
                                style="background:#fff;border-radius:12px;padding:16px 20px;border:1px solid #f0f0f5;display:flex;align-items:center;gap:14px;">
                                <div
                                    style="width:42px;height:42px;border-radius:10px;background:#fef2f2;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas fa-times-circle" style="color:#ef4444;"></i>
                                </div>
                                <div>
                                    <div class="font-weight-bold" id="statAbsent"
                                        style="font-size:1.4rem;color:#1a1f36;line-height:1;">—</div>
                                    <div style="font-size:.75rem;color:#9ca3af;margin-top:2px;">Absent</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Table --}}
                    <div style="background:#fff;border-radius:12px;border:1px solid #f0f0f5;overflow:hidden;">
                        <div style="overflow-x:auto;-webkit-overflow-scrolling:touch;">
                            <table id="attendanceTable" class="table table-hover w-100 mb-0" style="min-width:900px;">
                                <thead>
                                    <tr style="background:#f8f9ff;">
                                        <th width="50"
                                            style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">
                                            #</th>
                                        <th width="180"
                                            style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">
                                            Employee</th>
                                        <th width="120"
                                            style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">
                                            Date</th>
                                        <th width="100"
                                            style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">
                                            Check In</th>
                                        <th width="100"
                                            style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">
                                            Check Out</th>
                                        <th width="120"
                                            style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">
                                            Working Hours</th>
                                        <th width="110"
                                            style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">
                                            Late (min)</th>
                                        <th width="110"
                                            style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">
                                            Overtime (hr)</th>
                                        <th width="110" class="text-center"
                                            style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">
                                            Status</th>
                                        <th width="100" class="text-center"
                                            style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">
                                            Actions</th>
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

    {{-- ============================================================
         Modal: Confirm Delete
         ============================================================ --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content"
                style="border:none;border-radius:16px;overflow:hidden;box-shadow:0 25px 60px rgba(0,0,0,.15);">

                <div class="modal-header"
                    style="background:linear-gradient(135deg,#ef4444,#dc2626);padding:18px 22px;border:none;">
                    <div class="d-flex align-items-center">
                        <div
                            style="width:34px;height:34px;border-radius:9px;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;margin-right:10px;">
                            <i class="fas fa-exclamation-triangle" style="color:#fff;font-size:.85rem;"></i>
                        </div>
                        <h5 class="modal-title text-white mb-0 font-weight-bold" style="font-size:.95rem;">Confirm Delete
                        </h5>
                    </div>
                    <button type="button" class="close text-white" data-dismiss="modal" style="opacity:.8;">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body text-center" style="padding:28px 22px;background:#fff;">
                    <div
                        style="width:56px;height:56px;border-radius:50%;background:#fef2f2;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                        <i class="fas fa-trash-alt" style="color:#ef4444;font-size:1.2rem;"></i>
                    </div>
                    <p class="mb-1" style="color:#374151;font-size:.9rem;">Are you sure you want to delete this
                        attendance record for</p>
                    <strong id="deleteName" style="color:#ef4444;font-size:.95rem;"></strong>
                    <p style="color:#ef4444;font-size:.9rem;display:inline;">?</p>
                    <p class="mt-3 mb-0 py-2 px-3"
                        style="background:#fef2f2;border-radius:8px;font-size:.78rem;color:#9ca3af;">
                        <i class="fas fa-info-circle mr-1" style="color:#ef4444;"></i>
                        This action cannot be undone.
                    </p>
                </div>

                <div class="modal-footer"
                    style="padding:14px 22px;background:#f9fafb;border-top:1px solid #f0f0f5;justify-content:space-between;">
                    <button type="button" class="btn btn-sm" data-dismiss="modal"
                        style="border-radius:9px;border:1.5px solid #e5e7eb;color:#6b7280;padding:7px 16px;font-weight:600;font-size:.82rem;background:#fff;">
                        <i class="fas fa-times mr-1"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-sm" id="btnConfirmDelete"
                        style="background:linear-gradient(135deg,#ef4444,#dc2626);color:#fff;border:none;border-radius:9px;padding:7px 18px;font-weight:600;font-size:.82rem;box-shadow:0 4px 12px rgba(239,68,68,.3);">
                        <i class="fas fa-trash mr-1"></i> Delete
                    </button>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        /* ── Stats Row: 2x2 on all screens below desktop ── */
        @media (max-width: 992px) {
            #statsRow .col-md-3 {
                flex: 0 0 50%;
                max-width: 50%;
                margin-bottom: 12px;
            }
        }

        @media (max-width: 576px) {
            #statsRow [id^="stat"] {
                font-size: 1.15rem !important;
            }

            #statsRow>.col-md-3>div {
                padding: 12px 14px !important;
                gap: 10px !important;
            }

            #statsRow>.col-md-3>div>div:first-child {
                width: 36px !important;
                height: 36px !important;
            }
        }

        #attendanceTable tbody tr {
            transition: background .15s;
        }

        #attendanceTable tbody tr:hover {
            background: #f5f6ff !important;
        }

        #attendanceTable tbody td {
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

        .badge-halfday {
            background: #ede9fe;
            color: #7c3aed;
        }

        .badge-present,
        .badge-late,
        .badge-absent,
        .badge-halfday {
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

        /* ── Action buttons ── */
        .btn-delete-row {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: #fef2f2;
            color: #ef4444;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all .2s;
            font-size: .8rem;
        }

        .btn-delete-row:hover {
            background: #ef4444;
            color: #fff;
            box-shadow: 0 4px 12px rgba(239, 68, 68, .3);
            transform: translateY(-1px);
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

        .form-control:focus {
            border-color: #4f46e5 !important;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, .12) !important;
        }

        #btnCheckIn:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(5, 150, 105, .4) !important;
        }

        #btnCheckOut:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(245, 158, 11, .4) !important;
        }

        #toast-container>.toast {
            border-radius: 12px !important;
            box-shadow: 0 8px 30px rgba(0, 0, 0, .12) !important;
        }

        @media (max-width:576px) {
            .card-body {
                padding: 16px !important;
            }

            #statsRow .col-6 {
                margin-bottom: 10px;
            }
        }
    </style>
@stop

{{-- ============================================================
     Scripts
     ============================================================ --}}
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {

            /* ── Config ───────────────────────────────────────── */
            toastr.options = {
                closeButton: true,
                progressBar: true,
                positionClass: 'toast-top-right',
                timeOut: 3000
            };

            const DATA_URL = '{{ url('attendance/data') }}';
            const BASE_URL = '{{ route('attendance.index') }}';
            const CHECKIN_URL = '{{ url('attendance/check-in') }}';
            const CHECKOUT_URL = '{{ url('attendance/check-out') }}';
            const CSRF = '{{ csrf_token() }}';
            let deleteId = null;

            /// Helper to format time in 12-hour format with AM/PM
            function formatTime(t) {
                if (!t) return '<span style="color:#d1d5db;">—</span>';
                const [h, m] = t.split(':');
                const hour = parseInt(h);
                const ampm = hour >= 12 ? 'PM' : 'AM';
                const hour12 = hour % 12 || 12;
                return `${String(hour12).padStart(2,'0')}:${m} <span style="font-size:.7rem;font-weight:700;">${ampm}</span>`;
            }

            /* ── Status badge helper ──────────────────────────── */
            function statusBadge(s) {
                const map = {
                    'Present': ['badge-present', 'fa-check-circle', 'Present'],
                    'Late': ['badge-late', 'fa-exclamation-circle', 'Late'],
                    'Absent': ['badge-absent', 'fa-times-circle', 'Absent'],
                    'Half Day': ['badge-halfday', 'fa-adjust', 'Half Day'],
                    'holiday': ['badge-halfday', 'fa-umbrella-beach', 'Holiday']
                };
                const [cls, icon, label] = map[s] || ['badge-halfday', 'fa-circle', s];
                return `<span class="${cls}"><i class="fas ${icon}" style="font-size:.5rem;"></i>${label}</span>`;
            }

            /* ── DataTable ────────────────────────────────────── */
            const table = $('#attendanceTable').DataTable({
                processing: true,
                serverSide: false,
                dom: 't<"d-flex align-items-center justify-content-between px-1 pt-2"ip>',
                ajax: {
                    url: DATA_URL,
                    type: 'GET',
                    dataSrc: function(json) {
                        const data = json.data || [];
                        $('#statTotal').text(data.length);
                        $('#statPresent').text(data.filter(r => r.status === 'Present').length);
                        $('#statLate').text(data.filter(r => r.status === 'Late').length);
                        $('#statAbsent').text(data.filter(r => r.status === 'Absent').length);
                        $('#statHoliday').text(data.filter(r => r.status === 'holiday').length);
                        return data;
                    },
                    error: function() {
                        toastr.error('Failed to load attendance records.');
                    }
                },
                order: [
                    [2, 'desc']
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
                columns: [{
                        data: null,
                        render: (d, t, r, m) =>
                            `<span class="row-num">${m.row + m.settings._iDisplayStart + 1}</span>`,
                        orderable: false
                    },
                    {
                        data: 'employee',
                        render: emp => emp ?
                            `
            <span style="
                background:#eef2ff;
                color:#4338ca;
                padding:6px 14px;
                border-radius:20px;
                font-size:13px;
                font-weight:600;
            ">
                ${emp.first_name ?? ''} ${emp.last_name ?? ''}
            </span>
        ` :
                            `
            <span style="
                background:#f3f4f6;
                color:#9ca3af;
                padding:6px 14px;
                border-radius:20px;
                font-size:13px;
            ">
                —
            </span>
        `
                    },
                    {
                        data: 'attendance_date',
                        render: d => d ?
                            `<span>${
                                new Date(d).toLocaleDateString('en-CA', {
                                    timeZone: 'Asia/Phnom_Penh'
                                })
                                }</span>` : '<span style="color:#d1d5db;">—</span>'
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
                    {
                        data: 'working_hours',
                        render: h => h != null ?
                            `<span>${h} hr</span>` : '<span style="color:#d1d5db;">—</span>'
                    },
                    {
                        data: 'late_minutes',
                        render: m => m ?
                            `<span style="color:#d97706;font-weight:600;">${m} min</span>` :
                            '<span style="color:#6b7280;">0 min</span>'
                    },
                    {
                        data: 'overtime_hours',
                        render: h => h ?
                            `<span style="color:#4f46e5;font-weight:600;">${h} hr</span>` :
                            '<span style="color:#6b7280;">0 hr</span>'
                    },
                    {
                        data: 'status',
                        className: 'text-center',
                        render: s => statusBadge(s)
                    },
                    {
                        data: 'attendance_id',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: (id, t, row) => `
                            <div class="d-flex align-items-center justify-content-center" style="gap:6px;">
                                <button type="button" class="btn-delete-row btn-delete"
                                        data-id="${id}"
                                        data-name="${row.employee ? (row.employee.first_name + ' ' + row.employee.last_name) : 'this record'} (${row.attendance_date})"
                                        data-toggle="tooltip" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        `
                    }
                ]
            });

            /* ── Custom Search ────────────────────────────────── */
            $('#searchAttendance').on('keyup', function() {
                const val = $(this).val();
                $('#clearSearch').toggle(val.length > 0);
                table.search(val).draw();
            });

            $('#clearSearch').on('click', function() {
                $('#searchAttendance').val('');
                $(this).hide();
                table.search('').draw();
            });

            /* ── Tooltips ─────────────────────────────────────── */
            $(document).on('mouseenter', '[data-toggle="tooltip"]', function() {
                $(this).tooltip('show');
            });
            table.on('draw', function() {
                $('[data-toggle="tooltip"]').tooltip();
            });

            /* ── Check In ─────────────────────────────────────── */
            $('#btnCheckIn').on('click', function() {
                const $btn = $(this);
                $btn.prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin mr-1"></i> Checking In...');

                $.ajax({
                        url: CHECKIN_URL,
                        method: 'POST',
                        data: {
                            _token: CSRF
                        }
                    })
                    .done(function(res) {
                        if (res.success) {
                            toastr.success(res.message);
                            table.ajax.reload(null, false);
                        } else {
                            toastr.warning(res.message);
                        }
                    })
                    .fail(function(xhr) {
                        const msg = xhr.responseJSON?.message ?? 'Check-in failed. Please try again.';
                        toastr.error(msg);
                    })
                    .always(function() {
                        $btn.prop('disabled', false).html(
                            '<i class="fas fa-sign-in-alt mr-1"></i> Check In');
                    });
            });

            /* ── Check Out ────────────────────────────────────── */
            $('#btnCheckOut').on('click', function() {
                const $btn = $(this);
                $btn.prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin mr-1"></i> Checking Out...');

                $.ajax({
                        url: CHECKOUT_URL,
                        method: 'POST',
                        data: {
                            _token: CSRF
                        }
                    })
                    .done(function(res) {
                        if (res.success) {
                            toastr.success(res.message);
                            table.ajax.reload(null, false);
                        } else {
                            toastr.warning(res.message);
                        }
                    })
                    .fail(function(xhr) {
                        const msg = xhr.responseJSON?.message ?? 'Check-out failed. Please try again.';
                        toastr.error(msg);
                    })
                    .always(function() {
                        $btn.prop('disabled', false).html(
                            '<i class="fas fa-sign-out-alt mr-1"></i> Check Out');
                    });
            });

            /* ── DELETE ───────────────────────────────────────── */
            $(document).on('click', '.btn-delete', function() {
                deleteId = $(this).data('id');
                $('#deleteName').text($(this).data('name'));
                $('#deleteModal').modal('show');
            });

            $(document).on('click', '#btnConfirmDelete', function() {
                if (!deleteId) return;

                const $btn = $(this);
                $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Deleting...');

                $.ajax({
                        url: BASE_URL + '/' + deleteId,
                        method: 'POST',
                        data: {
                            _token: CSRF,
                            _method: 'DELETE'
                        }
                    })
                    .done(function(res) {
                        $('#deleteModal').modal('hide');
                        if (res.success) {
                            table.ajax.reload(null, false);
                            toastr.success(res.message);
                        } else {
                            toastr.error(res.message);
                        }
                    })
                    .fail(function() {
                        $('#deleteModal').modal('hide');
                        toastr.error('Something went wrong. Please try again.');
                    })
                    .always(function() {
                        deleteId = null;
                        $btn.prop('disabled', false).html('<i class="fas fa-trash mr-1"></i> Delete');
                    });
            });

        });
    </script>
@stop
