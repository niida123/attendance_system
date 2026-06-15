{{-- resources/views/leave_types/index.blade.php --}}
@extends('adminlte::page')

@section('title', 'Leave Types')

@section('content_header')
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h1 class="m-0 font-weight-bold" style="color:#1a1f36;font-size:1.4rem;letter-spacing:-0.3px;">
                <i class="fas fa-calendar-minus mr-2" style="color:#4f46e5;"></i> Leave Types
            </h1>
            <ol class="breadcrumb mt-1 mb-0" style="background:transparent;padding:0;font-size:.8rem;">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" style="color:#4f46e5;">Home</a></li>
                <li class="breadcrumb-item active" style="color:#6b7280;">Leave Types</li>
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
                                Leave Type List
                            </h3>
                            <small style="color:#9ca3af;font-size:.75rem;">
                                Manage all leave types
                            </small>
                        </div>
                    </div>
                    <!-- Right Side -->
                    <div class="d-flex align-items-center ml-auto mt-3" style="gap:10px; flex-wrap:wrap;">

                        <!-- Search Box -->
                        <div style="position: relative; width:250px; min-width:180px;">
                            <input type="text"
                                class="form-control form-control-sm"
                                placeholder="Search leave type or description..."
                                id="searchLeaveType"
                                style="padding-right:35px;">

                            <i class="fas fa-times"
                            id="clearSearch"
                            style="display:none;
                                    position:absolute;
                                    right:10px;
                                    top:50%;
                                    transform:translateY(-50%);
                                    cursor:pointer;
                                    color:#6b7280;">
                            </i>
                        </div>

                        <!-- Add Button -->
                        <button type="button"
                                class="btn btn-sm"
                                data-toggle="modal"
                                data-target="#leaveTypeModal"
                                id="btnCreate"
                                style="background:linear-gradient(135deg,#4f46e5,#7c3aed);color:#fff;border:none;border-radius:10px;padding:8px 18px;font-weight:600;font-size:.82rem;letter-spacing:.2px;box-shadow:0 4px 14px rgba(79,70,229,.35);">
                            <i class="fas fa-plus mr-1"></i> Add Leave Type
                        </button>

                    </div>

                </div>

                <div class="card-body" style="padding:24px;background:#fafbff;">

                    {{-- Stats Row --}}
                    <div class="row mb-4" id="statsRow">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div
                                style="background:#fff;border-radius:12px;padding:16px 20px;border:1px solid #f0f0f5;display:flex;align-items:center;gap:14px;">
                                <div
                                    style="width:42px;height:42px;border-radius:10px;background:#eef2ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas fa-calendar-minus" style="color:#4f46e5;"></i>
                                </div>
                                <div>
                                    <div class="font-weight-bold" id="statTotal"
                                        style="font-size:1.4rem;color:#1a1f36;line-height:1;">—</div>
                                    <div style="font-size:.75rem;color:#9ca3af;margin-top:2px;">Total Leave Types</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div
                                style="background:#fff;border-radius:12px;padding:16px 20px;border:1px solid #f0f0f5;display:flex;align-items:center;gap:14px;">
                                <div
                                    style="width:42px;height:42px;border-radius:10px;background:#ecfdf5;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas fa-check-circle" style="color:#10b981;"></i>
                                </div>
                                <div>
                                    <div class="font-weight-bold" id="statActive"
                                        style="font-size:1.4rem;color:#1a1f36;line-height:1;">—</div>
                                    <div style="font-size:.75rem;color:#9ca3af;margin-top:2px;">Active</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div
                                style="background:#fff;border-radius:12px;padding:16px 20px;border:1px solid #f0f0f5;display:flex;align-items:center;gap:14px;">
                                <div
                                    style="width:42px;height:42px;border-radius:10px;background:#f3f4f6;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas fa-pause-circle" style="color:#6b7280;"></i>
                                </div>
                                <div>
                                    <div class="font-weight-bold" id="statInactive"
                                        style="font-size:1.4rem;color:#1a1f36;line-height:1;">—</div>
                                    <div style="font-size:.75rem;color:#9ca3af;margin-top:2px;">Inactive</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Table --}}
                    <div style="background:#fff;border-radius:12px;border:1px solid #f0f0f5;overflow:hidden;">
                        <div style="overflow-x:auto;-webkit-overflow-scrolling:touch;">
                        <table id="leaveTypeTable" class="table table-hover w-100 mb-0" style="min-width:700px;">
                            <thead>
                                <tr style="background:#f8f9ff;">
                                    <th width="50"
                                        style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">
                                        #</th>
                                    <th
                                        style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">
                                        Leave Type Name</th>
                                    <th
                                        style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">
                                        Max Days Per Year</th>
                                    <th 
                                        style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">
                                        Description</th>
                                    <th width="140"
                                        style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">
                                        Status</th>
                                    <th width="140"
                                        style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">
                                        Created At</th>
                                    <th width="130" class="text-center"
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
     Modal: Add / Edit Leave Type
     ============================================================ --}}
    <div class="modal fade" id="leaveTypeModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content"
                style="border:none;border-radius:16px;overflow:hidden;box-shadow:0 25px 60px rgba(0,0,0,.15);">

                <div class="modal-header"
                    style="background:linear-gradient(135deg,#4f46e5,#7c3aed);padding:20px 24px;border:none;">
                    <div class="d-flex align-items-center">
                        <div
                            style="width:36px;height:36px;border-radius:9px;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;margin-right:12px;">
                            <i class="fas fa-calendar-alt" style="color:#fff;font-size:.9rem;"></i>
                        </div>
                        <h5 class="modal-title text-white mb-0 font-weight-bold" style="font-size:1rem;">
                            <span id="modalTitle">Add Leave Type</span>
                        </h5>
                    </div>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"
                        style="opacity:.8;font-size:1.3rem;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body" style="padding:28px 24px;background:#fff;">
                    <input type="hidden" id="leave_type_id">

                    <div class="form-group mb-4">
                        <label for="leave_name"
                            style="font-weight:600;font-size:.83rem;color:#374151;letter-spacing:.2px;margin-bottom:6px;">
                            LEAVE TYPE NAME <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="leave_name" class="form-control"
                            placeholder="e.g. Sick Leave"
                            style="border-radius:10px;border:1.5px solid #e5e7eb;padding:10px 14px;font-size:.9rem;transition:border-color .2s;">
                        <span class="text-danger small d-block mt-1" id="err_leave_name"></span>
                    </div>
                    {{-- max day per year --}}
                    <div class="form-group mb-4">
                        <label for="max_days_per_year"
                            style="font-weight:600;font-size:.83rem;color:#374151;letter-spacing:.2px;margin-bottom:6px;">
                            MAX DAYS PER YEAR <span class="text-danger">*</span>
                        </label>
                        <input type="number" id="max_days_per_year" class="form-control"
                            placeholder="e.g. 10"
                            style="border-radius:10px;border:1.5px solid #e5e7eb;padding:10px 14px;font-size:.9rem;transition:border-color .2s;">
                        <span class="text-danger small d-block mt-1" id="err_max_days_per_year"></span>
                    </div>

                    <div class="form-group mb-4">
                        <label for="description"
                            style="font-weight:600;font-size:.83rem;color:#374151;letter-spacing:.2px;margin-bottom:6px;">
                            DESCRIPTION
                        </label>
                        <textarea id="description" class="form-control" rows="3"
                            placeholder="Brief description of this leave type (optional)"
                            style="border-radius:10px;border:1.5px solid #e5e7eb;padding:10px 14px;font-size:.9rem;resize:none;transition:border-color .2s;"></textarea>
                        <span class="text-danger small d-block mt-1" id="err_description"></span>
                    </div>

                    <div class="form-group mb-0">
                        <label for="status"
                            style="font-weight:600;font-size:.83rem;color:#374151;letter-spacing:.2px;margin-bottom:6px;">
                            STATUS <span class="text-danger">*</span>
                        </label>
                        <select id="status" class="form-control"
                            style="border-radius:10px;border:1.5px solid #e5e7eb;padding:10px 14px;font-size:.9rem;transition:border-color .2s;">
                            <option value="">— Select Status —</option>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                        <span class="text-danger small d-block mt-1" id="err_status"></span>
                    </div>
                </div>

                <div class="modal-footer"
                    style="padding:16px 24px;background:#f9fafb;border-top:1px solid #f0f0f5;justify-content:space-between;">
                    <button type="button" class="btn btn-sm" data-dismiss="modal"
                        style="border-radius:9px;border:1.5px solid #e5e7eb;color:#6b7280;padding:8px 18px;font-weight:600;font-size:.83rem;background:#fff;">
                        <i class="fas fa-times mr-1"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-sm" id="btnSave"
                        style="background:linear-gradient(135deg,#4f46e5,#7c3aed);color:#fff;border:none;border-radius:9px;padding:8px 22px;font-weight:600;font-size:.83rem;box-shadow:0 4px 12px rgba(79,70,229,.3);">
                        <i class="fas fa-save mr-1"></i> Save
                    </button>
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
                    <p class="mb-1" style="color:#374151;font-size:.9rem;">Are you sure you want to delete</p>
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
        /* ── Table rows ── */
        #leaveTypeTable tbody tr {
            transition: background .15s;
        }

        #leaveTypeTable tbody tr:hover {
            background: #f5f6ff !important;
        }

        #leaveTypeTable tbody td {
            padding: 13px 20px;
            vertical-align: middle;
            font-size: .875rem;
            color: #374151;
            border-color: #f3f4f6;
        }

        /* ── Badges ── */
        .badge-active {
            background: #ecfdf5;
            color: #059669;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: .75rem;
            font-weight: 600;
            letter-spacing: .2px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            white-space: nowrap;
            line-height: 1;
        }

        .badge-inactive {
            background: #f3f4f6;
            color: #6b7280;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: .75rem;
            font-weight: 600;
            letter-spacing: .2px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            white-space: nowrap;
            line-height: 1;
        }

        /* ── Action buttons ── */
        .btn-edit-row {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: #eef2ff;
            color: #4f46e5;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all .2s;
            font-size: .8rem;
        }

        .btn-edit-row:hover {
            background: #4f46e5;
            color: #fff;
            box-shadow: 0 4px 12px rgba(79, 70, 229, .35);
            transform: translateY(-1px);
        }

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

        /* ── Form focus ── */
        .form-control:focus {
            border-color: #4f46e5 !important;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, .12) !important;
        }

        .form-control.is-invalid {
            border-color: #ef4444 !important;
        }

        /* ── Add Dept btn hover ── */
        #btnCreate:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(79, 70, 229, .45) !important;
        }

        /* ── Toastr overrides ── */
        #toast-container>.toast {
            border-radius: 12px !important;
            box-shadow: 0 8px 30px rgba(0, 0, 0, .12) !important;
        }

        /* ── Responsive ── */
        @media (max-width: 576px) {
            .card-body { padding: 16px !important; }
            #statsRow .col-md-4 { margin-bottom: 10px; }
            .modal-dialog { margin: 10px; }
            .modal-lg { max-width: calc(100% - 20px); }
        }

    </style>
@stop

{{-- ============================================================
     Scripts
     ============================================================ --}}
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="{{ asset('js/modal-nav.js') }}"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {

            /* ── Config ─────────────────────────────────────────────── */
            toastr.options = {
                closeButton: true,
                progressBar: true,
                positionClass: 'toast-top-right',
                timeOut: 3000
            };

            const BASE_URL = '{{ route('leave-types.index') }}';
            const DATA_URL = '{{ url('leave-types/data') }}';
            const CSRF = '{{ csrf_token() }}';
            let isEdit = false;
            let deleteId = null;

            /* ── DataTable ──────────────────────────────────────────── */
            const table = $('#leaveTypeTable').DataTable({
                processing: true,
                serverSide: false,
                dom: 't<"d-flex align-items-center justify-content-between px-1 pt-2"ip>',
                ajax: {
                    url: DATA_URL,
                    type: 'GET',
                    dataSrc: function(json) {
                        // Update stats after load
                        const data = json.data || [];
                        $('#statTotal').text(data.length);
                        $('#statActive').text(data.filter(r => r.status === 'Active').length);
                        $('#statInactive').text(data.filter(r => r.status !== 'Active').length);
                        return data;
                    },
                    error: function(xhr) {
                        console.error('DataTable AJAX error:', xhr.status, xhr.responseText);
                        toastr.error('Failed to load leave types.');
                    }
                },
                language: {
                    processing: '<i class="fas fa-spinner fa-spin mr-2" style="color:#4f46e5;"></i><span style="color:#4f46e5;">Loading...</span>',
                    emptyTable: '<div style="padding:40px 0;color:#9ca3af;"><i class="fas fa-inbox" style="font-size:2rem;display:block;margin-bottom:10px;opacity:.4;"></i>No leave types found.</div>',
                    info: 'Showing _START_ to _END_ of <strong>_TOTAL_</strong> leave types',
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
                        data: 'leave_name',
                        render: name =>
                            `<span style="font-weight:600;color:#1a1f36;">${name}</span>`
                    },
                    {
                        data: 'max_days_per_year',
                        render: d =>
                            `<span style="color:#6b7280;">${d} day${d > 1 ? 's' : ''}/year</span>`
                    },
                    {
                        data: 'description',
                        render: data =>
                            data ?
                            `<span style="color:#6b7280;">${data}</span>` :
                            '<span style="color:#d1d5db;">—</span>'
                    },
                    {
                        data: 'status',
                        className: 'text-center',
                        render: s => s === 'Active' ?
                            '<span class="badge-active"><i class="fas fa-circle" style="font-size:.5rem;"></i><span>Active</span></span>' :
                            '<span class="badge-inactive"><i class="fas fa-circle" style="font-size:.5rem;"></i><span>Inactive</span></span>'
                    },
                    {
                        data: 'created_at',
                        render: d =>
                            `<span style="color:#9ca3af;font-size:.82rem;">${new Date(d).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' })}</span>`
                    },
                    {
                        data: 'leave_type_id',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: (id, t, row) => `
                        <div class="d-flex align-items-center justify-content-center" style="gap:6px;">
                            <button type="button" class="btn-edit-row btn-edit"
                                    data-id="${id}" data-toggle="tooltip" title="Edit">
                                <i class="fas fa-pen"></i>
                            </button>
                            <button type="button" class="btn-delete-row btn-delete"
                                    data-id="${id}" data-name="${row.leave_name}"
                                    data-toggle="tooltip" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    `
                    }
                ]
            });

            // Custom Search
            $('#searchLeaveType').on('keyup', function () {
                let value = $(this).val();

                if (value.length > 0) {
                    $('#clearSearch').show();
                } else {
                    $('#clearSearch').hide();
                }

                table.search(value).draw();
            });

            $('#clearSearch').on('click', function () {
                $('#searchLeaveType').val('');
                $(this).hide();
                table.search('').draw();
            });

            /* ── Tooltips ───────────────────────────────────────────── */
            $(document).on('mouseenter', '[data-toggle="tooltip"]', function() {
                $(this).tooltip('show');
            });
            table.on('draw', function() {
                $('[data-toggle="tooltip"]').tooltip();
            });

            /* ── Modal: Add ─────────────────────────────────────────── */
            $('#leaveTypeModal').on('show.bs.modal', function(e) {
                if (e.relatedTarget && $(e.relatedTarget).is('#btnCreate')) {
                    isEdit = false;
                    resetForm();
                    $('#modalTitle').text('Add Leave Type');
                }
            });

            /* ── EDIT ───────────────────────────────────────────────── */
            $(document).on('click', '.btn-edit', function() {
                const id = $(this).data('id');
                isEdit = true;
                resetForm();
                $('#modalTitle').text('Edit Leave Type');
                $('#leaveTypeModal').modal('show');

                $('#btnSave').prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin mr-1"></i> Loading...'
                );

                $.get(BASE_URL + '/' + id)
                    .done(function(res) {
                        if (res.success) {
                            const d = res.data;
                            $('#leave_type_id').val(d.leave_type_id);
                            $('#leave_name').val(d.leave_name);
                            $('#max_days_per_year').val(d.max_days_per_year);
                            $('#description').val(d.description ?? '');
                            $('#status').val(d.status);
                        }
                    })
                    .fail(function() {
                        toastr.error('Failed to load leave type data.');
                        setTimeout(() => $('#leaveTypeModal').modal('hide'), 1500);
                    })
                    .always(function() {
                        $('#btnSave').prop('disabled', false).html(
                            '<i class="fas fa-save mr-1"></i> Save'
                        );
                    });
            });

            /* ── DELETE ─────────────────────────────────────────────── */
            $(document).on('click', '.btn-delete', function() {
                deleteId = $(this).data('id');
                $('#deleteName').text($(this).data('name'));
                $('#deleteModal').modal('show');
            });

            /* ── SAVE ───────────────────────────────────────────────── */
            $(document).on('click', '#btnSave', function() {
                const id = $('#leave_type_id').val();
                const payload = {
                    leave_name: $('#leave_name').val().trim(),
                    max_days_per_year: $('#max_days_per_year').val().trim(),
                    description: $('#description').val().trim(),
                    status: $('#status').val(),
                    _token: CSRF,
                };

                let url = BASE_URL;
                if (isEdit && id) {
                    url = BASE_URL + '/' + id;
                    payload._method = 'PUT';
                }

                const $btn = $(this);
                $btn.prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin mr-1"></i> Saving...'
                );

                $.ajax({
                        url,
                        method: 'POST',
                        data: payload
                    })
                    .done(function(res) {
                        if (res.success) {
                            $('#leaveTypeModal').modal('hide');
                            table.ajax.reload(null, false);
                            toastr.success(res.message);
                        }
                    })
                    .fail(function(xhr) {
                        if (xhr.status === 422) {
                            showErrors(xhr.responseJSON.errors);
                        } else {
                            toastr.error('Something went wrong. Please try again.');
                        }
                    })
                    .always(function() {
                        $btn.prop('disabled', false).html(
                            '<i class="fas fa-save mr-1"></i> Save'
                        );
                    });
            });

            /* ── CONFIRM DELETE ─────────────────────────────────────── */
            $(document).on('click', '#btnConfirmDelete', function() {
                if (!deleteId) return;

                const $btn = $(this);
                $btn.prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin mr-1"></i> Deleting...'
                );

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
                    .fail(function(xhr) {
                        $('#deleteModal').modal('hide');

                        if (xhr.status === 422 && xhr.responseJSON) {
                            toastr.error(xhr.responseJSON.message);
                        } else {
                            toastr.error('Something went wrong. Please try again.');
                        }
                    })
                    .always(function() {
                        deleteId = null;
                        $btn.prop('disabled', false).html(
                            '<i class="fas fa-trash mr-1"></i> Delete'
                        );
                    });
            });

            /* ── Modal close: reset ─────────────────────────────────── */
            $('#leaveTypeModal').on('hidden.bs.modal', resetForm);

            /* ── Helpers ────────────────────────────────────────────── */
            function resetForm() {
                $('#leave_type_id, #leave_name, #max_days_per_year').val('');
                $('#status').val('');
                clearErrors();
            }

            function clearErrors() {
                ['leave_name', 'max_days_per_year', 'status'].forEach(function(f) {
                    $('#' + f).removeClass('is-invalid');
                    $('#err_' + f).text('');
                });
            }

            function showErrors(errors) {
                clearErrors();
                $.each(errors, function(field, msgs) {
                    $('#' + field).addClass('is-invalid');
                    $('#err_' + field).text(msgs[0]);
                });
            }
        });


    
    </script>
@stop
