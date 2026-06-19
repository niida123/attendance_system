{{-- resources/views/leave_requests/my_leave.blade.php --}}
@extends('adminlte::page')

@section('title', 'My Leave')

@section('content_header')
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h1 class="m-0 font-weight-bold" style="color:#1a1f36;font-size:1.4rem;letter-spacing:-0.3px;">
                <i class="fas fa-calendar-check mr-2" style="color:#4f46e5;"></i> My Leave
            </h1>
            <ol class="breadcrumb mt-1 mb-0" style="background:transparent;padding:0;font-size:.8rem;">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" style="color:#4f46e5;">Home</a></li>
                <li class="breadcrumb-item active" style="color:#6b7280;">My Leave</li>
            </ol>
        </div>
        <button type="button" id="btnApplyLeave"
            style="background:linear-gradient(135deg,#4f46e5,#7c3aed);color:#fff;border:none;border-radius:12px;padding:10px 22px;font-weight:700;font-size:.88rem;box-shadow:0 4px 14px rgba(79,70,229,.35);">
            <i class="fas fa-plus mr-2"></i> Apply Leave
        </button>
    </div>
@stop

@section('content')
<div class="row">
    <div class="col-12">

        {{-- Leave Balance Cards --}}
        <div id="balanceCards" class="row mb-4">
            <div class="col-12">
                <div style="background:#fff;border-radius:16px;border:1px solid #f0f0f5;padding:20px 24px;">
                    <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#9ca3af;margin-bottom:16px;">
                        <i class="fas fa-chart-pie mr-1"></i> Leave Balance — {{ date('Y') }}
                    </div>
                    <div id="balanceInner" class="row">
                        <div class="col-12 text-center py-3">
                            <i class="fas fa-spinner fa-spin" style="color:#4f46e5;font-size:1.2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Leave Requests Table --}}
        <div class="card" style="border:none;border-radius:16px;box-shadow:0 1px 3px rgba(0,0,0,.08),0 8px 32px rgba(79,70,229,.07);overflow:hidden;">

            <div class="card-header d-flex align-items-center justify-content-between"
                style="background:#fff;border-bottom:1px solid #f0f0f5;padding:18px 24px;">
                <div class="d-flex align-items-center">
                    <div style="width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,#4f46e5,#7c3aed);display:flex;align-items:center;justify-content:center;margin-right:12px;">
                        <i class="fas fa-list" style="color:#fff;font-size:.85rem;"></i>
                    </div>
                    <div>
                        <h3 class="mb-0 font-weight-bold" style="font-size:1rem;color:#1a1f36;">My Requests</h3>
                        <small style="color:#9ca3af;font-size:.75rem;">Your leave request history</small>
                    </div>
                </div>
                <div style="display:flex;gap:10px;align-items:center;">
                    <select id="filterStatus" class="form-control form-control-sm" style="min-width:130px;border-radius:10px;border:1.5px solid #e5e7eb;">
                        <option value="">All Status</option>
                        <option value="Pending">Pending</option>
                        <option value="Approved">Approved</option>
                        <option value="Rejected">Rejected</option>
                    </select>
                </div>
            </div>

            <div class="card-body" style="padding:24px;background:#fafbff;">
                <div style="background:#fff;border-radius:12px;border:1px solid #f0f0f5;overflow:hidden;">
                    <div style="overflow-x:auto;">
                        <table id="myLeaveTable" class="table table-hover w-100 mb-0" style="min-width:750px;">
                            <thead>
                                <tr style="background:#f8f9ff;">
                                    @foreach(['#','Leave Type','Period','Days','Reason','Status','Approved By','Actions'] as $th)
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

{{-- Apply Leave Modal --}}
<div class="modal fade" id="applyModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border:none;border-radius:16px;overflow:hidden;">
            <div class="modal-header" style="border-bottom:1px solid #f0f0f5;padding:20px 24px;">
                <h5 class="modal-title font-weight-bold" style="color:#1a1f36;font-size:1rem;">
                    <i class="fas fa-calendar-plus mr-2" style="color:#4f46e5;"></i> Apply for Leave
                </h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body" style="padding:24px;">

                {{-- Working days preview --}}
                <div id="daysPreview" style="display:none;background:#eef2ff;border-radius:10px;padding:12px 16px;margin-bottom:20px;text-align:center;">
                    <span style="font-size:.82rem;color:#6b7280;">Working days requested: </span>
                    <span id="daysCount" style="font-weight:700;color:#4f46e5;font-size:1.1rem;">0</span>
                </div>

                <div class="form-group">
                    <label style="font-size:.8rem;font-weight:600;color:#374151;">Leave Type <span class="text-danger">*</span></label>
                    <select id="leaveTypeId" class="form-control" style="border-radius:10px;border:1.5px solid #e5e7eb;">
                        <option value="">— Select leave type —</option>
                    </select>
                    <small id="balanceHint" style="color:#9ca3af;font-size:.75rem;"></small>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label style="font-size:.8rem;font-weight:600;color:#374151;">Start Date <span class="text-danger">*</span></label>
                            <input type="date" id="startDate" class="form-control" style="border-radius:10px;border:1.5px solid #e5e7eb;">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label style="font-size:.8rem;font-weight:600;color:#374151;">End Date <span class="text-danger">*</span></label>
                            <input type="date" id="endDate" class="form-control" style="border-radius:10px;border:1.5px solid #e5e7eb;">
                        </div>
                    </div>
                </div>

                <div class="form-group mb-0">
                    <label style="font-size:.8rem;font-weight:600;color:#374151;">Reason</label>
                    <textarea id="reason" rows="3" class="form-control" placeholder="Optional reason..."
                        style="border-radius:10px;border:1.5px solid #e5e7eb;resize:none;"></textarea>
                </div>

            </div>
            <div class="modal-footer" style="border-top:1px solid #f0f0f5;padding:16px 24px;">
                <button type="button" class="btn btn-sm" data-dismiss="modal"
                    style="background:#f3f4f6;color:#6b7280;border:none;border-radius:10px;padding:8px 20px;font-weight:600;">
                    Cancel
                </button>
                <button type="button" id="btnSubmitLeave"
                    style="background:linear-gradient(135deg,#4f46e5,#7c3aed);color:#fff;border:none;border-radius:10px;padding:8px 24px;font-weight:700;font-size:.88rem;box-shadow:0 4px 14px rgba(79,70,229,.3);">
                    <i class="fas fa-paper-plane mr-1"></i> Submit Request
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Cancel Confirm Modal --}}
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border:none;border-radius:16px;overflow:hidden;">
            <div class="modal-body" style="padding:28px 24px;text-align:center;">
                <div style="width:56px;height:56px;border-radius:50%;background:#fef2f2;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <i class="fas fa-ban" style="font-size:1.4rem;color:#ef4444;"></i>
                </div>
                <h6 class="font-weight-bold mb-2" style="color:#1a1f36;">Cancel Request?</h6>
                <p style="color:#6b7280;font-size:.85rem;margin-bottom:20px;">This action cannot be undone.</p>
                <div class="d-flex justify-content-center" style="gap:10px;">
                    <button type="button" data-dismiss="modal"
                        style="background:#f3f4f6;color:#6b7280;border:none;border-radius:10px;padding:8px 20px;font-weight:600;font-size:.85rem;">
                        No, Keep
                    </button>
                    <button type="button" id="btnConfirmCancel"
                        style="background:linear-gradient(135deg,#ef4444,#dc2626);color:#fff;border:none;border-radius:10px;padding:8px 20px;font-weight:600;font-size:.85rem;">
                        Yes, Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        #myLeaveTable tbody td { padding:13px 16px;vertical-align:middle;font-size:.875rem;color:#374151;border-color:#f3f4f6; }
        #myLeaveTable tbody tr:hover { background:#f5f6ff !important; }
        .badge-status { padding:5px 14px;border-radius:20px;font-size:.73rem;font-weight:700;display:inline-flex;align-items:center;gap:5px;white-space:nowrap; }
        .badge-pending  { background:#fffbeb;color:#d97706; }
        .badge-approved { background:#ecfdf5;color:#059669; }
        .badge-rejected { background:#fef2f2;color:#ef4444; }
        .btn-action { border:none;border-radius:8px;padding:5px 12px;font-size:.75rem;font-weight:600;cursor:pointer;transition:all .15s; }
        .btn-cancel-req { background:#fef2f2;color:#ef4444; }
        .btn-cancel-req:hover { background:#ef4444;color:#fff; }
        .balance-bar { height:6px;border-radius:4px;background:#f0f0f5;overflow:hidden;margin-top:6px; }
        .balance-bar-fill { height:100%;border-radius:4px;transition:width .6s ease; }
        .form-control:focus { border-color:#4f46e5 !important;box-shadow:0 0 0 3px rgba(79,70,229,.12) !important; }
        .dataTables_wrapper .dataTables_info { font-size:.78rem;color:#9ca3af;padding-top:14px; }
        .dataTables_wrapper .dataTables_paginate { padding-top:10px; }
        .dataTables_wrapper .dataTables_paginate .paginate_button { border-radius:8px!important;margin:0 2px;border:none!important;background:transparent!important;font-size:.82rem;color:#6b7280!important;padding:5px 11px!important; }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover { background:linear-gradient(135deg,#4f46e5,#7c3aed)!important;color:#fff!important;border:none!important; }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover { background:#eef2ff!important;color:#4f46e5!important; }
    </style>
@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function () {

    toastr.options = { closeButton:true, progressBar:true, positionClass:'toast-top-right', timeOut:3500 };

    const MY_DATA_URL  = '{{ route("leave-requests.my-data") }}';
    const BALANCE_URL  = '{{ route("leave-requests.my-balance") }}';
    const BASE_URL     = '{{ url("leave-requests") }}';
    const CSRF         = '{{ csrf_token() }}';

    let balanceData = [];

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

    /* ── Load balance cards ─────────────────────────── */
    function loadBalance() {
        $.get(BALANCE_URL).done(res => {
            if (!res.success) return;
            balanceData = res.data;

            // Populate dropdown
            $('#leaveTypeId').html('<option value="">— Select leave type —</option>');
            balanceData.forEach(b => {
                $('#leaveTypeId').append(
                    `<option value="${b.leave_type_id}"
                        data-remaining="${b.days_remaining}"
                        data-allowed="${b.max_days_per_year}">
                        ${b.leave_type_name} (${b.days_remaining} days left)
                    </option>`
                );
            });

            // Build balance cards
            let html = '';
            balanceData.forEach(b => {
                const allowed   = b.max_days_per_year;   // ← correct key
                const remaining = b.days_remaining;
                const used      = b.days_used;
                const pending   = b.days_pending;
                const pct       = allowed > 0 ? Math.round((used / allowed) * 100) : 0;
                const color     = pct >= 90 ? '#ef4444' : pct >= 60 ? '#f59e0b' : '#4f46e5';

                html += `
                <div class="col-6 col-md-3 mb-3">
                    <div style="background:#f8f9ff;border-radius:12px;padding:14px 16px;border:1px solid #eef0f8;">
                        <div style="font-weight:600;color:#1a1f36;font-size:.88rem;margin-bottom:8px;">${b.leave_type_name}</div>
                        <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:4px;">
                            <span style="font-size:1.3rem;font-weight:800;color:${color};">${remaining}</span>
                            <span style="font-size:.75rem;color:#9ca3af;">/ ${allowed} days</span>
                        </div>
                        <div class="balance-bar">
                            <div class="balance-bar-fill" style="width:${pct}%;background:${color};"></div>
                        </div>
                        <div style="display:flex;justify-content:space-between;margin-top:6px;font-size:.7rem;color:#9ca3af;">
                            <span>Used: ${used}</span>
                            <span style="color:#f59e0b;">Pending: ${pending}</span>
                        </div>
                    </div>
                </div>`;
            });

            $('#balanceInner').html(html || '<div class="col-12 text-center" style="color:#9ca3af;">No leave types configured.</div>');
        });
    }

    /* ── Custom filter ──────────────────────────────── */
    $.fn.dataTable.ext.search.push(function (settings, data, dataIndex, rowData) {
        const status = $('#filterStatus').val();
        if (status && rowData.status !== status) return false;
        return true;
    });

    /* ── DataTable ──────────────────────────────────── */
    const table = $('#myLeaveTable').DataTable({
        processing: true,
        serverSide: false,
        dom: 't<"d-flex align-items-center justify-content-between px-1 pt-2"ip>',
        ajax: {
            url: MY_DATA_URL,
            dataSrc: d => d.data || [],
            error: () => toastr.error('Failed to load leave requests.')
        },
        language: {
            processing: '<i class="fas fa-spinner fa-spin mr-2" style="color:#4f46e5;"></i><span style="color:#4f46e5;">Loading...</span>',
            emptyTable: '<div style="padding:40px 0;color:#9ca3af;text-align:center;"><i class="fas fa-calendar-times" style="font-size:2rem;display:block;margin-bottom:10px;opacity:.4;"></i>No leave requests yet.</div>',
            info: 'Showing _START_–_END_ of <strong>_TOTAL_</strong> requests',
            paginate: { previous:'<i class="fas fa-chevron-left"></i>', next:'<i class="fas fa-chevron-right"></i>' }
        },
        order: [[0,'desc']],
        columns: [
            {
                data: null, orderable:false, searchable:false,
                render: (d,t,r,m) => `<span style="width:26px;height:26px;border-radius:6px;background:#f3f4f6;color:#6b7280;font-size:.75rem;font-weight:700;display:inline-flex;align-items:center;justify-content:center;">${m.row+m.settings._iDisplayStart+1}</span>`
            },
            {
                data: 'leave_type',
                render: lt => lt
                    ? `<span style="background:#eef2ff;color:#4f46e5;border-radius:8px;padding:4px 10px;font-size:.78rem;font-weight:600;">${lt.leave_type_name}</span>`
                    : '—'
            },
            {
                data: 'start_date',
                render: (d, t, r) => `<div style="font-size:.83rem;">
                    <div style="color:#374151;font-weight:600;">${r.start_date}</div>
                    <div style="color:#9ca3af;font-size:.75rem;">to ${r.end_date}</div>
                </div>`
            },
            {
                data: 'total_days',
                render: d => `<span style="font-weight:700;color:#4f46e5;">${d}</span><span style="color:#9ca3af;font-size:.75rem;"> day(s)</span>`
            },
            {
                data: 'reason',
                render: d => d
                    ? `<span style="color:#6b7280;font-size:.82rem;" title="${d}">${d.length>35?d.substring(0,35)+'…':d}</span>`
                    : '<span style="color:#d1d5db;">—</span>'
            },
            { data: 'status', render: s => statusBadge(s) },
            {
                data: 'approved_by',
                render: ab => ab
                    ? `<span style="font-size:.82rem;color:#374151;">${ab.name}</span>`
                    : '<span style="color:#d1d5db;">—</span>'
            },
            {
                data: null, orderable:false, searchable:false,
                render: (d, t, r) => r.status === 'Pending'
                    ? `<button class="btn-action btn-cancel-req" onclick="cancelRequest(${r.leave_request_id})">
                            <i class="fas fa-ban mr-1"></i> Cancel
                       </button>`
                    : '<span style="color:#d1d5db;font-size:.8rem;">—</span>'
            },
        ]
    });

    $('#filterStatus').on('change', () => table.draw());

    /* ── Apply Leave Modal ──────────────────────────── */
    $('#btnApplyLeave').on('click', function () {
        $('#leaveTypeId, #startDate, #endDate, #reason').val('');
        $('#daysPreview').hide();
        $('#balanceHint').text('');
        $('#applyModal').modal('show');
    });

    // Show remaining balance hint when type selected
    $('#leaveTypeId').on('change', function () {
        const remaining = $(this).find(':selected').data('remaining');
        const allowed   = $(this).find(':selected').data('allowed');
        if (remaining !== undefined) {
            $('#balanceHint').html(`<i class="fas fa-info-circle mr-1"></i> ${remaining} of ${allowed} days remaining this year.`);
        } else {
            $('#balanceHint').text('');
        }
        updateDaysPreview();
    });

    // Update preview when dates change
    $('#startDate, #endDate').on('change', updateDaysPreview);

    function updateDaysPreview() {
        const s = $('#startDate').val();
        const e = $('#endDate').val();
        if (!s || !e || e < s) { $('#daysPreview').hide(); return; }
        // Simple client-side estimate (weekdays only, no holiday check)
        let count = 0;
        const cur = new Date(s);
        const end = new Date(e);
        while (cur <= end) {
            const d = cur.getDay();
            if (d !== 0 && d !== 6) count++;
            cur.setDate(cur.getDate() + 1);
        }
        $('#daysCount').text(count);
        $('#daysPreview').show();
    }

    /* ── Submit Leave ───────────────────────────────── */
    $('#btnSubmitLeave').on('click', function () {
        const leaveTypeId = $('#leaveTypeId').val();
        const startDate   = $('#startDate').val();
        const endDate     = $('#endDate').val();
        const reason      = $('#reason').val();

        if (!leaveTypeId) { toastr.warning('Please select a leave type.'); return; }
        if (!startDate)   { toastr.warning('Please select a start date.'); return; }
        if (!endDate)     { toastr.warning('Please select an end date.'); return; }
        if (endDate < startDate) { toastr.warning('End date cannot be before start date.'); return; }

        const $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Submitting...');

        $.ajax({
            url: BASE_URL,
            method: 'POST',
            data: { _token:CSRF, leave_type_id:leaveTypeId, start_date:startDate, end_date:endDate, reason }
        })
        .done(res => {
            if (res.success) {
                toastr.success(res.message);
                $('#applyModal').modal('hide');
                table.ajax.reload();
                loadBalance();
            } else {
                toastr.warning(res.message);
            }
        })
        .fail(xhr => toastr.error(xhr.responseJSON?.message ?? 'Submission failed.'))
        .always(() => $btn.prop('disabled', false).html('<i class="fas fa-paper-plane mr-1"></i> Submit Request'));
    });

    /* ── Cancel Request ─────────────────────────────── */
    let cancelId = null;

    window.cancelRequest = function (id) {
        cancelId = id;
        $('#cancelModal').modal('show');
    };

    $('#btnConfirmCancel').on('click', function () {
        if (!cancelId) return;
        const $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>');

        $.ajax({
            url: `${BASE_URL}/${cancelId}/cancel`,
            method: 'POST',
            data: { _token: CSRF }
        })
        .done(res => {
            if (res.success) {
                toastr.success(res.message);
                $('#cancelModal').modal('hide');
                table.ajax.reload();
                loadBalance();
            } else {
                toastr.warning(res.message);
            }
        })
        .fail(xhr => toastr.error(xhr.responseJSON?.message ?? 'Cancel failed.'))
        .always(() => {
            $btn.prop('disabled', false).html('Yes, Cancel');
            cancelId = null;
        });
    });

    /* ── Init ───────────────────────────────────────── */
    loadBalance();

});
</script>
@stop