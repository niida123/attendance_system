{{-- resources/views/dashboard.blade.php --}}
@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h1 class="m-0 font-weight-bold" style="color:#1a1f36;font-size:1.4rem;letter-spacing:-0.3px;">
                <i class="fas fa-chart-line mr-2" style="color:#4f46e5;"></i> Dashboard
            </h1>
            <ol class="breadcrumb mt-1 mb-0" style="background:transparent;padding:0;font-size:.8rem;">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" style="color:#4f46e5;">Home</a></li>
                <li class="breadcrumb-item active" style="color:#6b7280;">Dashboard</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
<div id="dashboard-root">

    {{-- Loading --}}
    <div id="dash-loading" class="text-center py-5">
        <i class="fas fa-spinner fa-spin" style="color:#4f46e5;font-size:1.6rem;"></i>
        <p class="mt-2" style="color:#9ca3af;font-size:.85rem;">Loading dashboard…</p>
    </div>

    {{-- ============================ ADMIN VIEW ============================ --}}
    <div id="admin-view" class="d-none">

        <div class="d-flex justify-content-end mb-3">
            <button id="btnPrintDashboard" class="btn btn-sm" style="height:36px;border-radius:10px;border:1.5px solid #e5e7eb;background:#fff;color:#6b7280;font-weight:600;font-size:.8rem;padding:0 14px;">
                <i class="fas fa-print mr-1"></i> Print
            </button>
        </div>

        <div id="holiday-banner" class="d-none d-flex align-items-center mb-3" style="background:#eef2ff;border:1px solid #e0e7ff;border-radius:12px;padding:12px 18px;">
            <i class="fas fa-umbrella-beach mr-2" style="color:#4f46e5;"></i>
            <span id="holiday-text" style="color:#374151;font-size:.85rem;"></span>
        </div>

        {{-- Stats Row --}}
        <div class="row mb-4 g-3" id="statsRow">
            <div class="col-6 col-lg-2">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#eef2ff;"><i class="fas fa-users" style="color:#4f46e5;"></i></div>
                    <div><div class="stat-num" id="stat-total">—</div><div class="stat-lbl">Total Employees</div></div>
                </div>
            </div>
            <div class="col-6 col-lg-2">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#ecfdf5;"><i class="fas fa-check-circle" style="color:#10b981;"></i></div>
                    <div><div class="stat-num" id="stat-present">—</div><div class="stat-lbl">Present Today</div></div>
                </div>
            </div>
            <div class="col-6 col-lg-2">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#fef2f2;"><i class="fas fa-user-times" style="color:#ef4444;"></i></div>
                    <div><div class="stat-num" id="stat-absent">—</div><div class="stat-lbl">Absent Today</div></div>
                </div>
            </div>
            <div class="col-6 col-lg-2">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#fffbeb;"><i class="fas fa-clock" style="color:#f59e0b;"></i></div>
                    <div><div class="stat-num" id="stat-late">—</div><div class="stat-lbl">Late Today</div></div>
                </div>
            </div>
            <div class="col-6 col-lg-2">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#f3f4f6;"><i class="fas fa-plane-departure" style="color:#6b7280;"></i></div>
                    <div><div class="stat-num" id="stat-leave">—</div><div class="stat-lbl">On Leave Today</div></div>
                </div>
            </div>
            <div class="col-6 col-lg-2">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#f5f3ff;"><i class="fas fa-inbox" style="color:#7c3aed;"></i></div>
                    <div><div class="stat-num" id="stat-pending">—</div><div class="stat-lbl">Pending Leaves</div></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="panel-card mb-4">
                    <div class="panel-header">
                        <div class="panel-icon" style="background:linear-gradient(135deg,#4f46e5,#7c3aed);">
                            <i class="fas fa-chart-bar" style="color:#fff;font-size:.85rem;"></i>
                        </div>
                        <div>
                            <h3 class="panel-title">Attendance Trend</h3>
                            <small class="panel-sub">Last 6 months</small>
                        </div>
                    </div>
                    <div class="panel-body">
                        <canvas id="trendChart" height="110"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="panel-card mb-4">
                    <div class="panel-header">
                        <div class="panel-icon" style="background:linear-gradient(135deg,#06b6d4,#3b82f6);">
                            <i class="fas fa-building" style="color:#fff;font-size:.85rem;"></i>
                        </div>
                        <div>
                            <h3 class="panel-title">Department Breakdown</h3>
                            <small class="panel-sub">Active employees</small>
                        </div>
                    </div>
                    <div class="panel-body">
                        <canvas id="deptChart" height="230"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="panel-card mb-4">
                    <div class="panel-header">
                        <div class="panel-icon" style="background:linear-gradient(135deg,#ef4444,#dc2626);">
                            <i class="fas fa-user-times" style="color:#fff;font-size:.85rem;"></i>
                        </div>
                        <div>
                            <h3 class="panel-title">Absent Today</h3>
                            <small class="panel-sub">Not yet checked in</small>
                        </div>
                    </div>
                    <ul class="list-group list-group-flush" id="absent-list" style="border-top:1px solid #f0f0f5;"></ul>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="panel-card mb-4">
                    <div class="panel-header">
                        <div class="panel-icon" style="background:linear-gradient(135deg,#f59e0b,#f97316);">
                            <i class="fas fa-inbox" style="color:#fff;font-size:.85rem;"></i>
                        </div>
                        <div>
                            <h3 class="panel-title">Pending Leave Requests</h3>
                            <small class="panel-sub">Awaiting approval</small>
                        </div>
                    </div>
                    <ul class="list-group list-group-flush" id="pending-list" style="border-top:1px solid #f0f0f5;"></ul>
                </div>
            </div>
        </div>

    </div>

    {{-- ========================== EMPLOYEE VIEW =========================== --}}
    <div id="employee-view" class="d-none">

        <div class="panel-card mb-4">
            <div class="panel-body d-flex align-items-center flex-wrap" style="gap:16px;">
                <img id="emp-photo" src="" class="d-none" style="width:72px;height:72px;border-radius:50%;object-fit:cover;border:3px solid #eef2ff;">
                <div id="emp-initials" class="d-none" style="width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,#4f46e5,#7c3aed);color:#fff;font-weight:700;font-size:1.3rem;display:flex;align-items:center;justify-content:center;"></div>
                <div>
                    <h4 class="mb-0 font-weight-bold" id="emp-name" style="color:#1a1f36;">—</h4>
                    <span style="color:#9ca3af;font-size:.85rem;" id="emp-position">—</span>
                    <span style="color:#d1d5db;">&middot;</span>
                    <span style="color:#9ca3af;font-size:.85rem;" id="emp-department">—</span>
                    <div class="mt-2">
                        <span class="badge-info-pill" id="emp-shift">No shift assigned</span>
                    </div>
                </div>
            </div>
        </div>

        <div id="no-employee-alert" class="d-none mb-4" style="background:#fffbeb;border:1px solid #fde68a;border-radius:12px;padding:14px 18px;color:#92400e;font-size:.85rem;">
            <i class="fas fa-exclamation-triangle mr-1"></i> No employee profile is linked to your account.
        </div>

        <div class="row mb-4 g-3">
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#ecfdf5;"><i class="fas fa-sign-in-alt" style="color:#10b981;"></i></div>
                    <div><div class="stat-num" id="today-checkin" style="font-size:1.1rem;">—</div><div class="stat-lbl">Check In</div></div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#fef2f2;"><i class="fas fa-sign-out-alt" style="color:#ef4444;"></i></div>
                    <div><div class="stat-num" id="today-checkout" style="font-size:1.1rem;">—</div><div class="stat-lbl">Check Out</div></div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#eef2ff;"><i class="fas fa-hourglass-half" style="color:#4f46e5;"></i></div>
                    <div><div class="stat-num" id="today-hours" style="font-size:1.1rem;">—</div><div class="stat-lbl">Working Hours</div></div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#fffbeb;"><i class="fas fa-flag" style="color:#f59e0b;"></i></div>
                    <div><div class="stat-num" id="today-status" style="font-size:1.1rem;">—</div><div class="stat-lbl">Status</div></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">
                <div class="panel-card mb-4">
                    <div class="panel-header">
                        <div class="panel-icon" style="background:linear-gradient(135deg,#10b981,#059669);">
                            <i class="fas fa-calendar-check" style="color:#fff;font-size:.85rem;"></i>
                        </div>
                        <h3 class="panel-title">This Month</h3>
                    </div>
                    <ul class="list-group list-group-flush" style="border-top:1px solid #f0f0f5;">
                        <li class="list-group-item d-flex justify-content-between summary-row">Present <span class="pill-success" id="m-present">0</span></li>
                        <li class="list-group-item d-flex justify-content-between summary-row">Late <span class="pill-warning" id="m-late">0</span></li>
                        <li class="list-group-item d-flex justify-content-between summary-row">Absent <span class="pill-danger" id="m-absent">0</span></li>
                        <li class="list-group-item d-flex justify-content-between summary-row">On Leave <span class="pill-secondary" id="m-leave">0</span></li>
                        <li class="list-group-item d-flex justify-content-between summary-row">Total Hours <span class="pill-info" id="m-hours">0</span></li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="panel-card mb-4">
                    <div class="panel-header">
                        <div class="panel-icon" style="background:linear-gradient(135deg,#06b6d4,#3b82f6);">
                            <i class="fas fa-balance-scale" style="color:#fff;font-size:.85rem;"></i>
                        </div>
                        <h3 class="panel-title">Leave Balance ({{ date('Y') }})</h3>
                    </div>
                    <ul class="list-group list-group-flush" id="leave-balance-list" style="border-top:1px solid #f0f0f5;"></ul>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="panel-card mb-4">
                    <div class="panel-header">
                        <div class="panel-icon" style="background:linear-gradient(135deg,#6b7280,#374151);">
                            <i class="fas fa-history" style="color:#fff;font-size:.85rem;"></i>
                        </div>
                        <h3 class="panel-title">Recent Attendance</h3>
                    </div>
                    <div class="panel-body p-0">
                        <table class="table table-sm mb-0" id="recentTable">
                            <thead>
                                <tr style="background:#f8f9ff;">
                                    <th>Date</th><th>In</th><th>Out</th><th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="recent-table-body"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

@section('css')
<style>
    .stat-card {
        background:#fff;border-radius:12px;padding:16px 18px;border:1px solid #f0f0f5;
        display:flex;align-items:center;gap:14px;height:100%;
    }
    .stat-icon {
        width:42px;height:42px;border-radius:10px;display:flex;align-items:center;
        justify-content:center;flex-shrink:0;font-size:1rem;
    }
    .stat-num { font-weight:700;font-size:1.4rem;color:#1a1f36;line-height:1; }
    .stat-lbl { font-size:.75rem;color:#9ca3af;margin-top:2px; }

    .panel-card {
        background:#fff;border:none;border-radius:16px;
        box-shadow:0 1px 3px rgba(0,0,0,.08),0 8px 32px rgba(79,70,229,.07);
        overflow:hidden;
    }
    .panel-header {
        display:flex;align-items:center;gap:12px;
        padding:18px 22px;border-bottom:1px solid #f0f0f5;
    }
    .panel-icon {
        width:36px;height:36px;border-radius:10px;display:flex;
        align-items:center;justify-content:center;flex-shrink:0;
    }
    .panel-title { margin:0;font-size:1rem;font-weight:700;color:#1a1f36; }
    .panel-sub { color:#9ca3af;font-size:.75rem; }
    .panel-body { padding:20px 22px; }

    #absent-list .list-group-item, #pending-list .list-group-item {
        padding:14px 22px;border-color:#f3f4f6;
    }
    #absent-list .list-group-item:hover, #pending-list .list-group-item:hover { background:#fafbff; }

    .avatar-circle {
        width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;
        font-weight:700;font-size:.75rem;color:#fff;flex-shrink:0;
        background:linear-gradient(135deg,#4f46e5,#7c3aed);
    }

    .badge-info-pill {
        background:#eef2ff;color:#4f46e5;padding:4px 12px;border-radius:20px;
        font-size:.72rem;font-weight:600;
    }
    .pill-success  { background:#ecfdf5;color:#059669;padding:3px 10px;border-radius:20px;font-size:.75rem;font-weight:700; }
    .pill-warning  { background:#fffbeb;color:#d97706;padding:3px 10px;border-radius:20px;font-size:.75rem;font-weight:700; }
    .pill-danger   { background:#fef2f2;color:#dc2626;padding:3px 10px;border-radius:20px;font-size:.75rem;font-weight:700; }
    .pill-secondary{ background:#f3f4f6;color:#6b7280;padding:3px 10px;border-radius:20px;font-size:.75rem;font-weight:700; }
    .pill-info     { background:#eff6ff;color:#2563eb;padding:3px 10px;border-radius:20px;font-size:.75rem;font-weight:700; }

    .summary-row { font-size:.85rem;color:#374151;padding:12px 22px; }

    #recentTable thead th {
        padding:12px 16px;font-size:.7rem;font-weight:700;text-transform:uppercase;
        letter-spacing:.5px;color:#6b7280;border-bottom:2px solid #eef0f8;border-top:none;
    }
    #recentTable tbody td { padding:11px 16px;font-size:.82rem;color:#374151;border-color:#f3f4f6; }

    .badge-status-Present { background:#ecfdf5;color:#059669; }
    .badge-status-Late    { background:#fffbeb;color:#d97706; }
    .badge-status-Absent  { background:#fef2f2;color:#dc2626; }
    .badge-status-Leave   { background:#f3f4f6;color:#6b7280; }
    .badge-status-pill {
        padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700;
    }

    @media (max-width: 992px) {
        #statsRow .col-lg-2 { flex:0 0 50%;max-width:50%;margin-bottom:12px; }
    }
    @media (max-width: 576px) {
        .panel-body, .panel-header { padding-left:16px !important; padding-right:16px !important; }
        .stat-num { font-size:1.15rem !important; }
    }
</style>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('turbo:load', function () {
    loadDashboard();
});

function loadDashboard() {

    console.log('loadDashboard called');

    fetch('/dashboard/data', {
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(res => {
        console.log('fetch status:', res.status);
        return res.json();
    })
    .then(data => {
        console.log('dashboard data:', data);

        document.getElementById('btnPrintDashboard')?.addEventListener('click', () => printDashboard(data));
        document.getElementById('dash-loading').classList.add('d-none');

        if (data.role === 'admin') {
            renderAdmin(data);
        } else {
            renderEmployee(data);
        }
    })
    .catch(err => {
        console.error('Dashboard error:', err);
    });
} 

    function renderAdmin(data) {
        document.getElementById('admin-view').classList.remove('d-none');

        if (data.holiday) {
            document.getElementById('holiday-banner').classList.remove('d-none');
            document.getElementById('holiday-text').textContent = "Today is a holiday: " + data.holiday;
        }

        const s = data.stats;
        document.getElementById('stat-total').textContent   = s.total_employees;
        document.getElementById('stat-present').textContent = s.present_today;
        document.getElementById('stat-absent').textContent  = s.absent_today;
        document.getElementById('stat-late').textContent    = s.late_today;
        document.getElementById('stat-leave').textContent   = s.on_leave_today;
        document.getElementById('stat-pending').textContent = s.pending_leaves;

        new Chart(document.getElementById('trendChart'), {
            type: 'bar',
            data: {
                labels: data.trend.map(t => t.month),
                datasets: [
                    { label: 'Present', data: data.trend.map(t => t.present), backgroundColor: '#10b981', borderRadius: 6 },
                    { label: 'Late',    data: data.trend.map(t => t.late),    backgroundColor: '#f59e0b', borderRadius: 6 },
                    { label: 'Absent',  data: data.trend.map(t => t.absent),  backgroundColor: '#ef4444', borderRadius: 6 },
                ]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 11 } } } },
                scales: { y: { beginAtZero: true, ticks: { precision: 0 } }, x: { grid: { display: false } } }
            }
        });

        new Chart(document.getElementById('deptChart'), {
            type: 'doughnut',
            data: {
                labels: data.dept_breakdown.map(d => d.name),
                datasets: [{
                    data: data.dept_breakdown.map(d => d.count),
                    backgroundColor: ['#4f46e5','#7c3aed','#06b6d4','#10b981','#f59e0b','#ef4444','#ec4899','#3b82f6'],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 11 } } } },
                cutout: '65%'
            }
        });

        const absentEl = document.getElementById('absent-list');
        absentEl.innerHTML = data.absent_list.length ? '' :
            '<li class="list-group-item text-center" style="color:#9ca3af;font-size:.85rem;">No one is absent today 🎉</li>';
        data.absent_list.forEach(e => {
            absentEl.innerHTML += `
                <li class="list-group-item d-flex align-items-center" style="gap:10px;">
                    ${e.photo
                        ? `<img src="${e.photo}" class="avatar-circle" style="object-fit:cover;background:none;">`
                        : `<span class="avatar-circle">${e.initials}</span>`
                    }
                    <div>
                        <div style="font-weight:600;color:#1a1f36;font-size:.85rem;">${e.name}</div>
                        <div style="color:#9ca3af;font-size:.72rem;">${e.position} — ${e.department}</div>
                    </div>
                </li>`;
        });

        const pendingEl = document.getElementById('pending-list');
        pendingEl.innerHTML = data.pending_list.length ? '' :
            '<li class="list-group-item text-center" style="color:#9ca3af;font-size:.85rem;">No pending leave requests</li>';
        data.pending_list.forEach(r => {
            pendingEl.innerHTML += `
                <li class="list-group-item d-flex align-items-center" style="gap:10px;">
                    ${r.photo
                        ? `<img src="${r.photo}" class="avatar-circle" style="object-fit:cover;background:none;">`
                        : `<span class="avatar-circle">${r.initials}</span>`
                    }
                    <div>
                        <div style="font-weight:600;color:#1a1f36;font-size:.85rem;">${r.name}</div>
                        <div style="color:#9ca3af;font-size:.72rem;">${r.leave_type} · ${r.start_date} → ${r.end_date} (${r.total_days}d)</div>
                    </div>
                </li>`;
        });
    }

    function renderEmployee(data) {
        document.getElementById('employee-view').classList.remove('d-none');

        if (data.no_employee) {
            document.getElementById('no-employee-alert').classList.remove('d-none');
            return;
        }

        const emp = data.employee;
        document.getElementById('emp-name').textContent = emp.name;
        document.getElementById('emp-position').textContent = emp.position;
        document.getElementById('emp-department').textContent = emp.department;
        if (emp.photo) {
            document.getElementById('emp-photo').src = emp.photo;
            document.getElementById('emp-photo').classList.remove('d-none');
        } else {
            document.getElementById('emp-initials').textContent = emp.initials;
            document.getElementById('emp-initials').classList.remove('d-none');
        }

        if (data.shift) {
            document.getElementById('emp-shift').textContent =
                `${data.shift.shift_name ?? 'Shift'} (${data.shift.start_time ?? ''} - ${data.shift.end_time ?? ''})`;
        }

        const att = data.today_attendance;
        document.getElementById('today-checkin').textContent  = att?.check_in  ?? '—';
        document.getElementById('today-checkout').textContent = att?.check_out ?? '—';
        document.getElementById('today-hours').textContent    = att?.working_hours ? att.working_hours + ' hrs' : '—';
        document.getElementById('today-status').textContent   = att?.status ?? 'Not checked in';

        const m = data.monthly_stats;
        document.getElementById('m-present').textContent = m?.present ?? 0;
        document.getElementById('m-late').textContent    = m?.late ?? 0;
        document.getElementById('m-absent').textContent  = m?.absent ?? 0;
        document.getElementById('m-leave').textContent   = m?.on_leave ?? 0;
        document.getElementById('m-hours').textContent   = m?.total_hours ?? 0;

        const balEl = document.getElementById('leave-balance-list');
        balEl.innerHTML = data.leave_balance.length ? '' :
            '<li class="list-group-item text-center" style="color:#9ca3af;font-size:.85rem;padding:14px 22px;">No leave taken yet</li>';
        data.leave_balance.forEach(b => {
            const pct = b.max > 0 ? Math.min(100, Math.round((b.used / b.max) * 100)) : 0;
            balEl.innerHTML += `
                <li class="list-group-item" style="padding:14px 22px;">
                    <div class="d-flex justify-content-between mb-1" style="font-size:.83rem;color:#374151;">
                        <span>${b.type}</span>
                        <span style="font-weight:600;">${b.used} / ${b.max} days</span>
                    </div>
                    <div style="background:#f3f4f6;border-radius:20px;height:6px;overflow:hidden;">
                        <div style="background:linear-gradient(135deg,#4f46e5,#7c3aed);height:100%;width:${pct}%;"></div>
                    </div>
                </li>`;
        });

        const tbody = document.getElementById('recent-table-body');
        tbody.innerHTML = data.recent.length ? '' :
            '<tr><td colspan="4" class="text-center" style="color:#9ca3af;padding:20px;">No records</td></tr>';
        data.recent.forEach(r => {
            tbody.innerHTML += `
                <tr>
                    <td>${r.date}</td>
                    <td>${r.check_in ?? '—'}</td>
                    <td>${r.check_out ?? '—'}</td>
                    <td><span class="badge-status-pill badge-status-${r.status}">${r.status}</span></td>
                </tr>`;
        });
    }
</script>
@endsection