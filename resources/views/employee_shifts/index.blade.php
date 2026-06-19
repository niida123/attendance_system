{{-- resources/views/employee_shifts/index.blade.php --}}
@extends('adminlte::page')

@section('title', 'Employee Shifts')

@section('content_header')
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h1 class="m-0 font-weight-bold" style="color:#1a1f36;font-size:1.4rem;letter-spacing:-0.3px;">
                <i class="fas fa-clock mr-2" style="color:#4f46e5;"></i> Employee Shifts
            </h1>
            <ol class="breadcrumb mt-1 mb-0" style="background:transparent;padding:0;font-size:.8rem;">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" style="color:#4f46e5;">Home</a></li>
                <li class="breadcrumb-item active" style="color:#6b7280;">Employee Shifts</li>
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
                                Employee Shift List
                            </h3>
                            <small style="color:#9ca3af;font-size:.75rem;">
                                Manage all employee shifts
                            </small>
                        </div>
                    </div>
                    <!-- Right Side -->
                    <div class="d-flex align-items-center ml-auto mt-3" style="gap:10px; flex-wrap:wrap;">

                        <!-- Search Box -->
                        <div style="position: relative; width:250px; min-width:180px;">
                            <input type="text"
                                class="form-control form-control-sm"
                                placeholder="Search employee shift..."
                                id="searchEmployeeShift"
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
                                data-target="#employeeShiftModal"
                                id="btnCreate"
                                style="background:linear-gradient(135deg,#4f46e5,#7c3aed);color:#fff;border:none;border-radius:10px;padding:8px 18px;font-weight:600;font-size:.82rem;letter-spacing:.2px;box-shadow:0 4px 14px rgba(79,70,229,.35);">
                            <i class="fas fa-plus mr-1"></i> Add Employee Shift
                        </button>

                    </div>

                </div>

                <div class="card-body" style="padding:24px;background:#fafbff;">

                    {{-- Stats Row --}}
                    <div class="row mb-4" id="statsRow">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div style="background:#fff;border-radius:12px;padding:16px 20px;border:1px solid #f0f0f5;display:flex;align-items:center;gap:14px;">
                                <div style="width:42px;height:42px;border-radius:10px;background:#eef2ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas fa-users" style="color:#4f46e5;"></i>
                                </div>
                                <div>
                                    <div class="font-weight-bold" id="statTotal" style="font-size:1.4rem;color:#1a1f36;line-height:1;">—</div>
                                    <div style="font-size:.75rem;color:#9ca3af;margin-top:2px;">Total Employee Shifts</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div style="background:#fff;border-radius:12px;padding:16px 20px;border:1px solid #f0f0f5;display:flex;align-items:center;gap:14px;">
                                <div style="width:42px;height:42px;border-radius:10px;background:#ecfdf5;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas fa-check-circle" style="color:#10b981;"></i>
                                </div>
                                <div>
                                    <div class="font-weight-bold" id="statOngoing" style="font-size:1.4rem;color:#1a1f36;line-height:1;">—</div>
                                    <div style="font-size:.75rem;color:#9ca3af;margin-top:2px;">Ongoing</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div style="background:#fff;border-radius:12px;padding:16px 20px;border:1px solid #f0f0f5;display:flex;align-items:center;gap:14px;">
                                <div style="width:42px;height:42px;border-radius:10px;background:#fef2f2;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas fa-calendar-times" style="color:#ef4444;"></i>
                                </div>
                                <div>
                                    <div class="font-weight-bold" id="statExpired" style="font-size:1.4rem;color:#1a1f36;line-height:1;">—</div>
                                    <div style="font-size:.75rem;color:#9ca3af;margin-top:2px;">Expired</div>
                                </div>
                            </div>
                        </div>
                    </div>


                    {{-- Filter Bar --}}
                    <div class="d-flex align-items-center flex-wrap mb-4" style="gap:10px;">

                        {{-- Department Dropdown --}}
                        <div style="position:relative;min-width:180px;">
                            <select id="filterDepartment" class="form-control form-control-sm"
                                style="border-radius:10px;border:1.5px solid #e5e7eb;padding:6px 14px;font-size:.82rem;color:#374151;appearance:none;padding-right:30px;">
                                <option value="">⇄ All Departments</option>
                                @foreach ($departments as $dept)
                                    <option value="{{ $dept->department_name }}">{{ $dept->department_name }}</option>
                                @endforeach
                            </select>
                            <i class="fas fa-chevron-down" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);font-size:.7rem;color:#9ca3af;pointer-events:none;"></i>
                        </div>

                        {{-- Date Range --}}
                        <div class="d-flex align-items-center"
                            style="border:1.5px solid #e5e7eb;border-radius:10px;padding:5px 12px;background:#fff;gap:8px;font-size:.82rem;color:#374151;">
                            <i class="fas fa-calendar-alt" style="color:#9ca3af;font-size:.8rem;"></i>
                            <input type="date" id="filterDateFrom" placeholder="From"
                                style="border:none;outline:none;font-size:.82rem;color:#374151;width:110px;">
                            <span style="color:#9ca3af;">–</span>
                            <input type="date" id="filterDateTo" placeholder="To"
                                style="border:none;outline:none;font-size:.82rem;color:#374151;width:110px;">
                        </div>

                        {{-- Clear Filters --}}
                        <button id="btnClearFilters" class="btn btn-sm"
                            style="border-radius:10px;border:1.5px solid #e5e7eb;color:#6b7280;padding:6px 14px;font-size:.82rem;background:#fff;display:none;">
                            <i class="fas fa-times mr-1"></i> Clear
                        </button>

                        {{-- Spacer --}}
                        <div class="ml-auto d-flex" style="gap:8px;">

                            {{-- Export CSV --}}
                            <button id="btnExport" title="Export CSV"
                                style="width:34px;height:34px;border-radius:9px;border:1.5px solid #e5e7eb;background:#fff;color:#6b7280;display:inline-flex;align-items:center;justify-content:center;cursor:pointer;transition:all .2s;">
                                <i class="fas fa-download" style="font-size:.8rem;"></i>
                            </button>

                            {{-- Print --}}
                            <button id="btnPrint" title="Print"
                                style="width:34px;height:34px;border-radius:9px;border:1.5px solid #e5e7eb;background:#fff;color:#6b7280;display:inline-flex;align-items:center;justify-content:center;cursor:pointer;transition:all .2s;">
                                <i class="fas fa-print" style="font-size:.8rem;"></i>
                            </button>

                        </div>
                    </div>
                    {{-- Table --}}
                    <div style="background:#fff;border-radius:12px;border:1px solid #f0f0f5;overflow:hidden;">
                        <div style="overflow-x:auto;-webkit-overflow-scrolling:touch;">
                        <table id="employeeShiftTable" class="table table-hover w-100 mb-0" style="min-width:700px;">
                            <thead>
                                <tr style="background:#f8f9ff;">
                                    <th width="50"
                                        style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">
                                        #</th>
                                    <th
                                        style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">
                                        Employee Name</th>
                                    <th 
                                        style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">
                                        Shift Name</th>
                                    <th 
                                        style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">
                                        Effective From</th>
                                    <th 
                                        style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">
                                        Effective To</th>

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
     Modal: Add / Edit Employee Shift
     ============================================================ --}}
    <div class="modal fade" id="employeeShiftModal" tabindex="-1" role="dialog" aria-hidden="true">
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
                            <span id="modalTitle">Add Employee Shift</span>
                        </h5>
                    </div>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"
                        style="opacity:.8;font-size:1.3rem;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body" style="padding:28px 24px;background:#fff;">
                    <input type="hidden" id="employee_shift_id">

                    {{-- EMPLOYEE NAME (id) --}}
                    <div class="form-group mb-4">
                        <label for="employee_name"
                            style="font-weight:600;font-size:.83rem;color:#374151;letter-spacing:.2px;margin-bottom:6px;">
                            EMPLOYEE NAME <span class="text-danger">*</span>
                        </label>
                        {{-- select --}}
                        <select id="employee_name" class="form-control"
                            style="border-radius:10px;border:1.5px solid #e5e7eb;padding:10px 14px;font-size:.9rem;transition:border-color .2s;">
                            <option value="">— Select Employee —</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->employee_id }}">
                                    {{ $employee->first_name }} {{ $employee->last_name }}
                                </option>
                            @endforeach
                        </select>
                        <span class="text-danger small d-block mt-1" id="err_employee_name"></span>
                    </div>

                    {{-- SHIFT NAME (id) --}}
                    <div class="form-group mb-4">
                        <label for="shift_name"
                            style="font-weight:600;font-size:.83rem;color:#374151;letter-spacing:.2px;margin-bottom:6px;">
                            SHIFT NAME <span class="text-danger">*</span>
                        </label>
                        {{-- select --}}
                        <select id="shift_name" class="form-control"
                            style="border-radius:10px;border:1.5px solid #e5e7eb;padding:10px 14px;font-size:.9rem;transition:border-color .2s;">
                            <option value="">— Select Shift —</option>
                            @foreach ($shifts as $shift)
                                <option value="{{ $shift->shift_id }}">
                                    {{ $shift->shift_name }}
                                </option>
                            @endforeach
                        </select>
                        <span class="text-danger small d-block mt-1" id="err_shift_name"></span>
                    </div>

                    {{-- EFFECTIVE FROM --}}
                    <div class="form-group mb-4">
                        <label for="effective_from"
                            style="font-weight:600;font-size:.83rem;color:#374151;letter-spacing:.2px;margin-bottom:6px;">
                            EFFECTIVE FROM <span class="text-danger">*</span>
                        </label>
                        <input type="date" id="effective_from" class="form-control"
                            style="border-radius:10px;border:1.5px solid #e5e7eb;padding:10px 14px;font-size:.9rem;transition:border-color .2s;">
                        <span class="text-danger small d-block mt-1" id="err_effective_from"></span>
                    </div>

                    {{-- EFFECTIVE TO --}}
                    <div class="form-group mb-4">
                        <label for="effective_to"
                            style="font-weight:600;font-size:.83rem;color:#374151;letter-spacing:.2px;margin-bottom:6px;">
                            EFFECTIVE TO
                        </label>
                        <input type="date" id="effective_to" class="form-control"
                            style="border-radius:10px;border:1.5px solid #e5e7eb;padding:10px 14px;font-size:.9rem;transition:border-color .2s;">
                        <span class="text-danger small d-block mt-1" id="err_effective_to"></span>
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

        /* ── Filter bar hover ── */
        #btnExport:hover, #btnPrint:hover {
            background: #eef2ff !important;
            color: #4f46e5 !important;
            border-color: #c7d2fe !important;
        }
        #filterDepartment:focus {
            border-color: #4f46e5 !important;
            box-shadow: 0 0 0 3px rgba(79,70,229,.12) !important;
            outline: none;
        }
        #filterDateFrom:focus, #filterDateTo:focus {
            outline: none;
        }

        /* ── Table rows ── */
        #employeeShiftTable tbody tr {
            transition: background .15s;
        }

        #employeeShiftTable tbody tr:hover {
            background: #f5f6ff !important;
        }

        #employeeShiftTable tbody td {
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

            toastr.options = {
                closeButton: true,
                progressBar: true,
                positionClass: 'toast-top-right',
                timeOut: 3000
            };

            const BASE_URL = '{{ route('employee_shifts.index') }}';
            const DATA_URL = '{{ url('employee_shifts/data') }}';
            const CSRF    = '{{ csrf_token() }}';
            let isEdit   = false;
            let deleteId = null;

            /* ── DataTable ─────────────────────────────────────────── */
            const table = $('#employeeShiftTable').DataTable({
                processing: true,
                serverSide: false,
                dom: 't<"d-flex align-items-center justify-content-between px-1 pt-2"ip>',
                ajax: {
                    url: DATA_URL,
                    type: 'GET',

                    dataSrc: function(json) {
                        const data  = json.data || [];
                        const today = new Date().toISOString().split('T')[0];

                        $('#statTotal').text(data.length);
                        $('#statOngoing').text(data.filter(r => !r.effective_to || r.effective_to === '').length);
                        $('#statExpired').text(data.filter(r => r.effective_to && r.effective_to < today).length);

                        return data;
                    },
                    error: function(xhr) {
                        toastr.error('Failed to load employee shifts.');
                    }
                },
                language: {
                    processing: '<i class="fas fa-spinner fa-spin mr-2" style="color:#4f46e5;"></i><span style="color:#4f46e5;">Loading...</span>',
                    emptyTable: '<div style="padding:40px 0;color:#9ca3af;"><i class="fas fa-inbox" style="font-size:2rem;display:block;margin-bottom:10px;opacity:.4;"></i>No employee shifts found.</div>',
                    info: 'Showing _START_–_END_ of <strong>_TOTAL_</strong> employee shifts',
                    paginate: {
                        previous: '<i class="fas fa-chevron-left"></i>',
                        next:     '<i class="fas fa-chevron-right"></i>'
                    }
                },
                columns: [
                    {
                        data: null,
                        render: (d, t, r, m) =>
                            `<span class="row-num">${m.row + m.settings._iDisplayStart + 1}</span>`,
                        orderable: false
                    },
                    {
                        data: 'employee_name',
                        render: function(name, t, row) {
                            const parts   = name.trim().split(' ');
                            const initials = parts.length >= 2
                                ? parts[0][0] + parts[parts.length - 1][0]
                                : parts[0][0];

                            const colors = [
                                { bg: '#e0e7ff', color: '#4f46e5' },
                                { bg: '#d1fae5', color: '#059669' },
                                { bg: '#fce7f3', color: '#db2777' },
                                { bg: '#fef3c7', color: '#d97706' },
                                { bg: '#ede9fe', color: '#7c3aed' },
                                { bg: '#fee2e2', color: '#ef4444' },
                                { bg: '#e0f2fe', color: '#0284c7' },
                            ];
                            const { bg, color } = colors[name.charCodeAt(0) % colors.length];

                            const position = row.position_name || '—';

                            // Use photo if available, otherwise show initials avatar
                            const avatar = row.photo
                                ? `<img src="/storage/${row.photo}"
                                        style="width:38px;height:38px;border-radius:10px;object-fit:cover;flex-shrink:0;"
                                        onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                                <div style="display:none;width:38px;height:38px;border-radius:10px;
                                            background:${bg};color:${color};font-weight:700;font-size:.78rem;
                                            align-items:center;justify-content:center;flex-shrink:0;">
                                    ${initials.toUpperCase()}
                                </div>`
                                : `<div style="width:38px;height:38px;border-radius:10px;
                                            background:${bg};color:${color};font-weight:700;font-size:.78rem;
                                            display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    ${initials.toUpperCase()}
                                </div>`;

                            return `
                                <div class="d-flex align-items-center" style="gap:10px;">
                                    ${avatar}
                                    <div>
                                        <div style="font-weight:600;color:#1a1f36;font-size:.875rem;line-height:1.2;">
                                            ${name}
                                        </div>
                                        <div style="font-size:.75rem;color:#9ca3af;margin-top:2px;">
                                            ${position}
                                        </div>
                                    </div>
                                </div>`;
                        }
                    },
                    {
                        data: 'shift_name',
                        render: name => {
                            const colors = [
                                { bg: '#e0e7ff', color: '#4f46e5' },
                                { bg: '#d1fae5', color: '#059669' },
                                { bg: '#fce7f3', color: '#db2777' },
                                { bg: '#fef3c7', color: '#d97706' },
                                { bg: '#ede9fe', color: '#7c3aed' },
                                { bg: '#fee2e2', color: '#ef4444' },
                                { bg: '#e0f2fe', color: '#0284c7' },
                            ];
                            const { bg, color } = colors[name.charCodeAt(0) % colors.length];

                            return `<span style="
                                background:${bg};
                                color:${color};
                                padding:5px 12px;
                                border-radius:20px;
                                font-size:.78rem;
                                font-weight:600;
                                display:inline-block;
                                letter-spacing:.2px;">
                                ${name}
                            </span>`;
                        }
                    },
                    {
                        data: 'effective_from',
                        render: d =>
                            `<span style="color:#9ca3af;font-size:.82rem;">${new Date(d).toLocaleDateString('en-GB', { day:'2-digit', month:'short', year:'numeric' })}</span>`
                    },
                    {
                        data: 'effective_to',
                        render: d => {
                            if (!d || d === null || d === '') {
                                return `<span style="
                                    background:#ecfdf5;color:#059669;
                                    padding:4px 10px;border-radius:20px;
                                    font-size:.75rem;font-weight:600;
                                    display:inline-flex;align-items:center;gap:5px;">
                                    <i class="fas fa-circle" style="font-size:.45rem;"></i> Ongoing
                                </span>`;
                            }
                            return `<span style="color:#9ca3af;font-size:.82rem;">
                                ${new Date(d).toLocaleDateString('en-GB', { day:'2-digit', month:'short', year:'numeric' })}
                            </span>`;
                        }
                    },
                    {
                        data: 'employee_shift_id',
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
                                        data-id="${id}" data-name="${row.employee_name}"
                                        data-toggle="tooltip" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>`
                    }
                ]
            });

            /* ── Custom Search ─────────────────────────────────────── */
            $('#searchEmployeeShift').on('keyup', function() {
                const value = $(this).val();
                $('#clearSearch').toggle(value.length > 0);
                table.search(value).draw();
            });

            $('#clearSearch').on('click', function() {
                $('#searchEmployeeShift').val('');
                $(this).hide();
                table.search('').draw();
            });

            /* ── Filters ───────────────────────────────────────────── */

            // Department filter (custom search on department_name column)
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex, rowData) {
                const dept     = $('#filterDepartment').val();
                const dateFrom = $('#filterDateFrom').val();
                const dateTo   = $('#filterDateTo').val();

                // Department match
                if (dept && rowData.department_name !== dept) return false;

                // Date range match against effective_from
                if (dateFrom && rowData.effective_from < dateFrom) return false;
                if (dateTo   && rowData.effective_from > dateTo)   return false;

                return true;
            });

            $('#filterDepartment, #filterDateFrom, #filterDateTo').on('change', function() {
                const hasFilter = $('#filterDepartment').val() || $('#filterDateFrom').val() || $('#filterDateTo').val();
                $('#btnClearFilters').toggle(!!hasFilter);
                table.draw();
            });

            $('#btnClearFilters').on('click', function() {
                $('#filterDepartment').val('');
                $('#filterDateFrom').val('');
                $('#filterDateTo').val('');
                $(this).hide();
                table.draw();
            });

            /* ── Export CSV ─────────────────────────────────────────── */
            $('#btnExport').on('click', function() {
                const rows  = table.rows({ search: 'applied' }).data().toArray();
                const today = new Date().toISOString().split('T')[0];

                let csv = 'Employee Name,Position,Shift,Effective From,Effective To\n';
                rows.forEach(r => {
                    const to = r.effective_to ? r.effective_to : 'Ongoing';
                    csv += `"${r.employee_name}","${r.position_name || ''}","${r.shift_name}","${r.effective_from}","${to}"\n`;
                });

                const blob = new Blob([csv], { type: 'text/csv' });
                const url  = URL.createObjectURL(blob);
                const a    = document.createElement('a');
                a.href     = url;
                a.download = `employee_shifts_${today}.csv`;
                a.click();
                URL.revokeObjectURL(url);
            });

            /* ── Print ──────────────────────────────────────────────── */
            $('#btnPrint').on('click', function() {
                const rows  = table.rows({ search: 'applied' }).data().toArray();
                const today = new Date().toLocaleDateString('en-GB', { day:'2-digit', month:'short', year:'numeric' });

                let html = `
                    <html><head><title>Employee Shifts</title>
                    <style>
                        body { font-family: sans-serif; font-size: 13px; color: #1a1f36; }
                        h2   { color: #4f46e5; margin-bottom: 4px; }
                        p    { color: #9ca3af; font-size: 11px; margin-top: 0; }
                        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
                        th { background: #f8f9ff; padding: 10px 14px; text-align:left; font-size:11px; text-transform:uppercase; letter-spacing:.5px; color:#6b7280; border-bottom: 2px solid #eef0f8; }
                        td { padding: 10px 14px; border-bottom: 1px solid #f3f4f6; }
                        tr:hover td { background: #f5f6ff; }
                    </style></head><body>
                    <h2>Employee Shifts</h2>
                    <p>Printed on ${today}</p>
                    <table>
                        <thead><tr>
                            <th>#</th><th>Employee</th><th>Position</th><th>Shift</th><th>Effective From</th><th>Effective To</th>
                        </tr></thead><tbody>`;

                rows.forEach((r, i) => {
                    const from = new Date(r.effective_from).toLocaleDateString('en-GB', { day:'2-digit', month:'short', year:'numeric' });
                    const to   = r.effective_to
                        ? new Date(r.effective_to).toLocaleDateString('en-GB', { day:'2-digit', month:'short', year:'numeric' })
                        : 'Ongoing';
                    html += `<tr>
                        <td>${i + 1}</td>
                        <td><strong>${r.employee_name}</strong></td>
                        <td>${r.position_name || '—'}</td>
                        <td>${r.shift_name}</td>
                        <td>${from}</td>
                        <td>${to}</td>
                    </tr>`;
                });

                html += `</tbody></table></body></html>`;

                const w = window.open('', '_blank');
                w.document.write(html);
                w.document.close();
                w.print();
            });

            /* ── Tooltips ──────────────────────────────────────────── */
            $(document).on('mouseenter', '[data-toggle="tooltip"]', function() {
                $(this).tooltip('show');
            });
            table.on('draw', function() {
                $('[data-toggle="tooltip"]').tooltip();
            });

            /* ── Modal: Add ────────────────────────────────────────── */
            $('#employeeShiftModal').on('show.bs.modal', function(e) {
                if (e.relatedTarget && $(e.relatedTarget).is('#btnCreate')) {
                    isEdit = false;
                    resetForm();
                    $('#modalTitle').text('Add Employee Shift');
                }
            });

            /* ── EDIT ──────────────────────────────────────────────── */
            $(document).on('click', '.btn-edit', function() {
                const id = $(this).data('id');
                isEdit = true;
                resetForm();
                $('#modalTitle').text('Edit Employee Shift');
                $('#employeeShiftModal').modal('show');

                $('#btnSave').prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin mr-1"></i> Loading...'
                );

                $.get(BASE_URL + '/' + id)
                    .done(function(res) {
                        if (res.success) {
                            const d = res.data;
                            $('#employee_shift_id').val(d.employee_shift_id);
                            $('#employee_name').val(d.employee_id);   // ← set dropdown by ID
                            $('#shift_name').val(d.shift_id);          // ← set dropdown by ID
                            $('#effective_from').val(d.effective_from);
                            $('#effective_to').val(d.effective_to);
                        }
                    })
                    .fail(function() {
                        toastr.error('Failed to load employee shift data.');
                        setTimeout(() => $('#employeeShiftModal').modal('hide'), 1500);
                    })
                    .always(function() {
                        $('#btnSave').prop('disabled', false).html(
                            '<i class="fas fa-save mr-1"></i> Save'
                        );
                    });
            });

            /* ── DELETE ────────────────────────────────────────────── */
            $(document).on('click', '.btn-delete', function() {
                deleteId = $(this).data('id');
                $('#deleteName').text($(this).data('name'));
                $('#deleteModal').modal('show');
            });

            /* ── SAVE (Store & Update) ─────────────────────────────── */
            $(document).on('click', '#btnSave', function() {
                const id = $('#employee_shift_id').val();

                // ✅ Send employee_id and shift_id — not employee_name/shift_name
                const payload = {
                    employee_id:    $('#employee_name').val(),
                    shift_id:       $('#shift_name').val(),
                    effective_from: $('#effective_from').val().trim(),
                    effective_to:   $('#effective_to').val().trim(),
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

                $.ajax({ url, method: 'POST', data: payload })
                    .done(function(res) {
                        if (res.success) {
                            $('#employeeShiftModal').modal('hide');
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

            /* ── CONFIRM DELETE ────────────────────────────────────── */
            $(document).on('click', '#btnConfirmDelete', function() {
                if (!deleteId) return;

                const $btn = $(this);
                $btn.prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin mr-1"></i> Deleting...'
                );

                $.ajax({
                    url: BASE_URL + '/' + deleteId,
                    method: 'POST',
                    data: { _token: CSRF, _method: 'DELETE' }
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
                    toastr.error(
                        xhr.status === 422 && xhr.responseJSON
                            ? xhr.responseJSON.message
                            : 'Something went wrong. Please try again.'
                    );
                })
                .always(function() {
                    deleteId = null;
                    $btn.prop('disabled', false).html(
                        '<i class="fas fa-trash mr-1"></i> Delete'
                    );
                });
            });

            /* ── Modal close: reset ────────────────────────────────── */
            $('#employeeShiftModal').on('hidden.bs.modal', resetForm);

            /* ── Helpers ───────────────────────────────────────────── */
            function resetForm() {
                $('#employee_shift_id').val('');
                $('#employee_name').val('');   // resets select to blank option
                $('#shift_name').val('');       // resets select to blank option
                $('#effective_from').val('');
                $('#effective_to').val('');
                clearErrors();
            }

            function clearErrors() {
                ['employee_name', 'shift_name', 'effective_from', 'effective_to'].forEach(function(f) {
                    $('#' + f).removeClass('is-invalid');
                    $('#err_' + f).text('');
                });
            }

            function showErrors(errors) {
                clearErrors();
                $.each(errors, function(field, msgs) {
                    // map controller field names back to HTML element IDs
                    const map = { employee_id: 'employee_name', shift_id: 'shift_name' };
                    const elId = map[field] || field;
                    $('#' + elId).addClass('is-invalid');
                    $('#err_' + elId).text(msgs[0]);
                });
            }
        });
    </script>
@stop
