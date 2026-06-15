{{-- resources/views/employees/index.blade.php --}}
@extends('adminlte::page')

@section('title', 'Employees')

@section('content_header')
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h1 class="m-0 font-weight-bold" style="color:#1a1f36;font-size:1.4rem;letter-spacing:-0.3px;">
                <i class="fas fa-briefcase mr-2" style="color:#4f46e5;"></i> Employees
            </h1>
            <ol class="breadcrumb mt-1 mb-0" style="background:transparent;padding:0;font-size:.8rem;">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" style="color:#4f46e5;">Home</a></li>
                <li class="breadcrumb-item active" style="color:#6b7280;">Employees</li>
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
                                Employee List
                            </h3>
                            <small style="color:#9ca3af;font-size:.75rem;">
                                Manage all employees and their details here
                            </small>
                        </div>
                    </div>

                    <div class="d-flex align-items-center ml-auto" style="gap:10px; flex-wrap:wrap;">

                        {{-- Add Button --}}
                        <button type="button"
                            class="btn btn-sm"
                            data-toggle="modal"
                            data-target="#employeeModal"
                            id="btnCreate"
                            style="background:linear-gradient(135deg,#4f46e5,#7c3aed);
                                color:#fff;
                                border:none;
                                border-radius:10px;
                                padding:8px 18px;
                                font-weight:600;
                                font-size:.82rem;">
                            <i class="fas fa-plus mr-1"></i> Add Employee
                        </button>

                    </div>

                </div>

                <div class="card-body" style="padding:24px;background:#fafbff;">

                    {{-- Stats Row --}}
                    <div class="row mb-4" id="statsRow">
                        <div class="col-md-3 mb-3 mb-md-0">
                            <div
                                style="background:#fff;border-radius:12px;padding:16px 20px;border:1px solid #f0f0f5;display:flex;align-items:center;gap:14px;">
                                <div
                                    style="width:42px;height:42px;border-radius:10px;background:#eef2ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas fa-briefcase" style="color:#4f46e5;"></i>
                                </div>
                                <div>
                                    <div class="font-weight-bold" id="statTotal"
                                        style="font-size:1.4rem;color:#1a1f36;line-height:1;">—</div>
                                    <div style="font-size:.75rem;color:#9ca3af;margin-top:2px;">Total Employees</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3 mb-md-0">
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
                        <div class="col-md-3">
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
                        <div class="col-md-3">
                            <div
                                style="background:#fff;border-radius:12px;padding:16px 20px;border:1px solid #f0f0f5;display:flex;align-items:center;gap:14px;">
                                <div
                                    style="width:42px;height:42px;border-radius:10px;background:#f3f4f6;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas fa-female" style="color:#ec4899;"></i>
                                </div>
                                <div>
                                    <div class="font-weight-bold" id="statFemale"
                                        style="font-size:1.4rem;color:#1a1f36;line-height:1;">—</div>
                                    <div style="font-size:.75rem;color:#9ca3af;margin-top:2px;">Female</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center ml-auto mt-3" style="gap:10px; flex-wrap:wrap;">

                        {{-- Search Box --}}
                        <div style="position: relative; width:250px; min-width:180px;">
                            <i class="fas fa-search"
                            style="position:absolute; left:10px; top:50%; transform:translateY(-50%); color:#9ca3af;"></i>

                            <input type="text"
                                class="form-control form-control-sm"
                                placeholder="Search name, CODE, Position..."
                                id="searchEmployee"
                                style="padding-left:30px; padding-right:35px; height:36px; border-radius:10px;">

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

                        {{-- Department Filter --}}
                        <div style="width:190px;min-width:150px; flex:1 1 150px;">
                            <select id="filterDepartment" class="form-control form-control-sm"
                                style="height:36px; border-radius:10px;">
                                <option value="">All Departments</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->department_name }}">
                                        {{ $department->department_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Status Filter --}}
                        <div style="width:150px;min-width:120px; flex:1 1 120px;">
                            <select id="filterStatus" class="form-control form-control-sm"
                                style="height:36px; border-radius:10px;">
                                <option value="">All Status</option>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>

                        <!-- Reset Button -->
                        <button id="resetFilters"
                                class="btn btn-sm"
                                style="
                                    height:36px;
                                    border-radius:10px;
                                    border:1.5px solid #e5e7eb;
                                    background:#fff;
                                    color:#6b7280;
                                    font-weight:600;
                                    font-size:.8rem;
                                    padding:0 14px;
                                    transition:.2s;
                                ">
                            <i class="fas fa-undo mr-1"></i> Reset
                        </button>

                    </div>

                    {{-- Table --}}
                    <div style="background:#fff;border-radius:12px;border:1px solid #f0f0f5;overflow:hidden;">
                        <div style="overflow-x:auto;-webkit-overflow-scrolling:touch;">
                        <table id="employeeTable" class="table table-hover w-100 mb-0" style="min-width:700px;">
                            <thead>
                                <tr style="background:#f8f9ff;">
                                    <th style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;" width="50">#</th>
                                    <th style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">Employee</th>
                                    <th style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">Department</th>
                                    <th style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">Position</th>
                                    <th style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;" width="100">Status</th>
                                    <th style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;" width="150" class="text-center">Actions</th>
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
     Modal: Add / Edit Employee
     ============================================================ --}}
    <div class="modal fade" id="employeeModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document" style="max-width:680px;">
            <div class="modal-content"
                style="border:none;border-radius:16px;overflow:hidden;box-shadow:0 25px 60px rgba(0,0,0,.15);">

                <div class="modal-header"
                    style="background:linear-gradient(135deg,#4f46e5,#7c3aed);padding:20px 24px;border:none;">
                    <div class="d-flex align-items-center">
                        <div
                            style="width:36px;height:36px;border-radius:9px;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;margin-right:12px;">
                            <i class="fas fa-building" style="color:#fff;font-size:.9rem;"></i>
                        </div>
                        <h5 class="modal-title text-white mb-0 font-weight-bold" style="font-size:1rem;">
                            <span id="modalTitle">Add Employee</span>
                        </h5>
                    </div>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"
                        style="opacity:.8;font-size:1.3rem;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body" style="padding:20px;background:#fff;max-height:70vh;overflow-y:auto;">
                    <input type="hidden" id="employee_id">

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 16px;">

                        <div class="form-group mb-3">
                            <label class="modal-label">EMPLOYEE CODE <span class="text-danger">*</span></label>
                            <input type="text" id="employee_code" class="form-control form-control-sm modal-input" placeholder="e.g. EMP001">
                            <span class="text-danger small d-block mt-1" id="err_employee_code"></span>
                        </div>

                        <div class="form-group mb-3">
                            <label class="modal-label">GENDER <span class="text-danger">*</span></label>
                            <select id="gender" class="form-control form-control-sm modal-input">
                                <option value="">— Select —</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                            <span class="text-danger small d-block mt-1" id="err_gender"></span>
                        </div>

                        <div class="form-group mb-3">
                            <label class="modal-label">FIRST NAME <span class="text-danger">*</span></label>
                            <input type="text" id="first_name" class="form-control form-control-sm modal-input" placeholder="e.g. John">
                            <span class="text-danger small d-block mt-1" id="err_first_name"></span>
                        </div>

                        <div class="form-group mb-3">
                            <label class="modal-label">LAST NAME <span class="text-danger">*</span></label>
                            <input type="text" id="last_name" class="form-control form-control-sm modal-input" placeholder="e.g. Doe">
                            <span class="text-danger small d-block mt-1" id="err_last_name"></span>
                        </div>

                        <div class="form-group mb-3">
                            <label class="modal-label">DEPARTMENT <span class="text-danger">*</span></label>
                            <select id="department_id" class="form-control form-control-sm modal-input">
                                <option value="">— Select —</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->department_id }}">{{ $department->department_name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger small d-block mt-1" id="err_department_id"></span>
                        </div>

                        <div class="form-group mb-3">
                            <label class="modal-label">POSITION <span class="text-danger">*</span></label>
                            <select id="position_id" class="form-control form-control-sm modal-input">
                                <option value="">— Select —</option>
                                @foreach ($positions as $position)
                                    <option value="{{ $position->position_id }}">{{ $position->position_name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger small d-block mt-1" id="err_position_id"></span>
                        </div>

                        <div class="form-group mb-3">
                            <label class="modal-label">DATE OF BIRTH</label>
                            <input type="date" id="date_of_birth" class="form-control form-control-sm modal-input">
                            <span class="text-danger small d-block mt-1" id="err_date_of_birth"></span>
                        </div>

                        <div class="form-group mb-3">
                            <label class="modal-label">HIRE DATE <span class="text-danger">*</span></label>
                            <input type="date" id="hire_date" class="form-control form-control-sm modal-input">
                            <span class="text-danger small d-block mt-1" id="err_hire_date"></span>
                        </div>

                        <div class="form-group mb-3">
                            <label class="modal-label">PHONE</label>
                            <input type="text" id="phone" class="form-control form-control-sm modal-input" placeholder="e.g. +855 12 345 678">
                            <span class="text-danger small d-block mt-1" id="err_phone"></span>
                        </div>

                        <div class="form-group mb-3">
                            <label class="modal-label">EMAIL</label>
                            <input type="email" id="email" class="form-control form-control-sm modal-input" placeholder="e.g. john@example.com">
                            <span class="text-danger small d-block mt-1" id="err_email"></span>
                        </div>

                        <div class="form-group mb-3">
                            <label class="modal-label">BASIC SALARY</label>
                            <input type="number" id="basic_salary" class="form-control form-control-sm modal-input" placeholder="e.g. 1200.00" min="0" step="0.01">
                            <span class="text-danger small d-block mt-1" id="err_basic_salary"></span>
                        </div>

                        <div class="form-group mb-3">
                            <label class="modal-label">STATUS <span class="text-danger">*</span></label>
                            <select id="status" class="form-control form-control-sm modal-input">
                                <option value="">— Select —</option>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                            <span class="text-danger small d-block mt-1" id="err_status"></span>
                        </div>

                        <div class="form-group mb-3">
                            <label class="modal-label">PHOTO</label>
                            <input type="file" id="photo" class="form-control form-control-sm modal-input" accept="image/jpeg,image/png">
                            <span class="text-danger small d-block mt-1" id="err_photo"></span>
                        </div>

                        <div class="form-group mb-3">
                            <label class="modal-label">ADDRESS</label>
                            <input type="text" id="address" class="form-control form-control-sm modal-input" placeholder="e.g. Phnom Penh">
                            <span class="text-danger small d-block mt-1" id="err_address"></span>
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
     Modal: View Detail
     ============================================================ --}}
    <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content"
                style="border:none;border-radius:16px;overflow:hidden;box-shadow:0 25px 60px rgba(0,0,0,.15);">

                <div class="modal-header"
                    style="background:linear-gradient(135deg,#4f46e5,#7c3aed);padding:20px 24px;border:none;">
                    <div class="d-flex align-items-center">
                        <div style="width:36px;height:36px;border-radius:9px;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;margin-right:12px;">
                            <i class="fas fa-id-card" style="color:#fff;font-size:.9rem;"></i>
                        </div>
                        <h5 class="modal-title text-white mb-0 font-weight-bold" style="font-size:1rem;">
                            Employee Detail
                        </h5>
                    </div>
                    <button type="button" class="close text-white" data-dismiss="modal" style="opacity:.8;font-size:1.3rem;">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body" style="padding:0;background:#fff;" id="viewModalBody">
                    <div class="text-center py-5">
                        <i class="fas fa-spinner fa-spin" style="color:#4f46e5;font-size:1.5rem;"></i>
                    </div>
                </div>

                <div class="modal-footer" style="padding:14px 24px;background:#f9fafb;border-top:1px solid #f0f0f5;">
                    <button type="button" class="btn btn-sm" data-dismiss="modal"
                        style="border-radius:9px;border:1.5px solid #e5e7eb;color:#6b7280;padding:8px 20px;font-weight:600;font-size:.83rem;background:#fff;">
                        <i class="fas fa-times mr-1"></i> Close
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

        /* Reset Filters Button */
        #resetFilters:hover {
            background: #f3f4f6 !important;
            color: #111827 !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(0,0,0,.08);
        }

        #filterDepartment,
        #filterStatus {
            transition: all .2s ease;
        }

        #filterDepartment:focus,
        #filterStatus:focus {
            border-color: #4f46e5 !important;
            box-shadow: 0 0 0 3px rgba(79,70,229,.12) !important;
        }
        /* ── Table rows ── */
        #employeeTable tbody tr {
            transition: background .15s;
        }

        #employeeTable tbody tr:hover {
            background: #f5f6ff !important;
        }

        #employeeTable tbody td {
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

        /* ── View button ── */
        .btn-view-row {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: #f0fdf4;
            color: #10b981;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all .2s;
            font-size: .8rem;
        }

        .btn-view-row:hover {
            background: #10b981;
            color: #fff;
            box-shadow: 0 4px 12px rgba(16,185,129,.3);
            transform: translateY(-1px);
        }

        /* ── Row number pill ── */

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

        /* ── Compact modal form ── */
        .modal-label {
            font-weight: 600;
            font-size: .72rem;
            color: #374151;
            letter-spacing: .3px;
            margin-bottom: 4px;
            display: block;
        }

        .modal-input {
            border-radius: 8px !important;
            border: 1.5px solid #e5e7eb !important;
            font-size: .82rem !important;
            height: 34px !important;
            padding: 5px 10px !important;
        }

        select.modal-input {
            height: 34px !important;
            padding: 4px 10px !important;
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

            const BASE_URL = '{{ route('employees.index') }}';
            const DATA_URL = '{{ url('employees/data') }}';
            const CSRF = '{{ csrf_token() }}';
            let isEdit = false;
            let deleteId = null;

            /* ── DataTable ──────────────────────────────────────────── */
            const table = $('#employeeTable').DataTable({
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
                        $('#statFemale').text(data.filter(r => r.gender === 'Female').length);
                        return data;
                    },
                    error: function(xhr) {
                        console.error('DataTable AJAX error:', xhr.status, xhr.responseText);
                        toastr.error('Failed to load employees.');
                    }
                },
                language: {
                    processing: '<i class="fas fa-spinner fa-spin mr-2" style="color:#4f46e5;"></i><span style="color:#4f46e5;">Loading...</span>',
                    emptyTable: '<div style="padding:40px 0;color:#9ca3af;"><i class="fas fa-inbox" style="font-size:2rem;display:block;margin-bottom:10px;opacity:.4;"></i>No employees found.</div>',
                    info: 'Showing _START_–_END_ of <strong>_TOTAL_</strong> employees',
                    paginate: {
                        previous: '<i class="fas fa-chevron-left"></i>',
                        next: '<i class="fas fa-chevron-right"></i>'
                    }
                },
                columns: [
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: (d, t, r, m) =>
                            `<span class="row-num">${m.row + m.settings._iDisplayStart + 1}</span>`
                    },
                    {
                        data: null,
                        render: (d, type, row) => {
                            if (type === 'filter' || type === 'sort') {
                                return `${row.first_name} ${row.last_name} ${row.employee_code}`;
                            }
                            const name     = `${row.first_name} ${row.last_name}`;
                            const code     = `<div style="font-size:.72rem;color:#9ca3af;">${row.employee_code}</div>`;
                            const initials = (row.first_name.charAt(0) + row.last_name.charAt(0)).toUpperCase();
                            const avatar   = row.photo
                                ? `<img src="/storage/${row.photo}" class="rounded-circle mr-2" style="width:36px;height:36px;object-fit:cover;flex-shrink:0;" alt="">`
                                : `<div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#4f46e5,#7c3aed);color:#fff;display:inline-flex;align-items:center;justify-content:center;font-weight:700;font-size:.75rem;flex-shrink:0;margin-right:8px;">${initials}</div>`;
                            return `<div class="d-flex align-items-center">${avatar}<div><div style="font-weight:600;color:#1a1f36;line-height:1.3;">${name}</div>${code}</div></div>`;
                        }
                    },
                    {
                        data: 'department.department_name',
                        render: dept =>
                            dept ? `<span style="color:#374151;font-weight:500;">${dept}</span>`
                                 : '<span style="color:#d1d5db;">—</span>'
                    },
                    {
                        data: 'position.position_name',
                        render: function(pos, type) {
                            if (type === 'filter' || type === 'sort') {
                                return pos || '';
                            }
                            return pos ? `<span style="color:#6b7280;">${pos}</span>`
                                    : '<span style="color:#d1d5db;">—</span>';
                        }
                    },
                    {
                        data: 'status',
                        className: 'text-center',
                        render: function (data, type) {
                            if (type === 'display') {
                                return data === 'Active'
                                    ? '<span class="badge-active"><i class="fas fa-circle mr-1" style="font-size:.5rem;vertical-align:middle;"></i>Active</span>'
                                    : '<span class="badge-inactive"><i class="fas fa-circle mr-1" style="font-size:.5rem;vertical-align:middle;"></i>Inactive</span>';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'employee_id',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: (id, t, row) => `
                            <div class="d-flex align-items-center justify-content-center" style="gap:6px;">
                                <button type="button" class="btn-view-row btn-view"
                                        data-id="${id}" data-toggle="tooltip" title="View Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button type="button" class="btn-edit-row btn-edit"
                                        data-id="${id}" data-toggle="tooltip" title="Edit">
                                    <i class="fas fa-pen"></i>
                                </button>
                                <button type="button" class="btn-delete-row btn-delete"
                                        data-id="${id}"
                                        data-name="${row.first_name} ${row.last_name}"
                                        data-toggle="tooltip" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>`
                    }
                ]
            });

            $.fn.dataTable.ext.search.push(function (settings, data) {

                let statusFilter = $('#filterStatus').val().toLowerCase().trim();
                let departmentFilter = $('#filterDepartment').val().toLowerCase().trim();

                let rowDepartment = (data[2] || '').toLowerCase().trim();
                let rowStatus = (data[4] || '').toLowerCase().trim();

                // Department check
                if (departmentFilter && rowDepartment !== departmentFilter) {
                    return false;
                }

                // Status check
                if (statusFilter && rowStatus !== statusFilter) {
                    return false;
                }

                return true;
            });
            $('#filterStatus, #filterDepartment').on('change', function () {
                table.draw();
            });
            // Reset Filters
            $('#resetFilters').on('click', function () {
                $('#filterDepartment').val('');
                $('#filterStatus').val('');
                $('#searchEmployee').val('');
                $('#clearSearch').hide();
                table.search('').columns().search('').draw();
            });

            // Custom Search
            $('#searchEmployee').on('keyup', function () {
                let value = $(this).val();

                if (value.length > 0) {
                    $('#clearSearch').show();
                } else {
                    $('#clearSearch').hide();
                }

                table.search(value).draw();
            });

            $('#clearSearch').on('click', function () {
                $('#searchEmployee').val('');
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

            /* ── VIEW DETAIL ────────────────────────────────────────── */
            $(document).on('click', '.btn-view', function () {
                const id = $(this).data('id');
                $('#viewModalBody').html('<div class="text-center py-5"><i class="fas fa-spinner fa-spin" style="color:#4f46e5;font-size:1.5rem;"></i></div>');
                $('#viewModal').modal('show');

                $.get(BASE_URL + '/' + id)
                    .done(function (res) {
                        if (!res.success) return;
                        const d = res.data;
                        const name     = `${d.first_name} ${d.last_name}`;
                        const initials = (d.first_name.charAt(0) + d.last_name.charAt(0)).toUpperCase();
                        const avatar   = d.photo
                            ? `<img src="/storage/${d.photo}" style="width:110px;height:110px;border-radius:50%;object-fit:cover;border:3px solid rgba(255,255,255,.4);box-shadow:0 4px 14px rgba(0,0,0,.2);" alt="">`
                            : `<div style="width:110px;height:110px;border-radius:50%;background:rgba(255,255,255,.2);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:2rem;border:3px solid rgba(255,255,255,.3);">${initials}</div>`;
                        const statusBadge = d.status === 'Active'
                            ? '<span class="badge-active"><i class="fas fa-circle mr-1" style="font-size:.5rem;vertical-align:middle;"></i>Active</span>'
                            : '<span class="badge-inactive"><i class="fas fa-circle mr-1" style="font-size:.5rem;vertical-align:middle;"></i>Inactive</span>';
                        const gColor = d.gender === 'Male' ? '#3b82f6' : '#ec4899';
                        const gBg    = d.gender === 'Male' ? '#eff6ff' : '#fdf2f8';
                        const fmt    = v => v || '<span style="color:#d1d5db;">—</span>';
                        const fmtDate= v => v ? new Date(v).toLocaleDateString('en-GB', {day:'2-digit',month:'short',year:'numeric'}) : '—';
                        const fmtSalary = v => v ? `$${parseFloat(v).toLocaleString('en-US',{minimumFractionDigits:2})}` : '—';

                        function row(icon, label, value) {
                            return `
                            <div style="display:flex;align-items:flex-start;padding:8px 0;border-bottom:1px solid #f3f4f6;">
                                <div style="width:26px;height:26px;border-radius:6px;background:#eef2ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-right:8px;">
                                    <i class="fas fa-${icon}" style="color:#4f46e5;font-size:.7rem;"></i>
                                </div>
                                <div>
                                    <div style="font-size:.68rem;color:#9ca3af;text-transform:uppercase;letter-spacing:.4px;font-weight:600;">${label}</div>
                                    <div style="font-size:.82rem;color:#1a1f36;font-weight:500;margin-top:1px;">${value}</div>
                                </div>
                            </div>`;
                        }

                        $('#viewModalBody').html(`
                            <div style="display:flex;gap:0;">

                                {{-- Left: photo + name --}}
                                <div style="width:180px;flex-shrink:0;background:linear-gradient(160deg,#4f46e5,#7c3aed);padding:24px 16px;display:flex;flex-direction:column;align-items:center;justify-content:flex-start;">
                                    <div style="margin-bottom:12px;">${avatar}</div>
                                    <div style="color:#fff;font-weight:700;font-size:.88rem;text-align:center;line-height:1.4;">${name}</div>
                                    <div style="color:rgba(255,255,255,.65);font-size:.72rem;margin-top:4px;">${d.employee_code}</div>
                                    <div style="margin-top:10px;">${statusBadge}</div>
                                </div>

                                {{-- Right: 2-column grid --}}
                                <div style="flex:1;padding:16px 20px;overflow-y:auto;max-height:420px;">
                                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:0 16px;">
                                        ${row('building',       'Department',   fmt(d.department?.department_name))}
                                        ${row('briefcase',      'Position',     fmt(d.position?.position_name))}
                                        ${row('venus-mars',     'Gender',       `<span style="background:${gBg};color:${gColor};padding:2px 8px;border-radius:20px;font-size:.72rem;font-weight:600;">${d.gender}</span>`)}
                                        ${row('calendar-alt',   'Hire Date',    fmtDate(d.hire_date))}
                                        ${row('birthday-cake',  'Date of Birth',fmtDate(d.date_of_birth))}
                                        ${row('dollar-sign',    'Basic Salary', fmtSalary(d.basic_salary))}
                                        ${row('phone',          'Phone',        fmt(d.phone))}
                                        ${row('envelope',       'Email',        fmt(d.email))}
                                        ${row('map-marker-alt', 'Address',      fmt(d.address))}
                                    </div>
                                </div>

                            </div>
                        `);
                    })
                    .fail(function () {
                        $('#viewModalBody').html('<div class="text-center py-4 text-muted">Failed to load employee data.</div>');
                    });
            });

            /* ── Filter positions by department ─────────────────────── */
            $(document).on('change', '#department_id', function () {
                const deptId = $(this).val();
                const $pos   = $('#position_id');

                $pos.html('<option value="">— Select —</option>');

                if (!deptId) return;

                $pos.html('<option value="">Loading...</option>').prop('disabled', true);

                $.get(`/positions/by-department/${deptId}`)
                    .done(function (res) {
                        $pos.html('<option value="">— Select Position —</option>');
                        if (res.success && res.data.length) {
                            res.data.forEach(p => {
                                $pos.append(`<option value="${p.position_id}">${p.position_name}</option>`);
                            });
                        } else {
                            $pos.html('<option value="">No positions available</option>');
                        }
                    })
                    .fail(function () {
                        $pos.html('<option value="">Failed to load</option>');
                    })
                    .always(function () {
                        $pos.prop('disabled', false);
                    });
            });

            /* ── Photo preview on file select ───────────────────────── */
            $(document).on('change', '#photo', function () {
                const file = this.files[0];
                $('#photoPreview').remove();
                if (file) {
                    const url = URL.createObjectURL(file);
                    $(this).after(
                        `<div id="photoPreview" style="margin-top:6px;display:flex;align-items:center;gap:8px;">
                            <img src="${url}" style="width:36px;height:36px;border-radius:8px;object-fit:cover;border:1.5px solid #e5e7eb;">
                            <span style="font-size:.72rem;color:#9ca3af;">${file.name}</span>
                        </div>`
                    );
                }
            });

            /* ── Modal: Add ─────────────────────────────────────────── */
            $('#employeeModal').on('show.bs.modal', function(e) {
                if (e.relatedTarget && $(e.relatedTarget).is('#btnCreate')) {
                    isEdit = false;
                    resetForm();
                    $('#modalTitle').text('Add Employee');
                }
            });

            /* ── EDIT ───────────────────────────────────────────────── */
            $(document).on('click', '.btn-edit', function() {
                const id = $(this).data('id');
                isEdit = true;
                resetForm();
                $('#modalTitle').text('Edit Employee');
                $('#employeeModal').modal('show');

                $('#btnSave').prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin mr-1"></i> Loading...'
                );

                $.get(BASE_URL + '/' + id)
                    .done(function(res) {
                        if (res.success) {
                            const d = res.data;
                            $('#employee_id').val(d.employee_id);
                            $('#employee_code').val(d.employee_code);
                            $('#first_name').val(d.first_name);
                            $('#last_name').val(d.last_name);
                            $('#gender').val(d.gender);
                            $('#date_of_birth').val(d.date_of_birth ? d.date_of_birth.substring(0, 10) : '');
                            $('#phone').val(d.phone);
                            $('#email').val(d.email);
                            $('#address').val(d.address);
                            $('#hire_date').val(d.hire_date ? d.hire_date.substring(0, 10) : '');
                            // Show current photo preview
                            $('#photoPreview').remove();
                            if (d.photo) {
                                $('#photo').after(
                                    `<div id="photoPreview" style="margin-top:6px;display:flex;align-items:center;gap:8px;">
                                        <img src="/storage/${d.photo}" style="width:36px;height:36px;border-radius:8px;object-fit:cover;border:1.5px solid #e5e7eb;">
                                        <span style="font-size:.72rem;color:#9ca3af;">Current photo — upload new to replace</span>
                                    </div>`
                                );
                            }
                            $('#position_id').val(d.position_id);
                            $('#basic_salary').val(d.basic_salary);
                            $('#status').val(d.status);

                            // Load positions for this department, then restore saved position
                            const savedPositionId = d.position_id;
                            $.get(`/positions/by-department/${d.department_id}`)
                                .done(function (res) {
                                    const $pos = $('#position_id');
                                    $pos.html('<option value="">— Select Position —</option>');
                                    if (res.success && res.data.length) {
                                        res.data.forEach(p => {
                                            $pos.append(`<option value="${p.position_id}">${p.position_name}</option>`);
                                        });
                                    }
                                    $pos.val(savedPositionId);
                                    $('#department_id').val(d.department_id);
                                });
                        }
                    })
                    .fail(function() {
                    console.log('Status:', xhr.status);
                        toastr.error('Failed to load employee data.');
                        setTimeout(() => $('#employeeModal').modal('hide'), 1500);
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
                const id = $('#employee_id').val();
                const payload = new FormData();
                payload.append('_token', CSRF);
                payload.append('employee_code',  $('#employee_code').val().trim());
                payload.append('first_name',     $('#first_name').val().trim());
                payload.append('last_name',      $('#last_name').val().trim());
                payload.append('gender',         $('#gender').val());
                payload.append('date_of_birth',  $('#date_of_birth').val());
                payload.append('phone',          $('#phone').val().trim());
                payload.append('email',          $('#email').val().trim());
                payload.append('address',        $('#address').val().trim());
                payload.append('department_id',  $('#department_id').val());
                payload.append('position_id',    $('#position_id').val());
                payload.append('hire_date',      $('#hire_date').val());
                payload.append('basic_salary',   $('#basic_salary').val());
                payload.append('status',         $('#status').val());
                if ($('#photo')[0].files[0]) {
                    payload.append('photo', $('#photo')[0].files[0]);
                }
                if (isEdit && id) payload.append('_method', 'PUT');

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
                        data: payload,
                        processData: false,
                        contentType: false,
                    })
                    .done(function(res) {
                        if (res.success) {
                            $('#employeeModal').modal('hide');
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
                    .fail(function() {
                        toastr.error('Something went wrong. Please try again.');
                    })
                    .always(function() {
                        deleteId = null;
                        $btn.prop('disabled', false).html(
                            '<i class="fas fa-trash mr-1"></i> Delete'
                        );
                    });
            });

            /* ── Modal close: reset ─────────────────────────────────── */
            $('#employeeModal').on('hidden.bs.modal', resetForm);

            /* ── Helpers ────────────────────────────────────────────── */
            function resetForm() {
                $('#employee_id, #employee_code, #first_name, #last_name, #phone, #email, #address, #basic_salary, #date_of_birth, #hire_date').val('');
                $('#gender, #department_id, #position_id, #status').val('');
                $('#photo').val('');
                $('#photoPreview').remove();
                clearErrors();
            }

            function clearErrors() {
                ['employee_code','first_name','last_name','gender','date_of_birth','phone',
                 'email','address','photo','department_id','position_id','hire_date','basic_salary','status'
                ].forEach(function(f) {
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
