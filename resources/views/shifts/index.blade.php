{{-- resources/views/shifts/index.blade.php --}}
@extends('adminlte::page')

@section('title', 'Shifts')

@section('content_header')
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h1 class="m-0 font-weight-bold" style="color:#1a1f36;font-size:1.4rem;letter-spacing:-0.3px;">
                <i class="fas fa-clock mr-2" style="color:#4f46e5;"></i> Shifts
            </h1>
            <ol class="breadcrumb mt-1 mb-0" style="background:transparent;padding:0;font-size:.8rem;">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" style="color:#4f46e5;">Home</a></li>
                <li class="breadcrumb-item active" style="color:#6b7280;">Shifts</li>
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
                            <h3 class="mb-0 font-weight-bold" style="font-size:1rem;color:#1a1f36;">Shift List</h3>
                            <small style="color:#9ca3af;font-size:.75rem;">Manage shift schedules</small>
                        </div>
                    </div>

                    <div class="d-flex align-items-center ml-auto" style="gap:10px;flex-wrap:wrap;">
                        <button type="button" class="btn btn-sm" id="btnCreate"
                            style="background:linear-gradient(135deg,#4f46e5,#7c3aed);color:#fff;border:none;border-radius:10px;padding:8px 18px;font-weight:600;font-size:.82rem;">
                            <i class="fas fa-plus mr-1"></i> Add Shift
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
                                    <i class="fas fa-clock" style="color:#4f46e5;"></i>
                                </div>
                                <div>
                                    <div class="font-weight-bold" id="statTotal"
                                        style="font-size:1.4rem;color:#1a1f36;line-height:1;">—</div>
                                    <div style="font-size:.75rem;color:#9ca3af;margin-top:2px;">Total Shifts</div>
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

                    {{-- Filters --}}
                    <div class="row mt-3 mb-2">
                        <div class="col-12">
                            <div class="d-flex align-items-center justify-content-start" style="gap:10px;flex-wrap:wrap;">

                                {{-- Status Filter --}}
                                <div class="d-flex align-items-center" style="gap:6px;">
                                    <select id="filterStatus" class="form-control form-control-sm"
                                        style="height:36px;border-radius:10px;">
                                        <option value="">All Status</option>
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>
                                </div>

                                {{-- Reset --}}
                                <button id="resetFilters" class="btn btn-sm"
                                    style="border-radius:10px;border:1px solid #e5e7eb;color:#6b7280;padding:6px 14px;font-weight:600;font-size:.78rem;background:#fff;">
                                    <i class="fas fa-undo mr-1"></i> Reset
                                </button>

                            </div>
                        </div>
                    </div>

                    {{-- Table --}}
                    <div class="mt-3"
                        style="background:#fff;border-radius:12px;border:1px solid #f0f0f5;overflow:hidden;">
                        <div style="overflow-x:auto;-webkit-overflow-scrolling:touch;">
                            <table id="shiftTable" class="table table-hover w-100 mb-0" style="min-width:750px;">
                                <thead>
                                    <tr style="background:#f8f9ff;">
                                        <th width="50"
                                            style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">
                                            #</th>
                                        <th
                                            style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">
                                            Shift Name</th>
                                        <th
                                            style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">
                                            Start Time</th>
                                        <th
                                            style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">
                                            End Time</th>
                                        <th
                                            style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">
                                            Late After</th>
                                        <th
                                            style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">
                                            Work Hours</th>
                                        <th width="110"
                                            style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">
                                            Status</th>
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
         Modal: Add / Edit Shift
    ============================================================ --}}
    <div class="modal fade" id="shiftModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content"
                style="border:none;border-radius:16px;overflow:hidden;box-shadow:0 25px 60px rgba(0,0,0,.15);">

                <div class="modal-header"
                    style="background:linear-gradient(135deg,#4f46e5,#7c3aed);padding:20px 24px;border:none;">
                    <div class="d-flex align-items-center">
                        <div
                            style="width:36px;height:36px;border-radius:9px;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;margin-right:12px;">
                            <i class="fas fa-clock" style="color:#fff;font-size:.9rem;"></i>
                        </div>
                        <h5 class="modal-title text-white mb-0 font-weight-bold" style="font-size:1rem;">
                            <span id="modalTitle">Add Shift</span>
                        </h5>
                    </div>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"
                        style="opacity:.8;font-size:1.3rem;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body" style="padding:28px 24px;background:#fff;">
                    <input type="hidden" id="shift_id">

                    <div class="row">
                        {{-- Shift Name --}}
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label for="shift_name"
                                    style="font-weight:600;font-size:.83rem;color:#374151;letter-spacing:.2px;margin-bottom:6px;">
                                    SHIFT NAME <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="shift_name" class="form-control" placeholder="Morning Shift"
                                    style="border-radius:10px;border:1.5px solid #e5e7eb;padding:10px 14px;font-size:.9rem;">
                                <span class="text-danger small d-block mt-1" id="err_shift_name"></span>
                            </div>
                        </div>

                        {{-- Start Time --}}
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label for="start_time"
                                    style="font-weight:600;font-size:.83rem;color:#374151;letter-spacing:.2px;margin-bottom:6px;">
                                    START TIME <span class="text-danger">*</span>
                                </label>
                                <input type="time" id="start_time" class="form-control" placeholder="e.g. 09:00 AM"
                                    style="border-radius:10px;border:1.5px solid #e5e7eb;padding:10px 14px;font-size:.9rem;">
                                <span class="text-danger small d-block mt-1" id="err_start_time"></span>
                            </div>
                        </div>

                        {{-- End Time --}}
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label for="end_time"
                                    style="font-weight:600;font-size:.83rem;color:#374151;letter-spacing:.2px;margin-bottom:6px;">
                                    END TIME <span class="text-danger">*</span>
                                </label>
                                <input type="time" id="end_time" class="form-control" placeholder="e.g. 05:00 PM"
                                    style="border-radius:10px;border:1.5px solid #e5e7eb;padding:10px 14px;font-size:.9rem;">
                                <span class="text-danger small d-block mt-1" id="err_end_time"></span>
                            </div>
                        </div>

                        {{-- Late After --}}
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label for="late_after_minutes"
                                    style="font-weight:600;font-size:.83rem;color:#374151;letter-spacing:.2px;margin-bottom:6px;">
                                    LATE AFTER <span class="text-danger">*</span>
                                </label>
                                <input type="number" id="late_after_minutes" class="form-control"
                                    placeholder="e.g. 15 mins"
                                    style="border-radius:10px;border:1.5px solid #e5e7eb;padding:10px 14px;font-size:.9rem;"
                                    min="0">
                                <span class="text-danger small d-block mt-1" id="err_late_after_minutes"></span>
                            </div>
                        </div>

                        {{-- Work Hours --}}
                        <div class="col-md-6">
                            <div class="form-group
                                mb-4">
                                <label for="working_hours"
                                    style="font-weight:600;font-size:.83rem;color:#374151;letter-spacing:.2px;margin-bottom:6px;">
                                    WORK HOURS <span class="text-danger">*</span>
                                </label>
                                <input type="number" id="working_hours" class="form-control" placeholder="e.g. 8"
                                    style="border-radius:10px;border:1.5px solid #e5e7eb;padding:10px 14px;font-size:.9rem;"
                                    min="0" step="0.5">
                                <span class="text-danger small d-block mt-1" id="err_working_hours"></span>
                            </div>
                        </div>

                        {{-- Status --}}
                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label for="status"
                                    style="font-weight:600;font-size:.83rem;color:#374151;letter-spacing:.2px;margin-bottom:6px;">
                                    STATUS <span class="text-danger">*</span>
                                </label>
                                <select id="status" class="form-control"
                                    style="border-radius:10px;border:1.5px solid #e5e7eb;padding:10px 14px;font-size:.9rem;">
                                    <option value="">— Select Status —</option>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                                <span class="text-danger small d-block mt-1" id="err_status"></span>
                            </div>
                        </div>
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
                    <button type="button" class="close text-white" data-dismiss="modal"
                        style="opacity:.8;"><span>&times;</span></button>
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
        #resetFilters:hover {
            background: #f3f4f6 !important;
            color: #111827 !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, .08);
        }

        #filterRole:focus,
        #filterStatus:focus {
            border-color: #4f46e5 !important;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, .12) !important;
        }

        #shiftTable tbody tr {
            transition: background .15s;
        }

        #shiftTable tbody tr:hover {
            background: #f5f6ff !important;
        }

        #shiftTable tbody td {
            padding: 13px 20px;
            vertical-align: middle;
            font-size: .875rem;
            color: #374151;
            border-color: #f3f4f6;
        }

        .badge-active {
            background: #ecfdf5;
            color: #059669;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: .75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
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
            display: inline-flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
            line-height: 1;
        }

        .badge-shift {
            background: #eef2ff;
            color: #4f46e5;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: .73rem;
            font-weight: 600;
            display: inline-block;
            white-space: nowrap;
        }

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

        .dataTables_wrapper .dataTables_info {
            font-size: .78rem;
            color: #9ca3af;
            padding-top: 14px;
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

        .form-control:focus {
            border-color: #4f46e5 !important;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, .12) !important;
        }

        .form-control.is-invalid {
            border-color: #ef4444 !important;
        }

        #btnCreate:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(79, 70, 229, .45) !important;
        }

        #toast-container>.toast {
            border-radius: 12px !important;
            box-shadow: 0 8px 30px rgba(0, 0, 0, .12) !important;
        }

        @media (max-width:576px) {
            .card-body {
                padding: 16px !important;
            }

            #statsRow .col-md-4 {
                margin-bottom: 10px;
            }

            .modal-dialog {
                margin: 10px;
            }

            .modal-lg {
                max-width: calc(100% - 20px);
            }
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

            /* ── Config ───────────────────────────────────────────── */
            toastr.options = {
                closeButton: true,
                progressBar: true,
                positionClass: 'toast-top-right',
                timeOut: 3000
            };

            const BASE_URL = '{{ route('shifts.index') }}';
            const DATA_URL = '{{ route('shifts.data') }}';
            const CSRF = '{{ csrf_token() }}';
            let isEdit = false;
            let deleteId = null;

            // Convert 24-hour time to 12-hour format with AM/PM
            function formatTime12Hour(timeValue) {
                if (!timeValue) return '';

                const [hourText, minuteText] = timeValue.split(':');
                const hour = parseInt(hourText, 10);
                const minutes = minuteText || '00';
                const period = hour >= 12 ? 'PM' : 'AM';
                const displayHour = hour % 12 || 12;

                return `${String(displayHour).padStart(2, '0')}:${minutes} ${period}`;
            }

            /* ── DataTable ─────────────────────────────────────────── */
            const table = $('#shiftTable').DataTable({
                processing: true,
                serverSide: false,
                dom: 't<"d-flex align-items-center justify-content-between px-1 pt-2"ip>',
                ajax: {
                    url: DATA_URL,
                    type: 'GET',
                    dataSrc: function(json) {
                        const data = json.data || [];
                        $('#statTotal').text(data.length);
                        $('#statActive').text(data.filter(r => r.status === 'Active').length);
                        $('#statInactive').text(data.filter(r => r.status !== 'Active').length);
                        return data;
                    },
                    error: function() {
                        toastr.error('Failed to load shifts.');
                    }
                },
                language: {
                    processing: '<i class="fas fa-spinner fa-spin mr-2" style="color:#4f46e5;"></i><span style="color:#4f46e5;">Loading...</span>',
                    emptyTable: '<div style="padding:40px 0;color:#9ca3af;"><i class="fas fa-inbox" style="font-size:2rem;display:block;margin-bottom:10px;opacity:.4;"></i>No shifts found.</div>',
                    info: 'Showing _START_–_END_ of <strong>_TOTAL_</strong> shifts',
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
                        data: 'shift_name',
                        render: shift_name => `
        <span style="
            background:#eef2ff;
            color:#4338ca;
            padding:6px 14px;
            border-radius:20px;
            font-size:13px;
            font-weight:600;
        ">
            ${shift_name}
        </span>
    `
                    },
                    {
                        data: 'start_time',
                        render: (start_time, type) => {
                            if (type !== 'display') return start_time;

                            return `
            <span style="
                background:#ecfdf5;
                color:#059669;
                padding:6px 14px;
                border-radius:20px;
                font-size:13px;
                font-weight:600;
            ">
                <i class="fas fa-clock me-1"></i>
                ${formatTime12Hour(start_time)}
            </span>
        `;
                        }
                    },
                    {
                        data: 'end_time',
                        render: (end_time, type) => {
                            if (type !== 'display') return end_time;

                            return `
            <span style="
                background:#fef2f2;
                color:#dc2626;
                padding:6px 14px;
                border-radius:20px;
                font-size:13px;
                font-weight:600;
            ">
                <i class="fas fa-clock me-1"></i>
                ${formatTime12Hour(end_time)}
            </span>
        `;
                        }
                    },
                    {
                        data: 'late_after_minutes',
                        render: late_after_minutes =>
                            `<span style="color:#6b7280;">${late_after_minutes} mins</span>`
                    },
                    {
                        data: 'working_hours',
                        render: working_hours =>
                            `<span style="color:#6b7280;">${working_hours} hrs</span>`
                    },
                    {
                        data: 'status',
                        className: 'text-center',
                        render: function(data, type) {
                            if (type === 'display') {
                                return data === 'Active' ?
                                    '<span class="badge-active"><i class="fas fa-circle" style="font-size:.45rem;"></i>Active</span>' :
                                    '<span class="badge-inactive"><i class="fas fa-circle" style="font-size:.45rem;"></i>Inactive</span>';
                            }
                            return data;
                        }
                    },

                    {
                        data: 'id',
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
                                        data-id="${id}" data-name="${row.shift_name}"
                                        data-toggle="tooltip" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>`
                    }
                ]
            });

            /* ── Custom filters ─────────────────────────────────────── */
            $.fn.dataTable.ext.search.push(function(settings, data) {
                const statusFilter = $('#filterStatus').val().toLowerCase().trim();
                const rowStatus = (data[6] || '').toLowerCase().trim();
                if (statusFilter && rowStatus !== statusFilter) return false;
                return true;
            });

            $('#filterStatus').on('change', function() {
                table.draw();
            });

            $('#resetFilters').on('click', function() {
                $('#filterStatus').val('');
                table.search('').columns().search('').draw();
            });

            $('#clearSearch').on('click', function() {
                $(this).hide();
                table.search('').draw();
            });

            /* ── ADD NEW ────────────────────────────────────────────── */
            $('#btnCreate').on('click', function() {
                isEdit = false;
                resetForm();
                $('#shift_id').val('');
                $('#modalTitle').text('Add Shift');
                $('#shiftModal').modal('show');
            });

            /* ── Tooltips ───────────────────────────────────────────── */
            $(document).on('mouseenter', '[data-toggle="tooltip"]', function() {
                $(this).tooltip('show');
            });
            table.on('draw', function() {
                $('[data-toggle="tooltip"]').tooltip();
            });

            /* ── EDIT ───────────────────────────────────────────────── */
            $(document).on('click', '.btn-edit', function() {
                const id = $(this).data('id');
                isEdit = true;
                resetForm();
                $('#modalTitle').text('Edit Shift');
                $('#shiftModal').modal('show');

                $('#btnSave').prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin mr-1"></i> Loading...'
                );

                $.get(BASE_URL + '/' + id)
                    .done(function(res) {
                        if (res.success) {
                            const d = res.data;
                            $('#shift_id').val(d.shift_id);
                            $('#shift_name').val(d.shift_name);
                            $('#start_time').val(d.start_time);
                            $('#end_time').val(d.end_time);
                            $('#late_after_minutes').val(d.late_after_minutes);
                            $('#working_hours').val(d.working_hours);
                            $('#status').val(d.status);
                        }
                    })
                    .fail(function() {
                        toastr.error('Failed to load shift data.');
                        setTimeout(() => $('#shiftModal').modal('hide'), 1500);
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
                const id = $('#shift_id').val();
                const payload = {
                    shift_name: $('#shift_name').val().trim(),
                    start_time: $('#start_time').val(),
                    end_time: $('#end_time').val(),
                    late_after_minutes: $('#late_after_minutes').val(),
                    working_hours: $('#working_hours').val(),
                    status: $('#status').val(),
                    _token: CSRF,
                };

                let url = BASE_URL;
                if (isEdit && id) {
                    url = BASE_URL + '/' + id;
                    payload._method = 'PUT';
                }

                const $btn = $(this);
                $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Saving...');

                $.ajax({
                        url,
                        method: 'POST',
                        data: payload
                    })
                    .done(function(res) {
                        if (res.success) {
                            $('#shiftModal').modal('hide');
                            table.ajax.reload(null, false);
                            toastr.success(res.message);
                        }
                    })
                    .fail(function(xhr) {
                        if (xhr.status === 422 && xhr.responseJSON) {
                            showErrors(xhr.responseJSON.errors);
                            toastr.error('Please fix the errors and try again.');
                        } else {
                            toastr.error('Something went wrong. Please try again.');
                        }
                    })
                    .always(function() {
                        $btn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Save');
                    });
            });

            /* ── CONFIRM DELETE ─────────────────────────────────────── */
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
                        $btn.prop('disabled', false).html('<i class="fas fa-trash mr-1"></i> Delete');
                    });
            });

            /* ── Modal close: reset ─────────────────────────────────── */
            $('#shiftModal').on('hidden.bs.modal', resetForm);

            /* ── Helpers ────────────────────────────────────────────── */
            function resetForm() {
                $('#shift_name, #start_time, #end_time, #late_after_minutes, #working_hours').val('');
                $('#status').val('');
                clearErrors();
            }

            function clearErrors() {
                ['shift_name', 'start_time', 'end_time', 'late_after_minutes', 'working_hours', 'status']
                .forEach(function(f) {
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
