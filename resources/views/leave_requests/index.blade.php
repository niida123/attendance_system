{{-- resources/views/leave_requests/index.blade.php --}}
@extends('adminlte::page')

@section('title', 'Leave Requests')

@section('content_header')
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h1 class="m-0 font-weight-bold" style="color:#1a1f36;font-size:1.4rem;letter-spacing:-0.3px;">
                <i class="fas fa-calendar-minus mr-2" style="color:#4f46e5;"></i> Leave Requests
            </h1>
            <ol class="breadcrumb mt-1 mb-0" style="background:transparent;padding:0;font-size:.8rem;">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" style="color:#4f46e5;">Home</a></li>
                <li class="breadcrumb-item active" style="color:#6b7280;">Leave Requests</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card" style="border:none;border-radius:16px;box-shadow:0 1px 3px rgba(0,0,0,.08),0 8px 32px rgba(79,70,229,.07);overflow:hidden;">

            {{-- Header --}}
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap"
                style="background:#fff;border-bottom:1px solid #f0f0f5;padding:18px 24px;gap:10px;">
                <div class="d-flex align-items-center">
                    <div style="width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,#4f46e5,#7c3aed);display:flex;align-items:center;justify-content:center;margin-right:12px;">
                        <i class="fas fa-calendar-minus" style="color:#fff;font-size:.85rem;"></i>
                    </div>
                    <div>
                        <h3 class="mb-0 font-weight-bold" style="font-size:1rem;color:#1a1f36;">All Leave Requests</h3>
                        <small style="color:#9ca3af;font-size:.75rem;">Manage and approve employee leave</small>
                    </div>
                </div>
                <button type="button" id="btnExport" class="btn btn-sm ml-auto"
                    style="background:linear-gradient(135deg,#10b981,#059669);color:#fff;border:none;border-radius:10px;padding:8px 18px;font-weight:600;font-size:.82rem;box-shadow:0 4px 14px rgba(16,185,129,.3);">
                    <i class="fas fa-file-excel mr-1"></i> Export CSV
                </button>
            </div>

            <div class="card-body" style="padding:24px;background:#fafbff;">

                {{-- Stats --}}
                <div class="stat-row">
                    @foreach([
                        ['id'=>'statTotal',    'label'=>'Total',    'icon'=>'fa-list',         'bg'=>'#eef2ff','color'=>'#4f46e5'],
                        ['id'=>'statPending',  'label'=>'Pending',  'icon'=>'fa-clock',        'bg'=>'#fffbeb','color'=>'#f59e0b'],
                        ['id'=>'statApproved', 'label'=>'Approved', 'icon'=>'fa-check-circle', 'bg'=>'#ecfdf5','color'=>'#10b981'],
                        ['id'=>'statRejected', 'label'=>'Rejected', 'icon'=>'fa-times-circle', 'bg'=>'#fef2f2','color'=>'#ef4444'],
                    ] as $s)
                    <div class="stat-item">
                        <div style="background:#fff;border-radius:12px;padding:16px 20px;border:1px solid #f0f0f5;display:flex;align-items:center;gap:14px;">
                            <div style="width:42px;height:42px;border-radius:10px;background:{{ $s['bg'] }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="fas {{ $s['icon'] }}" style="color:{{ $s['color'] }};"></i>
                            </div>
                            <div>
                                <div class="font-weight-bold" id="{{ $s['id'] }}" style="font-size:1.4rem;color:#1a1f36;line-height:1;">0</div>
                                <div style="font-size:.72rem;color:#9ca3af;margin-top:2px;">{{ $s['label'] }}</div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Filters --}}
                <div class="d-flex flex-wrap mb-3" style="gap:10px;align-items:center;">
                    
                    {{-- Search --}}
                    <div style="position:relative;flex:1;min-width:200px;">
                        <i class="fas fa-search" style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:.8rem;pointer-events:none;"></i>
                        <input type="text" id="searchInput" class="form-control form-control-sm"
                            placeholder="Search employee name or ID..."
                            style="padding-left:32px;border-radius:10px;border:1.5px solid #e5e7eb;height:36px;">
                    </div>

                    {{-- Status --}}
                    <select id="filterStatus" class="form-control form-control-sm"
                        style="width:auto;min-width:130px;border-radius:10px;border:1.5px solid #e5e7eb;height:36px;">
                        <option value="">All Status</option>
                        <option value="Pending">Pending</option>
                        <option value="Approved">Approved</option>
                        <option value="Rejected">Rejected</option>
                    </select>

                    {{-- Date From --}}
                    <div style="position:relative;">
                        <i class="fas fa-calendar-alt" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:.8rem;pointer-events:none;z-index:1;"></i>
                        <input type="date" id="filterDateFrom" class="form-control form-control-sm"
                            placeholder="From"
                            style="padding-left:30px;width:155px;border-radius:10px;border:1.5px solid #e5e7eb;height:36px;">
                    </div>

                    {{-- Arrow --}}
                    <span style="color:#9ca3af;font-size:.8rem;">→</span>

                    {{-- Date To --}}
                    <div style="position:relative;">
                        <i class="fas fa-calendar-alt" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:.8rem;pointer-events:none;z-index:1;"></i>
                        <input type="date" id="filterDateTo" class="form-control form-control-sm"
                            placeholder="To"
                            style="padding-left:30px;width:155px;border-radius:10px;border:1.5px solid #e5e7eb;height:36px;">
                    </div>

                    {{-- Reset --}}
                    <button type="button" id="btnReset"
                        style="background:#f3f4f6;color:#6b7280;border:none;border-radius:10px;padding:0 16px;font-size:.82rem;font-weight:600;height:36px;white-space:nowrap;flex-shrink:0;">
                        <i class="fas fa-undo mr-1"></i> Reset
                    </button>

                </div>

                {{-- Table --}}
                <div style="background:#fff;border-radius:12px;border:1px solid #f0f0f5;overflow:hidden;">
                    <div style="overflow-x:auto;">
                        <table id="leaveTable" class="table table-hover w-100 mb-0" style="min-width:900px;">
                            <thead>
                                <tr style="background:#f8f9ff;">
                                    @foreach(['#','Employee','Leave Type','Period','Days','Reason','Status','Approved By','Actions'] as $th)
                                    <th style="padding:14px 16px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;white-space:nowrap;">{{ $th }}</th>
                                    @endforeach
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

{{-- Approve/Reject Modal --}}
<div class="modal fade" id="actionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border:none;border-radius:16px;overflow:hidden;">
            <div class="modal-header" style="border-bottom:1px solid #f0f0f5;padding:20px 24px;">
                <h5 class="modal-title font-weight-bold" id="actionModalTitle" style="color:#1a1f36;font-size:1rem;"></h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body" style="padding:24px;">
                <div id="actionModalBody"></div>
            </div>
            <div class="modal-footer" style="border-top:1px solid #f0f0f5;padding:16px 24px;">
                <button type="button" class="btn btn-sm" data-dismiss="modal"
                    style="background:#f3f4f6;color:#6b7280;border:none;border-radius:10px;padding:8px 20px;font-weight:600;">
                    Cancel
                </button>
                <button type="button" id="btnConfirmAction"
                    style="border:none;border-radius:10px;padding:8px 24px;font-weight:600;font-size:.88rem;color:#fff;">
                    Confirm
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Detail Modal --}}
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border:none;border-radius:16px;overflow:hidden;">
            <div class="modal-header" style="border-bottom:1px solid #f0f0f5;padding:20px 24px;">
                <h5 class="modal-title font-weight-bold" style="color:#1a1f36;font-size:1rem;">Leave Request Detail</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body" style="padding:24px;" id="detailModalBody">
                <div class="text-center py-4"><i class="fas fa-spinner fa-spin" style="color:#4f46e5;font-size:1.4rem;"></i></div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        #leaveTable tbody td { padding:13px 16px;vertical-align:middle;font-size:.875rem;color:#374151;border-color:#f3f4f6; }
        #leaveTable tbody tr:hover { background:#f5f6ff !important; }
        .badge-status { padding:5px 14px;border-radius:20px;font-size:.73rem;font-weight:700;display:inline-flex;align-items:center;gap:5px;white-space:nowrap; }
        .badge-pending  { background:#fffbeb;color:#d97706; }
        .badge-approved { background:#ecfdf5;color:#059669; }
        .badge-rejected { background:#fef2f2;color:#ef4444; }
        .emp-avatar { width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#4f46e5,#7c3aed);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:.72rem;font-weight:700;color:#fff;overflow:hidden; }
        .emp-avatar img { width:100%;height:100%;object-fit:cover;border-radius:50%; }
        .emp-id-badge { background:#eef2ff;color:#4f46e5;border-radius:6px;padding:2px 7px;font-size:.68rem;font-weight:700; }
        .row-num { width:26px;height:26px;border-radius:6px;background:#f3f4f6;color:#6b7280;font-size:.75rem;font-weight:700;display:inline-flex;align-items:center;justify-content:center; }
        .btn-action { border:none;border-radius:8px;padding:5px 12px;font-size:.75rem;font-weight:600;cursor:pointer;transition:all .15s; }
        .btn-approve { background:#ecfdf5;color:#059669; }
        .btn-approve:hover { background:#059669;color:#fff; }
        .btn-reject  { background:#fef2f2;color:#ef4444; }
        .btn-reject:hover  { background:#ef4444;color:#fff; }
        .btn-view    { background:#eef2ff;color:#4f46e5; }
        .btn-view:hover    { background:#4f46e5;color:#fff; }
        .btn-delete  { background:#f3f4f6;color:#6b7280; }
        .btn-delete:hover  { background:#ef4444;color:#fff; }
        .form-control:focus { border-color:#4f46e5 !important;box-shadow:0 0 0 3px rgba(79,70,229,.12) !important; }
        .dataTables_wrapper .dataTables_info { font-size:.78rem;color:#9ca3af;padding-top:14px; }
        .dataTables_wrapper .dataTables_paginate { padding-top:10px; }
        .dataTables_wrapper .dataTables_paginate .paginate_button { border-radius:8px!important;margin:0 2px;border:none!important;background:transparent!important;font-size:.82rem;color:#6b7280!important;padding:5px 11px!important; }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover { background:linear-gradient(135deg,#4f46e5,#7c3aed)!important;color:#fff!important;border:none!important; }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover { background:#eef2ff!important;color:#4f46e5!important; }
        /* Force stats row always horizontal */
        .stat-row { display:flex !important; gap:12px; flex-wrap:nowrap; overflow-x:auto; margin-bottom:20px; }
        .stat-row .stat-item { flex:1; min-width:120px; }

        /* Fix filter row on small screens */
        @media (max-width: 576px) {
            #filterDateFrom, #filterDateTo { width:130px !important; }
        }
    </style>
@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function () {

    toastr.options = { closeButton:true, progressBar:true, positionClass:'toast-top-right', timeOut:3500 };

    const DATA_URL    = '{{ route("leave-requests.data") }}';
    const BASE_URL    = '{{ url("leave-requests") }}';
    const STORAGE_URL = '{{ asset("storage") }}';
    const CSRF        = '{{ csrf_token() }}';

    /* ── Status badge ───────────────────────────────── */
    function statusBadge(s) {
        const map = {
            Pending:  ['badge-pending',  'fa-clock',        'Pending'],
            Approved: ['badge-approved', 'fa-check-circle', 'Approved'],
            Rejected: ['badge-rejected', 'fa-times-circle', 'Rejected'],
        };
        const [cls, icon, label] = map[s] || ['','fa-circle', s];
        return `<span class="badge-status ${cls}"><i class="fas ${icon}" style="font-size:.5rem;"></i>${label}</span>`;
    }

    /* ── Employee avatar ────────────────────────────── */
    function avatarHtml(emp) {
        if (!emp) return '—';
        const initials = ((emp.first_name?.[0]??'')+(emp.last_name?.[0]??'')).toUpperCase();
        let photoUrl = null;
        if (emp.photo) {
            const p = String(emp.photo).replace(/^\/+/,'');
            photoUrl = p.startsWith('http') ? p : `${STORAGE_URL}/${p}`;
        }
        const inner = photoUrl
            ? `<img src="${photoUrl}" alt="${initials}" onerror="this.style.display='none';this.nextSibling.style.display='flex';">
               <span style="display:none;width:100%;height:100%;align-items:center;justify-content:center;">${initials}</span>`
            : `<span>${initials}</span>`;
        return `<div style="display:flex;align-items:center;gap:10px;">
                    <div class="emp-avatar">${inner}</div>
                    <div>
                        <div style="font-weight:600;color:#1a1f36;font-size:.88rem;">${emp.first_name} ${emp.last_name}</div>
                        <span class="emp-id-badge">${emp.employee_code ?? '—'}</span>
                    </div>
                </div>`;
    }

    /* ── Custom filters ─────────────────────────────── */
    $.fn.dataTable.ext.search.push(function (settings, data, dataIndex, rowData) {
        const status = $('#filterStatus').val();
        const from   = $('#filterDateFrom').val();
        const to     = $('#filterDateTo').val();
        if (status && rowData.status !== status) return false;
        if (from && rowData.start_date < from) return false;
        if (to   && rowData.end_date   > to)   return false;
        return true;
    });

    /* ── DataTable ──────────────────────────────────── */
    const table = $('#leaveTable').DataTable({
        processing: true,
        serverSide: false,
        dom: 't<"d-flex align-items-center justify-content-between px-1 pt-2"ip>',
        ajax: {
            url: DATA_URL,
            dataSrc: function (json) {
                const data = json.data || [];
                $('#statTotal').text(data.length);
                $('#statPending').text(data.filter(r=>r.status==='Pending').length);
                $('#statApproved').text(data.filter(r=>r.status==='Approved').length);
                $('#statRejected').text(data.filter(r=>r.status==='Rejected').length);
                return data;
            },
            error: () => toastr.error('Failed to load leave requests.')
        },
        language: {
            processing: '<i class="fas fa-spinner fa-spin mr-2" style="color:#4f46e5;"></i><span style="color:#4f46e5;">Loading...</span>',
            emptyTable: '<div style="padding:40px 0;color:#9ca3af;text-align:center;"><i class="fas fa-inbox" style="font-size:2rem;display:block;margin-bottom:10px;opacity:.4;"></i>No leave requests found.</div>',
            info: 'Showing _START_–_END_ of <strong>_TOTAL_</strong> requests',
            paginate: { previous:'<i class="fas fa-chevron-left"></i>', next:'<i class="fas fa-chevron-right"></i>' }
        },
        order: [[0,'desc']],
        columns: [
            {
                data: null, orderable:false, searchable:false,
                render: (d,t,r,m) => `<span class="row-num">${m.row+m.settings._iDisplayStart+1}</span>`
            },
            {
                data: 'employee',
                render: emp => avatarHtml(emp),
                createdCell: function(td, cellData, rowData) {
                    if (rowData.employee) {
                        $(td).attr('data-search',
                            `${rowData.employee.first_name} ${rowData.employee.last_name} ${rowData.employee.employee_id}`
                        );
                    }
                }
            },
            {
                data: 'leave_type',
                render: lt => lt
                    ? `<span style="background:#eef2ff;color:#4f46e5;border-radius:8px;padding:4px 10px;font-size:.78rem;font-weight:600;">${lt.leave_type_name}</span>`
                    : '—'
            },
            {
                data: 'start_date',
                render: (d, t, r) => {
                    const fmt = date => {
                        if (!date) return '—';
                        // Handle both ISO and Y-m-d formats safely
                        const d = new Date(date + 'T00:00:00');
                        return d.toLocaleDateString('en-GB', { day:'2-digit', month:'short', year:'numeric' });
                    };
                    return `<div style="font-size:.83rem;color:#374151;">
                        <div><i class="fas fa-calendar-alt mr-1" style="color:#4f46e5;"></i>${fmt(r.start_date)}</div>
                        <div style="color:#9ca3af;font-size:.75rem;">to ${fmt(r.end_date)}</div>
                    </div>`;
                }
            },
            {
                data: 'total_days',
                render: d => `<span style="font-weight:700;color:#1a1f36;">${d}</span><span style="color:#9ca3af;font-size:.75rem;"> day(s)</span>`
            },
            {
                data: 'reason',
                render: d => d
                    ? `<span style="color:#6b7280;font-size:.82rem;" title="${d}">${d.length>40?d.substring(0,40)+'…':d}</span>`
                    : '<span style="color:#d1d5db;">—</span>'
            },
            {
                data: 'status',
                render: s => statusBadge(s)
            },
            {
                data: 'approved_by',
                render: ab => ab
                    ? `<span style="font-size:.82rem;color:#374151;">${ab.name}</span>`
                    : '<span style="color:#d1d5db;">—</span>'
            },
            {
                data: null, orderable:false, searchable:false,
                render: (d, t, r) => {
                    let btns = `<button class="btn-action btn-view mr-1" onclick="viewDetail(${r.leave_request_id})">
                                    <i class="fas fa-eye"></i>
                                </button>`;
                    if (r.status === 'Pending') {
                        btns += `<button class="btn-action btn-approve mr-1" onclick="doAction(${r.leave_request_id},'approve')">
                                    <i class="fas fa-check"></i>
                                 </button>
                                 <button class="btn-action btn-reject mr-1" onclick="doAction(${r.leave_request_id},'reject')">
                                    <i class="fas fa-times"></i>
                                 </button>`;
                    }
                    btns += `<button class="btn-action btn-delete" onclick="doDelete(${r.leave_request_id})">
                                <i class="fas fa-trash"></i>
                             </button>`;
                    return `<div style="display:flex;align-items:center;gap:4px;">${btns}</div>`;
                }
            },
        ]
    });

    /* ── Search ─────────────────────────────────────── */
    $('#searchInput').on('keyup', function () {
        $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
            const q = $('#searchInput').val().toLowerCase().trim();
            if (!q) return true;
            const row  = table.row(dataIndex).node();
            const cell = $(row).find('td:nth-child(2)');
            return (cell.attr('data-search')||cell.text()).toLowerCase().includes(q);
        });
        table.draw();
        $.fn.dataTable.ext.search.pop();
    });

    $('#filterStatus, #filterDateFrom, #filterDateTo').on('change', () => table.draw());

    $('#btnReset').on('click', function () {
        $('#searchInput').val('');
        $('#filterStatus').val('');
        $('#filterDateFrom, #filterDateTo').val('');
        table.search('').draw();
    });

    /* ── Approve / Reject ───────────────────────────── */
    let pendingAction = null;

    window.doAction = function (id, action) {
        pendingAction = { id, action };
        const isApprove = action === 'approve';
        $('#actionModalTitle').text(isApprove ? 'Approve Leave Request' : 'Reject Leave Request');
        $('#actionModalBody').html(`
            <div style="text-align:center;padding:10px 0;">
                <div style="width:56px;height:56px;border-radius:50%;background:${isApprove?'#ecfdf5':'#fef2f2'};display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <i class="fas ${isApprove?'fa-check-circle':'fa-times-circle'}" style="font-size:1.6rem;color:${isApprove?'#059669':'#ef4444'};"></i>
                </div>
                <p style="color:#374151;margin:0;">Are you sure you want to <strong>${action}</strong> this leave request?</p>
                ${isApprove ? '<p style="color:#6b7280;font-size:.83rem;margin-top:8px;">This will mark attendance as <strong>Leave</strong> for the requested days.</p>' : ''}
            </div>`);
        $('#btnConfirmAction')
            .css('background', isApprove ? 'linear-gradient(135deg,#059669,#047857)' : 'linear-gradient(135deg,#ef4444,#dc2626)')
            .text(isApprove ? 'Yes, Approve' : 'Yes, Reject');
        $('#actionModal').modal('show');
    };

    $('#btnConfirmAction').on('click', function () {
        if (!pendingAction) return;
        const { id, action } = pendingAction;
        $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Processing...');

        $.ajax({
            url: `${BASE_URL}/${id}/${action}`,
            method: 'POST',
            data: { _token: CSRF }
        })
        .done(res => {
            if (res.success) {
                toastr.success(res.message);
                $('#actionModal').modal('hide');
                table.ajax.reload();
            } else {
                toastr.warning(res.message);
            }
        })
        .fail(xhr => toastr.error(xhr.responseJSON?.message ?? 'Action failed.'))
        .always(() => {
            $('#btnConfirmAction').prop('disabled', false).text('Confirm');
            pendingAction = null;
        });
    });

    /* ── Delete ─────────────────────────────────────── */
    window.doDelete = function (id) {
        pendingAction = { id, action: 'delete' };
        $('#actionModalTitle').text('Delete Leave Request');
        $('#actionModalBody').html(`
            <div style="text-align:center;padding:10px 0;">
                <div style="width:56px;height:56px;border-radius:50%;background:#fef2f2;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <i class="fas fa-trash" style="font-size:1.4rem;color:#ef4444;"></i>
                </div>
                <p style="color:#374151;margin:0;">Are you sure you want to <strong>delete</strong> this leave request?</p>
                <p style="color:#6b7280;font-size:.83rem;margin-top:8px;">If approved, attendance records will be reverted to Absent.</p>
            </div>`);
        $('#btnConfirmAction').css('background','linear-gradient(135deg,#ef4444,#dc2626)').text('Yes, Delete');
        $('#actionModal').modal('show');
    };

    // Override confirm for delete
    $('#actionModal').on('hide.bs.modal', () => { pendingAction = null; });

    $('#btnConfirmAction').off('click').on('click', function () {
        if (!pendingAction) return;
        const { id, action } = pendingAction;
        $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Processing...');

        let ajaxOpts;
        if (action === 'delete') {
            ajaxOpts = { url:`${BASE_URL}/${id}`, method:'POST', data:{ _token:CSRF, _method:'DELETE' } };
        } else {
            ajaxOpts = { url:`${BASE_URL}/${id}/${action}`, method:'POST', data:{ _token:CSRF } };
        }

        $.ajax(ajaxOpts)
        .done(res => {
            if (res.success) {
                toastr.success(res.message);
                $('#actionModal').modal('hide');
                table.ajax.reload();
            } else {
                toastr.warning(res.message);
            }
        })
        .fail(xhr => toastr.error(xhr.responseJSON?.message ?? 'Action failed.'))
        .always(() => {
            $(this).prop('disabled', false).text('Confirm');
            pendingAction = null;
        });
    });

    /* ── View Detail ────────────────────────────────── */
    window.viewDetail = function (id) {
        $('#detailModalBody').html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin" style="color:#4f46e5;font-size:1.4rem;"></i></div>');
        $('#detailModal').modal('show');

        $.get(`${BASE_URL}/${id}`)
        .done(res => {
            if (!res.success) { $('#detailModalBody').html('<p class="text-danger">Failed to load.</p>'); return; }
            const r = res.data;
            const emp = r.employee;
            const lt  = r.leave_type;
            $('#detailModalBody').html(`
                <div class="row">
                    <div class="col-md-6">
                        <div style="font-size:.72rem;color:#9ca3af;text-transform:uppercase;letter-spacing:.6px;margin-bottom:4px;">Employee</div>
                        <div style="font-weight:600;color:#1a1f36;">${emp ? emp.first_name+' '+emp.last_name : '—'}</div>
                        <div style="font-size:.8rem;color:#9ca3af;">${emp?.employee_code ?? '—'}</div>
                    </div>
                    <div class="col-md-6">
                        <div style="font-size:.72rem;color:#9ca3af;text-transform:uppercase;letter-spacing:.6px;margin-bottom:4px;">Leave Type</div>
                        <div style="font-weight:600;color:#1a1f36;">${lt?.leave_type_name ?? '—'}</div>
                        <div style="font-size:.8rem;color:#9ca3af;">${lt?.days_allowed ?? '—'} days/year</div>
                    </div>
                </div>
                <hr style="border-color:#f0f0f5;margin:16px 0;">
                <div class="row">
                    <div class="col-md-4">
                        <div style="font-size:.72rem;color:#9ca3af;text-transform:uppercase;letter-spacing:.6px;margin-bottom:4px;">Start Date</div>
                        <div style="font-weight:600;color:#1a1f36;">${r.start_date}</div>
                    </div>
                    <div class="col-md-4">
                        <div style="font-size:.72rem;color:#9ca3af;text-transform:uppercase;letter-spacing:.6px;margin-bottom:4px;">End Date</div>
                        <div style="font-weight:600;color:#1a1f36;">${r.end_date}</div>
                    </div>
                    <div class="col-md-4">
                        <div style="font-size:.72rem;color:#9ca3af;text-transform:uppercase;letter-spacing:.6px;margin-bottom:4px;">Total Days</div>
                        <div style="font-weight:700;color:#4f46e5;font-size:1.1rem;">${r.total_days}</div>
                    </div>
                </div>
                <hr style="border-color:#f0f0f5;margin:16px 0;">
                <div class="mb-3">
                    <div style="font-size:.72rem;color:#9ca3af;text-transform:uppercase;letter-spacing:.6px;margin-bottom:6px;">Reason</div>
                    <div style="background:#f8f9ff;border-radius:10px;padding:12px 16px;color:#374151;font-size:.88rem;">${r.reason || '<span style="color:#d1d5db;">No reason provided.</span>'}</div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div style="font-size:.72rem;color:#9ca3af;text-transform:uppercase;letter-spacing:.6px;margin-bottom:4px;">Status</div>
                        ${statusBadge(r.status)}
                    </div>
                    <div class="col-md-4">
                        <div style="font-size:.72rem;color:#9ca3af;text-transform:uppercase;letter-spacing:.6px;margin-bottom:4px;">Approved By</div>
                        <div style="font-weight:600;color:#1a1f36;font-size:.88rem;">${r.approved_by?.name ?? '—'}</div>
                    </div>
                    <div class="col-md-4">
                        <div style="font-size:.72rem;color:#9ca3af;text-transform:uppercase;letter-spacing:.6px;margin-bottom:4px;">Approval Date</div>
                        <div style="font-weight:600;color:#1a1f36;font-size:.88rem;">${r.approval_date ?? '—'}</div>
                    </div>
                </div>`);
        })
        .fail(() => $('#detailModalBody').html('<p class="text-danger text-center">Failed to load details.</p>'));
    };

    /* ── Export CSV ─────────────────────────────────── */
    $('#btnExport').on('click', function () {
        const rows = table.rows({ search:'applied' }).data().toArray();
        if (!rows.length) { toastr.warning('No data to export.'); return; }
        const headers = ['#','Employee ID','Employee Name','Leave Type','Start Date','End Date','Days','Status','Approved By','Reason'];
        const csv = [
            headers.join(','),
            ...rows.map((r,i) => [
                i+1,
                r.employee?.employee_id ?? '',
                `"${r.employee ? r.employee.first_name+' '+r.employee.last_name : ''}"`,
                `"${r.leave_type?.leave_type_name ?? ''}"`,
                r.start_date, r.end_date, r.total_days, r.status,
                `"${r.approved_by?.name ?? ''}"`,
                `"${(r.reason||'').replace(/"/g,"''")}"`,
            ].join(','))
        ].join('\n');
        const a = document.createElement('a');
        a.href = URL.createObjectURL(new Blob([csv],{type:'text/csv'}));
        a.download = `leave_requests_${new Date().toISOString().slice(0,10)}.csv`;
        a.click();
        toastr.success('Exported successfully.');
    });

});
</script>
@stop