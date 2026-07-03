{{-- resources/views/offices/index.blade.php --}}
@extends('adminlte::page')

@section('title', 'Offices')

@section('content_header')
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h1 class="m-0 font-weight-bold" style="color:#1a1f36;font-size:1.4rem;letter-spacing:-0.3px;">
                <i class="fas fa-building mr-2" style="color:#4f46e5;"></i> Offices
            </h1>
            <ol class="breadcrumb mt-1 mb-0" style="background:transparent;padding:0;font-size:.8rem;">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" style="color:#4f46e5;">Home</a></li>
                <li class="breadcrumb-item active" style="color:#6b7280;">Offices</li>
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
                            <i class="fas fa-building" style="color:#fff;font-size:.85rem;"></i>
                        </div>
                        <div>
                            <h3 class="mb-0 font-weight-bold" style="font-size:1rem;color:#1a1f36;">Office Locations</h3>
                            <small style="color:#9ca3af;font-size:.75rem;">Manage attendance-check office sites</small>
                        </div>
                    </div>

                    <div class="d-flex align-items-center ml-auto mt-2" style="gap:10px;flex-wrap:wrap;">
                        <button type="button" id="btnAddOffice"
                            class="btn btn-sm"
                            style="background:linear-gradient(135deg,#4f46e5,#7c3aed);color:#fff;border:none;border-radius:10px;padding:8px 18px;font-weight:600;font-size:.82rem;letter-spacing:.2px;box-shadow:0 4px 14px rgba(79,70,229,.3);white-space:nowrap;">
                            <i class="fas fa-plus mr-1"></i> Add Office
                        </button>
                    </div>
                </div>

                <div class="card-body" style="padding:24px;background:#fafbff;">

                    {{-- Stats --}}
                    <div class="row mb-4">
                        <div class="col-6 col-md-3 mb-3 mb-md-0">
                            <div style="background:#fff;border-radius:12px;padding:16px 20px;border:1px solid #f0f0f5;display:flex;align-items:center;gap:14px;">
                                <div style="width:42px;height:42px;border-radius:10px;background:#eef2ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas fa-building" style="color:#4f46e5;"></i>
                                </div>
                                <div>
                                    <div class="font-weight-bold" id="statTotal" style="font-size:1.4rem;color:#1a1f36;line-height:1;">—</div>
                                    <div style="font-size:.72rem;color:#9ca3af;margin-top:2px;">Total Offices</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3 mb-md-0">
                            <div style="background:#fff;border-radius:12px;padding:16px 20px;border:1px solid #f0f0f5;display:flex;align-items:center;gap:14px;">
                                <div style="width:42px;height:42px;border-radius:10px;background:#ecfdf5;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas fa-check-circle" style="color:#10b981;"></i>
                                </div>
                                <div>
                                    <div class="font-weight-bold" id="statActive" style="font-size:1.4rem;color:#1a1f36;line-height:1;">—</div>
                                    <div style="font-size:.72rem;color:#9ca3af;margin-top:2px;">Active</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3 mb-md-0">
                            <div style="background:#fff;border-radius:12px;padding:16px 20px;border:1px solid #f0f0f5;display:flex;align-items:center;gap:14px;">
                                <div style="width:42px;height:42px;border-radius:10px;background:#fffbeb;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas fa-pause-circle" style="color:#f59e0b;"></i>
                                </div>
                                <div>
                                    <div class="font-weight-bold" id="statInactive" style="font-size:1.4rem;color:#1a1f36;line-height:1;">—</div>
                                    <div style="font-size:.72rem;color:#9ca3af;margin-top:2px;">Inactive</div>
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
                                    <div style="font-size:.72rem;color:#9ca3af;margin-top:2px;">Employees Assigned</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Filters Row --}}
                    <div class="d-flex flex-wrap mb-3" style="gap:10px;align-items:center;">

                        <div style="position:relative;min-width:200px;flex:1;">
                            <i class="fas fa-search" style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:.8rem;pointer-events:none;"></i>
                            <input type="text" class="form-control form-control-sm" placeholder="Search office name, code or address..."
                                id="searchInput" style="padding-left:32px;padding-right:32px;border-radius:10px;border:1.5px solid #e5e7eb;">
                            <i class="fas fa-times" id="clearSearch"
                                style="display:none;position:absolute;right:10px;top:50%;transform:translateY(-50%);cursor:pointer;color:#9ca3af;font-size:.8rem;"></i>
                        </div>

                        <div style="min-width:150px;">
                            <select id="filterStatus" class="form-control form-control-sm" style="border-radius:10px;border:1.5px solid #e5e7eb;color:#374151;">
                                <option value="">All Statuses</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>

                        <button type="button" id="btnReset"
                            style="background:#f3f4f6;color:#6b7280;border:none;border-radius:10px;padding:6px 14px;font-size:.82rem;font-weight:600;white-space:nowrap;">
                            <i class="fas fa-undo mr-1"></i> Reset
                        </button>

                    </div>

                    {{-- Table --}}
                    <div style="background:#fff;border-radius:12px;border:1px solid #f0f0f5;overflow:hidden;">
                        <div style="overflow-x:auto;-webkit-overflow-scrolling:touch;">
                            <table id="officesTable" class="table table-hover w-100 mb-0" style="min-width:960px;">
                                <thead>
                                    <tr style="background:#f8f9ff;">
                                        <th width="50"  style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">#</th>
                                        <th style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">Office</th>
                                        <th style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">Address</th>
                                        <th width="160" style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">Coordinates</th>
                                        <th width="110" style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">Radius</th>
                                        <th style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">Network</th>
                                        <th width="100" style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">Status</th>
                                        <th width="130" style="padding:14px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;">Actions</th>
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
         Add / Edit Modal
    ============================================================ --}}
    <div class="modal fade" id="officeModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="border:none;border-radius:16px;overflow:hidden;">
                <div class="modal-header" style="background:linear-gradient(135deg,#4f46e5,#7c3aed);border:none;padding:18px 24px;">
                    <h5 class="modal-title font-weight-bold" style="color:#fff;font-size:1rem;" id="officeModalTitle">
                        <i class="fas fa-building mr-2"></i> Add Office
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" style="color:#fff;opacity:.85;">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="officeForm">
                    <div class="modal-body" style="padding:24px;background:#fafbff;">
                        <input type="hidden" id="office_id" name="office_id">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold" style="font-size:.8rem;color:#374151;">Office Code <span style="color:#ef4444;">*</span></label>
                                <input type="text" class="form-control" id="office_code" name="office_code"
                                    style="border-radius:10px;border:1.5px solid #e5e7eb;" placeholder="e.g. HQ-01">
                                <div class="invalid-feedback" data-field="office_code"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold" style="font-size:.8rem;color:#374151;">Office Name <span style="color:#ef4444;">*</span></label>
                                <input type="text" class="form-control" id="office_name" name="office_name"
                                    style="border-radius:10px;border:1.5px solid #e5e7eb;" placeholder="e.g. Head Office">
                                <div class="invalid-feedback" data-field="office_name"></div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold" style="font-size:.8rem;color:#374151;">Address <span style="color:#ef4444;">*</span></label>
                            <textarea class="form-control" id="address" name="address" rows="2"
                                style="border-radius:10px;border:1.5px solid #e5e7eb;" placeholder="Full street address"></textarea>
                            <div class="invalid-feedback" data-field="address"></div>
                        </div>

                        <div class="row">
                            {{-- Left: form fields --}}
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="font-weight-bold mb-0" style="font-size:.8rem;color:#374151;">Coordinates</label>
                                    <button type="button" id="btnPickMap"
                                        style="background:#eef2ff;color:#4f46e5;border:none;border-radius:8px;padding:4px 10px;font-size:.72rem;font-weight:600;display:flex;align-items:center;gap:5px;">
                                        <i class="fas fa-location-crosshairs"></i> Use current location
                                    </button>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="font-weight-bold" style="font-size:.8rem;color:#374151;">Latitude <span style="color:#ef4444;">*</span></label>
                                        <input type="number" step="0.00000001" class="form-control" id="latitude" name="latitude"
                                            style="border-radius:10px;border:1.5px solid #e5e7eb;" placeholder="51.5074">
                                        <div class="invalid-feedback" data-field="latitude"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="font-weight-bold" style="font-size:.8rem;color:#374151;">Longitude <span style="color:#ef4444;">*</span></label>
                                        <input type="number" step="0.00000001" class="form-control" id="longitude" name="longitude"
                                            style="border-radius:10px;border:1.5px solid #e5e7eb;" placeholder="-0.1278">
                                        <div class="invalid-feedback" data-field="longitude"></div>
                                    </div>
                                </div>

                                <div class="form-group mb-0">
                                    <div class="d-flex justify-content-between">
                                        <label class="font-weight-bold" style="font-size:.8rem;color:#374151;">Allowed Radius (meters)</label>
                                        <span id="radiusValue" style="font-weight:700;color:#4f46e5;font-size:.85rem;">100m</span>
                                    </div>
                                    <input type="range" min="20" max="1000" step="10" class="custom-range" id="allowed_radius" name="allowed_radius" value="100">
                                    <div class="d-flex justify-content-between" style="font-size:.7rem;color:#9ca3af;">
                                        <span>20m (Strict)</span><span>1000m (Wide)</span>
                                    </div>
                                    <div class="invalid-feedback" data-field="allowed_radius"></div>
                                </div>
                            </div>

                            {{-- Right: map preview --}}
                            <div class="col-md-6 mb-3">
                                <div style="border:1px solid #e5e7eb;border-radius:12px;overflow:hidden;">
                                    <div style="background:#4b5563;color:#fff;font-size:.75rem;font-weight:600;padding:6px 12px;">Add/Edit Office</div>
                                    <div id="mapPreview" style="height:180px;position:relative;background:#eef1f4;">
                                        {{-- swap for a real map lib (Leaflet/Google Maps) bound to #latitude/#longitude --}}
                                    </div>
                                    <div style="padding:6px 12px;font-size:.72rem;color:#10b981;font-weight:600;display:flex;align-items:center;gap:6px;">
                                        <i class="fas fa-circle" style="font-size:.5rem;"></i> Live Preview Active
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold" style="font-size:.8rem;color:#374151;">Office IP <span style="color:#9ca3af;font-weight:400;">(optional)</span></label>
                                <input type="text" class="form-control" id="office_ip" name="office_ip"
                                    style="border-radius:10px;border:1.5px solid #e5e7eb;" placeholder="192.168.1.1">
                                <div class="invalid-feedback" data-field="office_ip"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold" style="font-size:.8rem;color:#374151;">WiFi Name <span style="color:#9ca3af;font-weight:400;">(optional)</span></label>
                                <input type="text" class="form-control" id="office_wifi_name" name="office_wifi_name"
                                    style="border-radius:10px;border:1.5px solid #e5e7eb;" placeholder="Office_WiFi_5G">
                                <div class="invalid-feedback" data-field="office_wifi_name"></div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold" style="font-size:.8rem;color:#374151;">Description <span style="color:#9ca3af;font-weight:400;">(optional)</span></label>
                            <textarea class="form-control" id="description" name="description" rows="2"
                                style="border-radius:10px;border:1.5px solid #e5e7eb;" placeholder="Notes about this location"></textarea>
                            <div class="invalid-feedback" data-field="description"></div>
                        </div>

                        <div class="form-group mb-0" id="statusWrapper" style="display:none;">
                            <label class="font-weight-bold" style="font-size:.8rem;color:#374151;">Status</label>
                            <select class="form-control" id="status" name="status" style="border-radius:10px;border:1.5px solid #e5e7eb;max-width:200px;">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer" style="background:#fff;border-top:1px solid #f0f0f5;padding:16px 24px;">
                        <button type="button" class="btn btn-sm" data-dismiss="modal"
                            style="background:#f3f4f6;color:#6b7280;border:none;border-radius:10px;padding:8px 18px;font-weight:600;font-size:.82rem;">
                            Cancel
                        </button>
                        <button type="submit" id="btnSaveOffice" class="btn btn-sm"
                            style="background:linear-gradient(135deg,#4f46e5,#7c3aed);color:#fff;border:none;border-radius:10px;padding:8px 22px;font-weight:600;font-size:.82rem;box-shadow:0 4px 14px rgba(79,70,229,.3);">
                            <i class="fas fa-save mr-1"></i> Save Office
                        </button>
                    </div>
                </form>
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
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <style>
        #officesTable tbody tr { transition: background .15s; }
        #officesTable tbody tr:hover { background: #f5f6ff !important; }
        #officesTable tbody td {
            padding: 13px 20px;
            vertical-align: middle;
            font-size: .875rem;
            color: #374151;
            border-color: #f3f4f6;
        }
        .badge-active {
            background: #ecfdf5; color: #059669;
            padding: 5px 14px; border-radius: 20px;
            font-size: .75rem; font-weight: 700;
            display: inline-flex; align-items: center; gap: 6px;
            white-space: nowrap; line-height: 1; cursor: pointer;
        }
        .badge-inactive {
            background: #fffbeb; color: #d97706;
            padding: 5px 14px; border-radius: 20px;
            font-size: .75rem; font-weight: 700;
            display: inline-flex; align-items: center; gap: 6px;
            white-space: nowrap; line-height: 1; cursor: pointer;
        }
        .row-num {
            width: 26px; height: 26px; border-radius: 6px;
            background: #f3f4f6; color: #6b7280;
            font-size: .75rem; font-weight: 700;
            display: inline-flex; align-items: center; justify-content: center;
        }
        .office-icon {
            width: 34px; height: 34px; border-radius: 10px;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; color: #fff; font-size: .8rem;
        }
        .office-code-badge {
            background: #eef2ff; color: #4f46e5;
            border-radius: 6px; padding: 2px 7px;
            font-size: .68rem; font-weight: 700; letter-spacing: .3px;
        }
        .action-btn {
            width: 30px; height: 30px; border-radius: 8px; border: none;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: .78rem; margin-right: 4px; transition: transform .1s;
        }
        .action-btn:hover { transform: translateY(-1px); }
        .btn-edit  { background: #eef2ff; color: #4f46e5; }
        .btn-delete{ background: #fef2f2; color: #ef4444; }
        .form-control:focus { border-color: #4f46e5 !important; box-shadow: 0 0 0 3px rgba(79,70,229,.12) !important; }
        .invalid-feedback { display: block; font-size: .75rem; min-height: 14px; }
        .dataTables_wrapper .dataTables_info { font-size:.78rem;color:#9ca3af;padding-top:14px;padding-left:4px; }
        .dataTables_wrapper .dataTables_paginate { padding-top:10px; }
        .dataTables_wrapper .dataTables_paginate .paginate_button { border-radius:8px!important;margin:0 2px;border:none!important;background:transparent!important;font-size:.82rem;color:#6b7280!important;padding:5px 11px!important; }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover { background:linear-gradient(135deg,#4f46e5,#7c3aed)!important;color:#fff!important;border:none!important;box-shadow:0 3px 10px rgba(79,70,229,.35); }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover { background:#eef2ff!important;color:#4f46e5!important; }
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover { color:#d1d5db!important; }
        #btnAddOffice:hover { transform:translateY(-1px); box-shadow:0 6px 20px rgba(79,70,229,.4)!important; }
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
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="{{ asset('js/modal-nav.js') }}"></script>

    <script>
    $(document).ready(function () {

        toastr.options = { closeButton:true, progressBar:true, positionClass:'toast-top-right', timeOut:3000 };

        const DATA_URL   = '{{ route("offices.data") }}';
        const STORE_URL  = '{{ route("offices.store") }}';
        const CSRF_TOKEN = '{{ csrf_token() }}';

        function updateUrl(id)   { return `{{ url('offices') }}/${id}`; }
        function toggleUrl(id)   { return `{{ url('offices') }}/${id}/toggle-status`; }

        /* ── Table ─────────────────────────────────────────── */
        const table = $('#officesTable').DataTable({
            processing: true,
            serverSide: false,
            dom: 't<"d-flex align-items-center justify-content-between px-1 pt-2"ip>',
            ajax: {
                url: DATA_URL,
                type: 'GET',
                dataSrc: function (json) {
                    const data = json.data || [];

                    $('#statTotal').text(data.length);
                    $('#statActive').text(data.filter(o => o.status === 'active').length);
                    $('#statInactive').text(data.filter(o => o.status === 'inactive').length);
                    $('#statEmployees').text(data.reduce((sum, o) => sum + (o.employees_count || 0), 0));

                    return data;
                },
                error: function () { toastr.error('Failed to load offices.'); }
            },
            language: {
                processing: '<i class="fas fa-spinner fa-spin mr-2" style="color:#4f46e5;"></i><span style="color:#4f46e5;">Loading...</span>',
                emptyTable: '<div style="padding:40px 0;color:#9ca3af;"><i class="fas fa-building" style="font-size:2rem;display:block;margin-bottom:10px;opacity:.4;"></i>No offices found.</div>',
                info: 'Showing _START_–_END_ of <strong>_TOTAL_</strong> offices',
                paginate: { previous: '<i class="fas fa-chevron-left"></i>', next: '<i class="fas fa-chevron-right"></i>' }
            },
            order: [[1, 'asc']],
            columns: [
                {
                    data: null, orderable: false, searchable: false,
                    render: (d, t, r, m) => `<span class="row-num">${m.row + m.settings._iDisplayStart + 1}</span>`
                },
                {
                    data: 'office_name',
                    render: (name, t, r) => `
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div class="office-icon"><i class="fas fa-building"></i></div>
                            <div>
                                <div style="font-weight:600;color:#1a1f36;font-size:.88rem;line-height:1.3;">${name}</div>
                                <span class="office-code-badge">${r.office_code ?? '—'}</span>
                            </div>
                        </div>`
                },
                {
                    data: 'address',
                    render: d => d
                        ? `<span style="color:#6b7280;font-size:.82rem;" title="${d}">${d.substring(0,40)}${d.length > 40 ? '…' : ''}</span>`
                        : '<span style="color:#d1d5db;">—</span>'
                },
                {
                    data: null,
                    render: (d, t, r) => `
                        <a href="https://maps.google.com/?q=${r.latitude},${r.longitude}" target="_blank"
                           style="color:#4f46e5;font-size:.78rem;text-decoration:none;font-family:monospace;">
                           <i class="fas fa-map-marker-alt mr-1"></i>${parseFloat(r.latitude).toFixed(5)}, ${parseFloat(r.longitude).toFixed(5)}
                        </a>`
                },
                {
                    data: 'allowed_radius',
                    render: d => `<span style="color:#6b7280;font-size:.82rem;">${d} m</span>`
                },
                {
                    data: null,
                    render: (d, t, r) => {
                        const ip   = r.office_ip ? `<div><i class="fas fa-network-wired mr-1" style="color:#9ca3af;"></i><span style="font-family:monospace;font-size:.78rem;">${r.office_ip}</span></div>` : '';
                        const wifi = r.office_wifi_name ? `<div><i class="fas fa-wifi mr-1" style="color:#9ca3af;"></i><span style="font-size:.78rem;">${r.office_wifi_name}</span></div>` : '';
                        return ip || wifi ? `<div style="color:#6b7280;">${ip}${wifi}</div>` : '<span style="color:#d1d5db;">—</span>';
                    }
                },
                {
                    data: 'status',
                    render: (status, t, r) => status === 'active'
                        ? `<span class="badge-active" data-toggle-id="${r.office_id}"><i class="fas fa-circle" style="font-size:.45rem;"></i> ACTIVE</span>`
                        : `<span class="badge-inactive" data-toggle-id="${r.office_id}"><i class="fas fa-circle" style="font-size:.45rem;"></i> INACTIVE</span>`
                },
                {
                    data: null, orderable: false, searchable: false,
                    render: (d, t, r) => `
                        <button class="action-btn btn-edit" data-edit-id="${r.office_id}" title="Edit"><i class="fas fa-pen"></i></button>
                        <button class="action-btn btn-delete" data-delete-id="${r.office_id}" data-name="${r.office_name}" title="Delete"><i class="fas fa-trash"></i></button>`
                },
            ]
        });

        /* ── Reverse geocode: lat/lng → address ──────────────── */
        let geocodeTimeout;
        function reverseGeocode(lat, lng) {
            clearTimeout(geocodeTimeout);
            geocodeTimeout = setTimeout(function () {
                $.ajax({
                    url: `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`,
                    method: 'GET',
                    success: function (res) {
                        if (res && res.display_name) {
                            $('#address').val(res.display_name);
                        }
                    },
                    error: function () {
                        // silent fail — user can still type address manually
                    }
                });
            }, 500); // debounce so we don't spam the API while dragging
        }

        /* ── Leaflet map preview ─────────────────────────────── */
        let previewMap, previewMarker, previewCircle;

        function initPreviewMap(lat, lng, radius) {
            lat = lat || 11.5564; lng = lng || 104.9282; // default center (Phnom Penh)
            radius = radius || 100;

            if (!previewMap) {
                previewMap = L.map('mapPreview', { zoomControl: false, attributionControl: false })
                    .setView([lat, lng], 16);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(previewMap);

                previewMarker = L.marker([lat, lng], { draggable: true }).addTo(previewMap);
                previewCircle = L.circle([lat, lng], {
                    radius: radius,
                    color: '#4f46e5',
                    fillColor: '#4f46e5',
                    fillOpacity: 0.15,
                    weight: 2,
                    dashArray: '4'
                }).addTo(previewMap);

                // Dragging the marker updates the form fields
                previewMarker.on('dragend', function (e) {
                    const pos = e.target.getLatLng();
                    $('#latitude').val(pos.lat.toFixed(8));
                    $('#longitude').val(pos.lng.toFixed(8));
                    previewCircle.setLatLng(pos);
                    reverseGeocode(pos.lat, pos.lng);
                });

                // Click anywhere on the map to move the pin
                previewMap.on('click', function (e) {
                    const { lat, lng } = e.latlng;
                    previewMarker.setLatLng([lat, lng]);
                    previewCircle.setLatLng([lat, lng]);
                    $('#latitude').val(lat.toFixed(8));
                    $('#longitude').val(lng.toFixed(8));
                    reverseGeocode(lat, lng);
                });
            } else {
                previewMap.setView([lat, lng], 16);
                previewMarker.setLatLng([lat, lng]);
                previewCircle.setLatLng([lat, lng]);
                previewCircle.setRadius(radius);
                setTimeout(() => previewMap.invalidateSize(), 200);
            }
        }

        function updatePreviewFromInputs() {
            const lat = parseFloat($('#latitude').val());
            const lng = parseFloat($('#longitude').val());
            const radius = parseFloat($('#allowed_radius').val()) || 100;
            if (!isNaN(lat) && !isNaN(lng)) {
                initPreviewMap(lat, lng, radius);
            }
        }

        // When the modal is shown, initialize the map with existing values or defaults
        $('#officeModal').on('shown.bs.modal', function () {
            const lat = parseFloat($('#latitude').val());
            const lng = parseFloat($('#longitude').val());
            const radius = parseFloat($('#allowed_radius').val()) || 100;

            if (!isNaN(lat) && !isNaN(lng)) {
                initPreviewMap(lat, lng, radius);       // has values (Edit mode)
            } else {
                initPreviewMap(null, null, radius);     // empty (Add mode) → uses default center
            }
        });

        // Live-update circle radius on slider change
        $('#allowed_radius').on('input', function () {
            if (previewCircle) previewCircle.setRadius(parseFloat($(this).val()));
        });

        // Live-update marker on manual lat/lng typing
        $('#latitude, #longitude').on('input', function () {
            updatePreviewFromInputs();
        });

        /* ── Use current location ───────────────────────────── */
        $('#btnPickMap').on('click', function () {
            if (!navigator.geolocation) {
                toastr.warning('Geolocation is not supported by your browser.');
                return;
            }

            const btn = $(this);
            const icon = btn.find('i');
            icon.removeClass('fa-location-crosshairs').addClass('fa-spinner fa-spin');
            btn.prop('disabled', true);

            navigator.geolocation.getCurrentPosition(
                function (pos) {
                    $('#latitude').val(pos.coords.latitude.toFixed(8));
                    $('#longitude').val(pos.coords.longitude.toFixed(8));
                    updatePreviewFromInputs();
                    reverseGeocode(pos.coords.latitude, pos.coords.longitude);
                    icon.removeClass('fa-spinner fa-spin').addClass('fa-location-crosshairs');
                    btn.prop('disabled', false);
                    toastr.success('Current location captured.');
                },
                function (err) {
                    icon.removeClass('fa-spinner fa-spin').addClass('fa-location-crosshairs');
                    btn.prop('disabled', false);
                    toastr.warning('Unable to get your location. Please check browser permissions.');
                },
                { enableHighAccuracy: true, timeout: 8000 }
            );
        });

        /* ── Search / filter ───────────────────────────────── */
        $.fn.dataTable.ext.search.push(function (settings, data, dataIndex, rowData) {
            const q = $('#searchInput').val().toLowerCase().trim();
            const statusVal = $('#filterStatus').val();

            if (statusVal && rowData.status !== statusVal) return false;

            if (q) {
                const haystack = `${rowData.office_name} ${rowData.office_code} ${rowData.address}`.toLowerCase();
                if (!haystack.includes(q)) return false;
            }

            return true;
        });

        $('#searchInput').on('keyup', function () {
            $('#clearSearch').toggle($(this).val().length > 0);
            table.draw();
        });
        $('#clearSearch').on('click', function () { $('#searchInput').val(''); $(this).hide(); table.draw(); });
        $('#filterStatus').on('change', () => table.draw());
        $('#btnReset').on('click', function () {
            $('#searchInput').val(''); $('#clearSearch').hide();
            $('#filterStatus').val(''); table.draw();
        });

        /* ── Modal: add ────────────────────────────────────── */
        function resetForm() {
            $('#officeForm')[0].reset();
            $('#office_id').val('');
            $('.invalid-feedback').text('');
            $('.form-control').removeClass('is-invalid');
            $('#statusWrapper').hide();
            $('#allowed_radius').val(100).trigger('input'); 
        }

        $('#btnAddOffice').on('click', function () {
            resetForm();
            $('#officeModalTitle').html('<i class="fas fa-building mr-2"></i> Add Office');
            $('#officeModal').modal('show');
        });

        /* ── Radius live label ─────────────────────────────── */
        $('#allowed_radius').on('input', function () {
            $('#radiusValue').text($(this).val() + 'm');
        });

        /* ── Modal: edit ───────────────────────────────────── */
        $('#officesTable').on('click', '[data-edit-id]', function () {
            const id = $(this).data('edit-id');
            const row = table.rows().data().toArray().find(o => o.office_id == id);
            if (!row) return;

            resetForm();
            $('#officeModalTitle').html('<i class="fas fa-pen mr-2"></i> Edit Office');
            $('#office_id').val(row.office_id);
            $('#office_code').val(row.office_code);
            $('#office_name').val(row.office_name);
            $('#address').val(row.address);
            $('#latitude').val(row.latitude);
            $('#longitude').val(row.longitude);
            $('#allowed_radius').val(row.allowed_radius);
            $('#radiusValue').text(row.allowed_radius + 'm');
            $('#office_ip').val(row.office_ip);
            $('#office_wifi_name').val(row.office_wifi_name);
            $('#description').val(row.description);
            $('#status').val(row.status);
            $('#statusWrapper').show();
            

            $('#officeModal').modal('show');
        });

        /* ── Save (create / update) ────────────────────────── */
        $('#officeForm').on('submit', function (e) {
            e.preventDefault();

            const id = $('#office_id').val();
            const isEdit = !!id;
            const url = isEdit ? updateUrl(id) : STORE_URL;

            const payload = {
                office_code: $('#office_code').val(),
                office_name: $('#office_name').val(),
                address: $('#address').val(),
                latitude: $('#latitude').val(),
                longitude: $('#longitude').val(),
                allowed_radius: $('#allowed_radius').val(),
                office_ip: $('#office_ip').val(),
                office_wifi_name: $('#office_wifi_name').val(),
                description: $('#description').val(),
            };
            if (isEdit) payload.status = $('#status').val();
            if (isEdit) payload._method = 'PUT';

            $('.invalid-feedback').text('');
            $('.form-control').removeClass('is-invalid');
            $('#btnSaveOffice').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Saving...');

            $.ajax({
                url: url,
                method: 'POST',
                data: payload,
                headers: { 'X-CSRF-TOKEN': CSRF_TOKEN },
                success: function (res) {
                    toastr.success(res.message || 'Office saved successfully.');
                    $('#officeModal').modal('hide');
                    table.ajax.reload(null, false);
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors || {};
                        Object.keys(errors).forEach(field => {
                            $(`[data-field="${field}"]`).text(errors[field][0]);
                            $(`#${field}`).addClass('is-invalid');
                        });
                    } else if (xhr.status === 409) {
                        toastr.warning(xhr.responseJSON.message || 'This action is blocked by a business rule.');
                    } else {
                        toastr.error('Something went wrong while saving the office.');
                    }
                },
                complete: function () {
                    $('#btnSaveOffice').prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Save Office');
                }
            });
        });

        /* ── Toggle status ──────────────────────────────────── */
        $('#officesTable').on('click', '[data-toggle-id]', function () {
            const id = $(this).data('toggle-id');

            $.ajax({
                url: toggleUrl(id),
                method: 'PATCH',
                headers: { 'X-CSRF-TOKEN': CSRF_TOKEN },
                success: function (res) {
                    toastr.success(res.message || 'Status updated.');
                    table.ajax.reload(null, false);
                },
                error: function (xhr) {
                    toastr.warning(xhr.responseJSON?.message || 'Unable to change status.');
                }
            });
        });

        /* ── Delete ─────────────────────────────────────────── */
        $('#officesTable').on('click', '[data-delete-id]', function () {
            const id = $(this).data('delete-id');
            const name = $(this).data('name');

            if (!confirm(`Delete office "${name}"? This will only work if no employees or attendance logs are assigned.`)) return;

            $.ajax({
                url: updateUrl(id),
                method: 'POST',
                data: { _method: 'DELETE' },
                headers: { 'X-CSRF-TOKEN': CSRF_TOKEN },
                success: function (res) {
                    toastr.success(res.message || 'Office deleted successfully.');
                    table.ajax.reload(null, false);
                },
                error: function (xhr) {
                    toastr.warning(xhr.responseJSON?.message || 'Unable to delete this office.');
                }
            });
        });

    });
    </script>
@stop