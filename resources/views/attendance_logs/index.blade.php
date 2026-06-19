{{-- resources/views/attendance_logs/index.blade.php --}}
@extends('adminlte::page')

@section('title', 'Attendance Logs')

@section('content_header')
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h1 class="m-0 font-weight-bold" style="color:#1a1f36;font-size:1.4rem;letter-spacing:-0.3px;">
                <i class="fas fa-history mr-2" style="color:#4f46e5;"></i> Attendance Logs
            </h1>
            <ol class="breadcrumb mt-1 mb-0" style="background:transparent;padding:0;font-size:.8rem;">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" style="color:#4f46e5;">Home</a></li>
                <li class="breadcrumb-item active" style="color:#6b7280;">Attendance Logs</li>
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
                        <div style="width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,#4f46e5,#7c3aed);display:flex;align-items:center;justify-content:center;margin-right:12px;">
                            <i class="fas fa-list" style="color:#fff;font-size:.85rem;"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 font-weight-bold" style="font-size:1rem;color:#1a1f36;">Attendance Logs</h3>
                            <small style="color:#9ca3af;font-size:.75rem;">All attendance records — <span id="todayDate"></span></small>
                        </div>
                    </div>

                    <div class="d-flex align-items-center ml-auto mt-2" style="gap:10px;flex-wrap:wrap;">
                        {{-- Export --}}
                        <button type="button" id="btnExport"
                            class="btn btn-sm"
                            style="background:linear-gradient(135deg,#10b981,#059669);color:#fff;border:none;border-radius:10px;padding:8px 18px;font-weight:600;font-size:.82rem;letter-spacing:.2px;box-shadow:0 4px 14px rgba(16,185,129,.3);white-space:nowrap;">
                            <i class="fas fa-file-excel mr-1"></i> Export CSV
                        </button>
                    </div>
                </div>

                <div class="card-body" style="padding:24px;background:#fafbff;">

                    {{-- Stats --}}
                    <div class="row mb-4">
                        <div class="col-6 col-md-3 mb-3 mb-md-0">
                            <div style="background:#fff;border-radius:12px;padding:16px 20px;border:1px solid #f0f0f5;display:flex;align-items:center;gap:14px;">
                                <div style="width:42px;height:42px;border-radius:10px;background:#eef2ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas fa-clock" style="color:#4f46e5;"></i>
                                </div>
                                <div>
                                    <div class="font-weight-bold" id="statTotal" style="font-size:1.4rem;color:#1a1f36;line-height:1;">—</div>
                                    <div style="font-size:.72rem;color:#9ca3af;margin-top:2px;">Total logs (filtered)</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3 mb-md-0">
                            <div style="background:#fff;border-radius:12px;padding:16px 20px;border:1px solid #f0f0f5;display:flex;align-items:center;gap:14px;">
                                <div style="width:42px;height:42px;border-radius:10px;background:#ecfdf5;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas fa-sign-in-alt" style="color:#10b981;"></i>
                                </div>
                                <div>
                                    <div class="font-weight-bold" id="statIn" style="font-size:1.4rem;color:#1a1f36;line-height:1;">—</div>
                                    <div style="font-size:.72rem;color:#9ca3af;margin-top:2px;">Check In</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3 mb-md-0">
                            <div style="background:#fff;border-radius:12px;padding:16px 20px;border:1px solid #f0f0f5;display:flex;align-items:center;gap:14px;">
                                <div style="width:42px;height:42px;border-radius:10px;background:#fffbeb;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas fa-sign-out-alt" style="color:#f59e0b;"></i>
                                </div>
                                <div>
                                    <div class="font-weight-bold" id="statOut" style="font-size:1.4rem;color:#1a1f36;line-height:1;">—</div>
                                    <div style="font-size:.72rem;color:#9ca3af;margin-top:2px;">Check Out</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div style="background:#fff;border-radius:12px;padding:16px 20px;border:1px solid #f0f0f5;display:flex;align-items:center;gap:14px;">
                                <div style="width:42px;height:42px;border-radius:10px;background:#fdf4ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas fa-users" style="color:#9333ea;"></i>
                                </div>
                                <div>
                                    <div class="font-weight-bold" id="statEmployees" style="font-size:1.4rem;color:#1a1f36;line-height:1;">—</div>
                                    <div style="font-size:.72rem;color:#9ca3af;margin-top:2px;">Employees Present</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Filters Row --}}
                    <div class="d-flex flex-wrap mb-3" style="gap:10px;align-items:center;">

                        {{-- Search --}}
                        <div style="position:relative;min-width:200px;flex:1;">
                            <i class="fas fa-search" style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:.8rem;pointer-events:none;"></i>
                            <input type="text" class="form-control form-control-sm" placeholder="Search name or employee ID..."
                                id="searchInput" style="padding-left:32px;padding-right:32px;border-radius:10px;border:1.5px solid #e5e7eb;">
                            <i class="fas fa-times" id="clearSearch"
                                style="display:none;position:absolute;right:10px;top:50%;transform:translateY(-50%);cursor:pointer;color:#9ca3af;font-size:.8rem;"></i>
                        </div>

                        {{-- Log Type Filter --}}
                        <div style="min-width:150px;">
                            <select id="filterType" class="form-control form-control-sm" style="border-radius:10px;border:1.5px solid #e5e7eb;color:#374151;">
                                <option value="">All Types</option>
                                <option value="Check In">Check In</option>
                                <option value="Check Out">Check Out</option>
                            </select>
                        </div>

                        {{-- Date From --}}
                        <div style="min-width:140px;">
                            <input type="date" id="filterDateFrom" class="form-control form-control-sm"
                                style="border-radius:10px;border:1.5px solid #e5e7eb;color:#374151;">
                        </div>

                        {{-- Date To --}}
                        <div style="min-width:140px;">
                            <input type="date" id="filterDateTo" class="form-control form-control-sm"
                                style="border-radius:10px;border:1.5px solid #e5e7eb;color:#374151;">
                        </div>

                        {{-- Reset --}}
                        <button type="button" id="btnReset"
                            style="background:#f3f4f6;color:#6b7280;border:none;border-radius:10px;padding:6px 14px;font-size:.82rem;font-weight:600;white-space:nowrap;">
                            <i class="fas fa-undo mr-1"></i> Reset
                        </button>

                    </div>

                    {{-- Table --}}
                    <div style="background:#fff;border-radius:12px;border:1px solid #f0f0f5;overflow:hidden;">
                        <div style="overflow-x:auto;-webkit-overflow-scrolling:touch;">
                            <table id="attendanceTable" class="table table-hover w-100 mb-0" style="min-width:800px;">
                                <thead>
                                    <tr style="background:#f8f9ff;">
                                        <th width="50"  style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">#</th>
                                        <th style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">Employee</th>
                                        <th style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">Date & Time</th>
                                        <th width="120" style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">Type</th>
                                        <th style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">Device</th>
                                        <th style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">IP Address</th>
                                        <th style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">GPS</th>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        #attendanceTable tbody tr { transition: background .15s; }
        #attendanceTable tbody tr:hover { background: #f5f6ff !important; }
        #attendanceTable tbody td {
            padding: 13px 20px;
            vertical-align: middle;
            font-size: .875rem;
            color: #374151;
            border-color: #f3f4f6;
        }
        .badge-in {
            background: #ecfdf5; color: #059669;
            padding: 5px 14px; border-radius: 20px;
            font-size: .75rem; font-weight: 700;
            display: inline-flex; align-items: center; gap: 6px;
            white-space: nowrap; line-height: 1;
        }
        .badge-out {
            background: #fffbeb; color: #d97706;
            padding: 5px 14px; border-radius: 20px;
            font-size: .75rem; font-weight: 700;
            display: inline-flex; align-items: center; gap: 6px;
            white-space: nowrap; line-height: 1;
        }
        .row-num {
            width: 26px; height: 26px; border-radius: 6px;
            background: #f3f4f6; color: #6b7280;
            font-size: .75rem; font-weight: 700;
            display: inline-flex; align-items: center; justify-content: center;
        }
        .emp-avatar {
            width: 34px; height: 34px; border-radius: 50%;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; overflow: hidden;
            font-size: .72rem; font-weight: 700; color: #fff;
        }
        .emp-avatar img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; }
        .emp-id-badge {
            background: #eef2ff; color: #4f46e5;
            border-radius: 6px; padding: 2px 7px;
            font-size: .68rem; font-weight: 700; letter-spacing: .3px;
        }
        .form-control:focus { border-color: #4f46e5 !important; box-shadow: 0 0 0 3px rgba(79,70,229,.12) !important; }
        .dataTables_wrapper .dataTables_info { font-size:.78rem;color:#9ca3af;padding-top:14px;padding-left:4px; }
        .dataTables_wrapper .dataTables_paginate { padding-top:10px; }
        .dataTables_wrapper .dataTables_paginate .paginate_button { border-radius:8px!important;margin:0 2px;border:none!important;background:transparent!important;font-size:.82rem;color:#6b7280!important;padding:5px 11px!important; }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover { background:linear-gradient(135deg,#4f46e5,#7c3aed)!important;color:#fff!important;border:none!important;box-shadow:0 3px 10px rgba(79,70,229,.35); }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover { background:#eef2ff!important;color:#4f46e5!important; }
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover { color:#d1d5db!important; }
        #btnExport:hover { transform:translateY(-1px); box-shadow:0 6px 20px rgba(16,185,129,.4)!important; }
        #btnReset:hover { background:#e5e7eb !important; }
        #toast-container>.toast { border-radius:12px!important;box-shadow:0 8px 30px rgba(0,0,0,.12)!important; }
        @media (max-width:576px) { .card-body { padding:16px!important; } }
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
    $(document).ready(function () {

        toastr.options = { closeButton:true, progressBar:true, positionClass:'toast-top-right', timeOut:3000 };

        const DATA_URL    = '{{ route("attendance-logs.data") }}';
        const STORAGE_URL = '{{ asset("storage") }}';
        const today       = new Date();

        $('#todayDate').text(today.toLocaleDateString('en-GB', {
            weekday:'long', day:'2-digit', month:'long', year:'numeric'
        }));

        /* ── Default date range: today ─────────────────────── */
        const todayStr = today.toISOString().slice(0, 10);
        $('#filterDateFrom').val(todayStr);
        $('#filterDateTo').val(todayStr);

        /* ── Employee photo helper ─────────────────────────── */
        function avatarHtml(emp) {
            if (!emp) return '<span style="color:#d1d5db;">—</span>';

            const initials = ((emp.first_name?.[0] ?? '') + (emp.last_name?.[0] ?? '')).toUpperCase();

            // Build photo URL — same logic as employees/index.blade.php
            let photoUrl = null;
            if (emp.photo) {
                const p = String(emp.photo).replace(/^\/+/, '');
                photoUrl = p.startsWith('http') ? p : `${STORAGE_URL}/${p}`;
            }

            const avatarInner = photoUrl
                ? `<img src="${photoUrl}"
                    alt="${initials}"
                    style="width:100%;height:100%;object-fit:cover;border-radius:50%;"
                    onerror="this.style.display='none';this.nextSibling.style.display='flex';">
                <span style="display:none;width:100%;height:100%;align-items:center;justify-content:center;font-size:.7rem;font-weight:700;">${initials}</span>`
                : `<span>${initials}</span>`;

            return `<div style="display:flex;align-items:center;gap:10px;">
                        <div class="emp-avatar">${avatarInner}</div>
                        <div>
                            <div style="font-weight:600;color:#1a1f36;font-size:.88rem;line-height:1.3;">
                                ${emp.first_name} ${emp.last_name}
                            </div>
                            <span class="emp-id-badge">${emp.employee_code ?? '—'}</span>
                        </div>
                    </div>`;
        }

        /* ── Custom filter: date range + type ─────────────── */
        $.fn.dataTable.ext.search.push(function (settings, data, dataIndex, rowData) {
            const from    = $('#filterDateFrom').val();
            const to      = $('#filterDateTo').val();
            const typeVal = $('#filterType').val();

            // Date filter
            if (from || to) {
                const rowDate = rowData.log_datetime ? rowData.log_datetime.slice(0, 10) : '';
                if (from && rowDate < from) return false;
                if (to   && rowDate > to)   return false;
            }

            // Type filter
            if (typeVal && rowData.log_type !== typeVal) return false;

            return true;
        });

        /* ── DataTable ─────────────────────────────────────── */
        const table = $('#attendanceTable').DataTable({
            processing: true,
            serverSide: false,
            dom: 't<"d-flex align-items-center justify-content-between px-1 pt-2"ip>',
            ajax: {
                url: DATA_URL,
                type: 'GET',
            dataSrc: function (json) {
                const data = json.data || [];

                // Filter today only for stats
                const from = $('#filterDateFrom').val();
                const to   = $('#filterDateTo').val();

                // Count only records matching current date filter
                const filtered = data.filter(r => {
                    if (!r.log_datetime) return false;
                    const rowDate = r.log_datetime.slice(0, 10);
                    if (from && rowDate < from) return false;
                    if (to   && rowDate > to)   return false;
                    return true;
                });

                $('#statTotal').text(filtered.length);
                $('#statIn').text(filtered.filter(r => r.log_type === 'Check In').length);
                $('#statOut').text(filtered.filter(r => r.log_type === 'Check Out').length);
                const unique = new Set(filtered.map(r => r.employee_id));
                $('#statEmployees').text(unique.size);

                return data;
            },
                error: function () { toastr.error('Failed to load attendance logs.'); }
            },
            language: {
                processing: '<i class="fas fa-spinner fa-spin mr-2" style="color:#4f46e5;"></i><span style="color:#4f46e5;">Loading...</span>',
                emptyTable: '<div style="padding:40px 0;color:#9ca3af;"><i class="fas fa-inbox" style="font-size:2rem;display:block;margin-bottom:10px;opacity:.4;"></i>No attendance logs found.</div>',
                info: 'Showing _START_–_END_ of <strong>_TOTAL_</strong> logs',
                paginate: { previous: '<i class="fas fa-chevron-left"></i>', next: '<i class="fas fa-chevron-right"></i>' }
            },
            order: [[2, 'desc']],
            columns: [
                {
                    data: null, orderable: false, searchable: false,
                    render: (d, t, r, m) => `<span class="row-num">${m.row + m.settings._iDisplayStart + 1}</span>`
                },
                {
                    data: 'employee',
                    render: (emp) => avatarHtml(emp),
                    // Make searchable by name and ID
                    createdCell: function (td, cellData, rowData) {
                        if (rowData.employee) {
                            $(td).attr('data-search',
                                `${rowData.employee.first_name} ${rowData.employee.last_name} ${rowData.employee.employee_id}`
                            );
                        }
                    }
                },
                {
                    data: 'log_datetime',
                    render: d => d
                        ? `<span style="color:#374151;">${new Date(d).toLocaleString('en-GB', {
                            day:'2-digit', month:'short', year:'numeric',
                            hour:'2-digit', minute:'2-digit'
                          })}</span>`
                        : '<span style="color:#d1d5db;">—</span>'
                },
                {
                    data: 'log_type',
                    render: t => (t === 'Check In')
                        ? '<span class="badge-in"><i class="fas fa-circle" style="font-size:.45rem;"></i> IN</span>'
                        : '<span class="badge-out"><i class="fas fa-circle" style="font-size:.45rem;"></i> OUT</span>'
                },
                {
                    data: 'device_name',
                    render: d => d
                        ? `<span style="color:#6b7280;font-size:.82rem;" title="${d}">${d.substring(0,30)}${d.length > 30 ? '…' : ''}</span>`
                        : '<span style="color:#d1d5db;">—</span>'
                },
                {
                    data: 'ip_address',
                    render: ip => ip
                        ? `<span style="font-family:monospace;font-size:.82rem;color:#6b7280;">${ip}</span>`
                        : '<span style="color:#d1d5db;">—</span>'
                },
                {
                    data: 'gps_location',
                    render: gps => gps
                        ? `<a href="https://maps.google.com/?q=${gps}" target="_blank"
                              style="color:#4f46e5;font-size:.82rem;text-decoration:none;white-space:nowrap;">
                               <i class="fas fa-map-marker-alt mr-1"></i>View Map
                           </a>`
                        : '<span style="color:#d1d5db;">—</span>'
                },
            ]
        });

        /* ── Override DataTables search to use data-search attr ── */
        $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
            const q = $('#searchInput').val().toLowerCase().trim();
            if (!q) return true;
            const row = table.row(dataIndex).node();
            const empCell = $(row).find('td:nth-child(2)');
            const searchText = (empCell.attr('data-search') || empCell.text()).toLowerCase();
            return searchText.includes(q);
        });

        /* ── Search input ──────────────────────────────────── */
        $('#searchInput').on('keyup', function () {
            const val = $(this).val();
            $('#clearSearch').toggle(val.length > 0);
            table.draw();
        });

        $('#clearSearch').on('click', function () {
            $('#searchInput').val('');
            $(this).hide();
            table.draw();
        });

        /* ── Filter controls ───────────────────────────────── */
        $('#filterType, #filterDateFrom, #filterDateTo').on('change', function () {
            // Recalculate stats based on current filter
            const allData = table.ajax.json()?.data || [];
            const from = $('#filterDateFrom').val();
            const to   = $('#filterDateTo').val();
            const type = $('#filterType').val();

            const filtered = allData.filter(r => {
                if (!r.log_datetime) return false;
                const rowDate = r.log_datetime.slice(0, 10);
                if (from && rowDate < from) return false;
                if (to   && rowDate > to)   return false;
                if (type && r.log_type !== type) return false;
                return true;
            });

            $('#statTotal').text(filtered.length);
            $('#statIn').text(filtered.filter(r => r.log_type === 'Check In').length);
            $('#statOut').text(filtered.filter(r => r.log_type === 'Check Out').length);
            const unique = new Set(filtered.map(r => r.employee_id));
            $('#statEmployees').text(unique.size);

            table.draw();
        });

        /* ── Reset filters ─────────────────────────────────── */
        $('#btnReset').on('click', function () {
            $('#searchInput').val('');
            $('#clearSearch').hide();
            $('#filterType').val('');
            $('#filterDateFrom').val('');
            $('#filterDateTo').val('');
            table.search('').draw();
        });

        /* ── Export CSV ────────────────────────────────────── */
        $('#btnExport').on('click', function () {
            const rows = table.rows({ search: 'applied' }).data().toArray();

            if (!rows.length) {
                toastr.warning('No data to export.');
                return;
            }

            const label = `${$('#filterDateFrom').val() || 'all'}_to_${$('#filterDateTo').val() || 'all'}`;
            const headers = ['#', 'Employee ID', 'Employee Name', 'Date & Time', 'Type', 'Device', 'IP Address', 'GPS'];
            const csv = [
                headers.join(','),
                ...rows.map((r, i) => [
                    i + 1,
                    r.employee?.employee_id ?? '',
                    `"${r.employee ? r.employee.first_name + ' ' + r.employee.last_name : ''}"`,
                    `"${new Date(r.log_datetime).toLocaleString('en-GB')}"`,
                    r.log_type,
                    `"${r.device_name || ''}"`,
                    r.ip_address || '',
                    r.gps_location || '',
                ].join(','))
            ].join('\n');

            const blob = new Blob([csv], { type: 'text/csv' });
            const url  = URL.createObjectURL(blob);
            const a    = document.createElement('a');
            a.href     = url;
            a.download = `attendance_logs_${label}.csv`;
            a.click();
            URL.revokeObjectURL(url);

            toastr.success('Exported successfully.');
        });

    });
    </script>
@stop