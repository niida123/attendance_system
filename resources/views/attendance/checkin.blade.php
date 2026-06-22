{{-- resources/views/attendance/checkin.blade.php --}}
@extends('adminlte::page')

@section('title', 'Check In / Out - Attendance System')

@section('content_header')
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h1 class="m-0 font-weight-bold" style="color:#1a1f36;font-size:1.4rem;letter-spacing:-0.3px;">
                <i class="fas fa-fingerprint mr-2" style="color:#4f46e5;"></i> My Attendance
            </h1>
            <ol class="breadcrumb mt-1 mb-0" style="background:transparent;padding:0;font-size:.8rem;">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" style="color:#4f46e5;">Home</a></li>
                <li class="breadcrumb-item active" style="color:#6b7280;">My Attendance</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-10">

        {{-- ── Top: Date / Clock / Shift ─────────────────────────────── --}}
        <div class="row mb-4">

            {{-- Live Clock --}}
            <div class="col-md-4 mb-3 mb-md-0">
                <div style="background:#fff;border-radius:16px;border:1px solid #f0f0f5;padding:24px 20px;text-align:center;height:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;">
                    <div style="font-size:2.6rem;font-weight:800;color:#1a1f36;letter-spacing:-1px;line-height:1;" id="liveClock">--:--:--</div>
                    <div style="font-size:.82rem;color:#9ca3af;margin-top:6px;" id="liveDate">—</div>
                    <div style="margin-top:12px;">
                        <span style="background:#eef2ff;color:#4f46e5;border-radius:20px;padding:4px 14px;font-size:.75rem;font-weight:600;">
                            <i class="fas fa-map-marker-alt mr-1"></i> Office
                        </span>
                    </div>
                </div>
            </div>

            {{-- Shift Info --}}
            <div class="col-md-4 mb-3 mb-md-0">
                <div style="background:#fff;border-radius:16px;border:1px solid #f0f0f5;padding:24px 20px;height:100%;">
                    <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#9ca3af;margin-bottom:12px;">
                        <i class="fas fa-business-time mr-1"></i> Today's Shift
                    </div>
                    <div id="shiftInfo">
                        <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
                            <div style="width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,#4f46e5,#7c3aed);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="fas fa-clock" style="color:#fff;font-size:.85rem;"></i>
                            </div>
                            <div>
                                <div id="shiftName" style="font-weight:700;color:#1a1f36;font-size:.95rem;">—</div>
                                <div id="shiftTime" style="font-size:.78rem;color:#9ca3af;">—</div>
                            </div>
                        </div>
                        <div style="display:flex;gap:8px;flex-wrap:wrap;">
                            <span style="background:#f3f4f6;border-radius:8px;padding:5px 10px;font-size:.75rem;color:#6b7280;">
                                <i class="fas fa-hourglass-half mr-1" style="color:#4f46e5;"></i>
                                <span id="shiftHours">— hrs</span>
                            </span>
                            <span style="background:#fef3c7;border-radius:8px;padding:5px 10px;font-size:.75rem;color:#d97706;">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                Late after <span id="shiftLate">—</span> min
                            </span>
                        </div>
                    </div>
                    <div id="shiftEmpty" style="display:none;color:#9ca3af;font-size:.85rem;text-align:center;padding:10px 0;">
                        <i class="fas fa-calendar-times" style="font-size:1.4rem;opacity:.4;display:block;margin-bottom:6px;"></i>
                        No shift assigned for today.
                    </div>
                </div>
            </div>

            {{-- Today Summary --}}
            <div class="col-md-4 mb-3 mb-md-0">
                <div style="background:#fff;border-radius:16px;border:1px solid #f0f0f5;padding:24px 20px;height:100%;">
                    <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#9ca3af;margin-bottom:14px;">
                        <i class="fas fa-chart-bar mr-1"></i> Today's Summary
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <div style="text-align:center;">
                            <div style="font-size:.7rem;color:#9ca3af;margin-bottom:4px;">Check In</div>
                            <div id="summaryCheckIn" style="font-weight:700;color:#059669;font-size:1rem;">—</div>
                        </div>
                        <div style="text-align:center;">
                            <div style="font-size:.7rem;color:#9ca3af;margin-bottom:4px;">Check Out</div>
                            <div id="summaryCheckOut" style="font-weight:700;color:#ef4444;font-size:1rem;">—</div>
                        </div>
                        <div style="text-align:center;">
                            <div style="font-size:.7rem;color:#9ca3af;margin-bottom:4px;">Working</div>
                            <div id="summaryWorking" style="font-weight:700;color:#4f46e5;font-size:1rem;">—</div>
                        </div>
                    </div>
                    <div id="summaryStatus" style="text-align:center;"></div>
                </div>
            </div>

        </div>

        {{-- ── Main Check-In Card ──────────────────────────────────────── --}}
        <div class="card" style="border:none;border-radius:16px;box-shadow:0 1px 3px rgba(0,0,0,.08),0 8px 32px rgba(79,70,229,.07);overflow:hidden;margin-bottom:24px;">

            <div class="card-body" style="padding:40px 32px;background:#fff;text-align:center;">

                {{-- Employee Info --}}
                <div style="margin-bottom:32px;">
                    <div id="empAvatar"
                        style="width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,#4f46e5,#7c3aed);display:flex;align-items:center;justify-content:center;margin:0 auto 12px;font-size:1.6rem;color:#fff;font-weight:700;position:relative;overflow:hidden;">
                        <span id="empInitials">?</span>
                        <img id="empPhoto" src="" alt="Employee Photo"
                            style="display:none;position:absolute;inset:0;width:100%;height:100%;object-fit:cover;border-radius:50%;">
                    </div>
                    <div id="empName" style="font-size:1.2rem;font-weight:700;color:#1a1f36;">Loading...</div>
                    <div id="empPosition" style="font-size:.82rem;color:#9ca3af;margin-top:2px;">—</div>
                </div>

                {{-- Status Ring --}}
                <div style="position:relative;width:160px;height:160px;margin:0 auto 32px;">
                    <svg viewBox="0 0 160 160" style="width:100%;height:100%;transform:rotate(-90deg);">
                        <circle cx="80" cy="80" r="68" fill="none" stroke="#f0f0f5" stroke-width="10"/>
                        <circle cx="80" cy="80" r="68" fill="none" id="progressRing"
                            stroke="#4f46e5" stroke-width="10"
                            stroke-dasharray="427" stroke-dashoffset="427"
                            stroke-linecap="round"
                            style="transition:stroke-dashoffset 1s ease,stroke .5s;"/>
                    </svg>
                    <div style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;">
                        <i id="ringIcon" class="fas fa-fingerprint" style="font-size:1.8rem;color:#4f46e5;margin-bottom:4px;"></i>
                        <div id="ringLabel" style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#9ca3af;">Ready</div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="d-flex justify-content-center" style="gap:16px;flex-wrap:wrap;">
                    <button type="button" id="btnCheckIn"
                        style="background:linear-gradient(135deg,#059669,#047857);color:#fff;border:none;border-radius:14px;padding:14px 36px;font-weight:700;font-size:1rem;letter-spacing:.3px;box-shadow:0 6px 20px rgba(5,150,105,.35);transition:all .2s;min-width:140px;">
                        <i class="fas fa-sign-in-alt mr-2"></i> Check In
                    </button>
                    <button type="button" id="btnCheckOut"
                        style="background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;border:none;border-radius:14px;padding:14px 36px;font-weight:700;font-size:1rem;letter-spacing:.3px;box-shadow:0 6px 20px rgba(245,158,11,.35);transition:all .2s;min-width:140px;">
                        <i class="fas fa-sign-out-alt mr-2"></i> Check Out
                    </button>
                </div>

                {{-- Hint --}}
                <p id="actionHint" style="margin-top:16px;font-size:.78rem;color:#9ca3af;"></p>

            </div>
        </div>

        {{-- ── Recent Logs ─────────────────────────────────────────────── --}}
        <div class="card" style="border:none;border-radius:16px;box-shadow:0 1px 3px rgba(0,0,0,.08),0 8px 32px rgba(79,70,229,.07);overflow:hidden;">

            <div class="card-header" style="background:#fff;border-bottom:1px solid #f0f0f5;padding:18px 24px;">
                <div class="d-flex align-items-center">
                    <div style="width:34px;height:34px;border-radius:9px;background:linear-gradient(135deg,#4f46e5,#7c3aed);display:flex;align-items:center;justify-content:center;margin-right:10px;">
                        <i class="fas fa-history" style="color:#fff;font-size:.8rem;"></i>
                    </div>
                    <div>
                        <h3 class="mb-0 font-weight-bold" style="font-size:.95rem;color:#1a1f36;">Recent Activity</h3>
                        <small style="color:#9ca3af;font-size:.72rem;">Your last 7 attendance records</small>
                    </div>
                </div>
            </div>

            <div class="card-body" style="padding:0;background:#fff;">
                <div id="recentLogs" style="padding:16px 24px;">
                    <div style="text-align:center;padding:30px 0;color:#9ca3af;">
                        <i class="fas fa-spinner fa-spin" style="font-size:1.4rem;"></i>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        #btnCheckIn:hover  { transform:translateY(-2px); box-shadow:0 10px 28px rgba(5,150,105,.45) !important; }
        #btnCheckOut:hover { transform:translateY(-2px); box-shadow:0 10px 28px rgba(245,158,11,.45) !important; }
        #btnCheckIn:disabled, #btnCheckOut:disabled { opacity:.55; transform:none !important; cursor:not-allowed;pointer-events:none; }

        .log-item {
            display:flex;
            align-items:center;
            gap:14px;
            padding:12px 0;
            border-bottom:1px solid #f3f4f6;
        }
        .log-item:last-child { border-bottom:none; }

        .log-dot-in  { width:10px;height:10px;border-radius:50%;background:#059669;flex-shrink:0; }
        .log-dot-out { width:10px;height:10px;border-radius:50%;background:#ef4444;flex-shrink:0; }

        #toast-container>.toast { border-radius:12px !important; box-shadow:0 8px 30px rgba(0,0,0,.12) !important; }
    </style>
@stop

{{-- ============================================================
     Scripts
     ============================================================ --}}
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
    $(document).ready(function () {

        /* ── Config ─────────────────────────────────────────── */
        toastr.options = {
            closeButton: true, progressBar: true,
            positionClass: 'toast-top-right', timeOut: 3500
        };

        const CHECKIN_URL   = '{{ url("attendance/check-in") }}';
        const CHECKOUT_URL  = '{{ url("attendance/check-out") }}';
        const TODAY_URL     = '{{ url("attendance/today") }}';
        const RECENT_URL    = '{{ url("attendance/recent") }}';
        const CSRF          = '{{ csrf_token() }}';
        const STORAGE_BASE  = '{{ asset("storage") }}';

        /* ── Live Clock ─────────────────────────────────────── */
        const days   = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
        const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

        function tickClock() {
            const now = new Date();
            const hh  = String(now.getHours()).padStart(2,'0');
            const mm  = String(now.getMinutes()).padStart(2,'0');
            const ss  = String(now.getSeconds()).padStart(2,'0');
            $('#liveClock').text(`${hh}:${mm}:${ss}`);
            $('#liveDate').text(`${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`);
        }
        tickClock();
        setInterval(tickClock, 1000);

        /* ── Progress ring helper ───────────────────────────── */
        // fill = 0..1
        function setRing(fill, color) {
            const circumference = 427;
            const offset = circumference - (fill * circumference);
            $('#progressRing')
                .attr('stroke-dashoffset', offset)
                .attr('stroke', color || '#4f46e5');
        }

        /* ── Status helpers ─────────────────────────────────── */
        function statusBadge(s) {
            const map = {
                'Present' : ['#ecfdf5','#059669','fa-check-circle'],
                'Late'    : ['#fef3c7','#d97706','fa-exclamation-circle'],
                'Absent'  : ['#fef2f2','#ef4444','fa-times-circle'],
                'Leave'   : ['#ede9fe','#7c3aed','fa-calendar-minus'],
                'Holiday' : ['#fff7ed','#ea580c','fa-star'],
            };
            const [bg, color, icon] = map[s] || ['#f3f4f6','#6b7280','fa-circle'];
            return `<span style="background:${bg};color:${color};padding:5px 14px;border-radius:20px;font-size:.75rem;font-weight:700;display:inline-flex;align-items:center;gap:6px;">
                        <i class="fas ${icon}" style="font-size:.55rem;"></i>${s}
                    </span>`;
        }

        function formatTime(t) {
            if (!t) return '—';
            const [h, m] = t.split(':');
            const hour = parseInt(h);
            const ampm = hour >= 12 ? 'PM' : 'AM';
            const hour12 = hour % 12 || 12;
            return `${String(hour12).padStart(2,'0')}:${m} ${ampm}`;
        }

        function buildPhotoUrl(photoPath) {
            if (!photoPath) return null;
            if (photoPath.startsWith('http://') || photoPath.startsWith('https://') || photoPath.startsWith('/')) {
                return photoPath;
            }
            return `${STORAGE_BASE}/${photoPath}`; // ← STORAGE_BASE = asset('storage')
        }

        /* ── Load today's data ──────────────────────────────── */
        function loadToday() {
            $.get(TODAY_URL)
                .done(function (res) {
                    if (!res.success) {
                        if (res.no_employee) {
                            // Show user avatar if available
                            if (res.user_avatar) {
                                $('#empPhoto').attr('src', res.user_avatar).show();
                                $('#empInitials').hide();
                            } else {
                                $('#empInitials').text('?');
                            }

                            $('#empName').text('No employee linked');
                            $('#empPosition').text('—');
                            $('#shiftInfo').hide();
                            $('#shiftEmpty').show();
                            $('#btnCheckIn').prop('disabled', true);
                            $('#btnCheckOut').prop('disabled', true);
                            $('#actionHint').html(
                                '<span style="color:#ef4444;"><i class="fas fa-user-slash mr-1"></i>Your account is not linked to an employee record.</span>'
                            );
                            setRing(0, '#9ca3af');
                            $('#ringIcon').attr('class','fas fa-user-slash').css('color','#9ca3af');
                            $('#ringLabel').text('N/A');
                        }
                        return;
                    }

                    const emp = res.employee;
                    const att = res.attendance;   // null if not yet checked in
                    const shift = res.shift;       // null if no shift assigned

                    if (!emp) {
                        $('#empName').text('No employee linked');
                        $('#empPosition').text('—');
                        $('#empInitials').text('?');
                        $('#shiftInfo').hide();
                        $('#shiftEmpty').show();
                        $('#btnCheckIn').prop('disabled', true);
                        $('#btnCheckOut').prop('disabled', true);
                        $('#actionHint').html(
                            '<span style="color:#ef4444;"><i class="fas fa-user-slash mr-1"></i>Your account is not linked to an employee record.</span>'
                        );
                        setRing(0, '#9ca3af');
                        $('#ringIcon').attr('class','fas fa-user-slash').css('color','#9ca3af');
                        $('#ringLabel').text('N/A');
                        return; // ← stop here, don't process further
                    }

                    /* Employee image & info */
                    const initials = ((emp.first_name?.[0] ?? '') + (emp.last_name?.[0] ?? '')).toUpperCase();
                    const photoUrl = buildPhotoUrl(emp.photo) || res.user_avatar;

                    $('#empInitials').text(initials || '?');
                    if (photoUrl) {
                        $('#empPhoto').attr('src', photoUrl).show();
                        $('#empInitials').hide();
                    } else {
                        $('#empPhoto').attr('src', '').hide();
                        $('#empInitials').show();
                    }

                    $('#empName').text(`${emp.first_name} ${emp.last_name}`);
                    $('#empPosition').text(emp.position?.position_name ?? '—');

                    /* Shift */
                    if (shift) {
                        $('#shiftName').text(shift.shift_name);
                        $('#shiftTime').text(`${shift.start_time} – ${shift.end_time}`);
                        $('#shiftHours').text(`${shift.working_hours} hrs`);
                        $('#shiftLate').text(shift.late_after_minutes);
                        $('#shiftInfo').show();
                        $('#shiftEmpty').hide();
                    } else {
                        $('#shiftInfo').hide();
                        $('#shiftEmpty').show();
                    }

                    /* Today summary & ring */
                    if (att) {
                        $('#summaryCheckIn').text(att.check_in   ? formatTime(att.check_in)   : '—');
                        $('#summaryCheckOut').text(att.check_out ? formatTime(att.check_out)   : '—');
                        $('#summaryWorking').text(att.working_hours != null ? `${att.working_hours}h` : '—');
                        $('#summaryStatus').html(statusBadge(att.status));

                        if (att.status === 'Holiday') {
                            setRing(1, '#ea580c');
                            $('#ringIcon').attr('class','fas fa-star').css('color','#ea580c');
                            $('#ringLabel').text('Holiday');
                            $('#actionHint').text('Today is a holiday. Attendance is not required.');
                            $('#btnCheckIn').prop('disabled', true);
                            $('#btnCheckOut').prop('disabled', true);
                        } else if (att.check_in && !att.check_out) {
                            // Checked in, not out yet
                            setRing(0.5, '#059669');
                            $('#ringIcon').attr('class','fas fa-sign-in-alt').css('color','#059669');
                            $('#ringLabel').text('Checked In');
                            $('#actionHint').text('You have checked in. Remember to check out before leaving.');
                            $('#btnCheckIn').prop('disabled', true);
                            $('#btnCheckOut').prop('disabled', false);
                        } else if (att.check_in && att.check_out) {
                            // Full day done
                            setRing(1, '#059669');
                            $('#ringIcon').attr('class','fas fa-check-circle').css('color','#059669');
                            $('#ringLabel').text('Done');
                            $('#actionHint').text('You have completed your attendance for today. See you tomorrow!');
                            $('#btnCheckIn').prop('disabled', true);
                            $('#btnCheckOut').prop('disabled', true);
                        }
                    } else {
                        setRing(0, '#4f46e5');
                        $('#ringIcon').attr('class','fas fa-fingerprint').css('color','#4f46e5');
                        $('#ringLabel').text('Ready');

                        if (!res.shift) {
                            // ← Disable button, show warning
                            $('#btnCheckIn').prop('disabled', true);
                            $('#btnCheckOut').prop('disabled', true);
                            $('#actionHint').html(
                                '<span style="color:#ef4444;"><i class="fas fa-ban mr-1"></i>No shift assigned. Contact your administrator.</span>'
                            );
                        } else {
                            $('#btnCheckIn').prop('disabled', false);
                            $('#btnCheckOut').prop('disabled', true);
                            $('#actionHint').text('You have not checked in yet. Click Check In to start your day.');
                        }
                    }
                })
                .fail(function (xhr) {
                const msg = xhr.responseJSON?.message ?? 'Failed to load today\'s attendance.';
                toastr.error(msg);
                console.error('today() error:', xhr.responseJSON);
            });
        }

        /* ── Load recent logs ───────────────────────────────── */
        function loadRecent() {
            $('#recentLogs').html('<div style="text-align:center;padding:30px 0;color:#9ca3af;"><i class="fas fa-spinner fa-spin" style="font-size:1.4rem;"></i></div>');

            $.get(RECENT_URL)
                .done(function (res) {
                    if (!res.success || !res.data.length) {
                        $('#recentLogs').html('<div style="text-align:center;padding:30px 0;color:#9ca3af;"><i class="fas fa-inbox" style="font-size:2rem;opacity:.4;display:block;margin-bottom:8px;"></i>No recent records.</div>');
                        return;
                    }

                    let html = '';
                    res.data.forEach(function (r) {
                        const displayDate = new Date(r.attendance_date).toISOString().split('T')[0];
                        const statusBadgeHtml = statusBadge(r.status);
                        html += `
                            <div class="log-item">
                                <div style="width:42px;height:42px;border-radius:10px;background:#f8f9ff;display:flex;flex-direction:column;align-items:center;justify-content:center;flex-shrink:0;">
                                    <span style="font-size:.65rem;font-weight:700;color:#9ca3af;text-transform:uppercase;">${formatMonth(r.attendance_date)}</span>
                                    <span style="font-size:1rem;font-weight:800;color:#1a1f36;line-height:1;">${formatDay(r.attendance_date)}</span>
                                </div>
                                <div style="flex:1;min-width:0;">
                                    <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                                        <span style="font-weight:600;color:#1a1f36;font-size:.88rem;">${r.attendance_date}</span>
                                        ${statusBadgeHtml}
                                    </div>
                                    <div style="font-size:.76rem;color:#9ca3af;margin-top:3px;display:flex;gap:14px;flex-wrap:wrap;">
                                        <span><i class="fas fa-sign-in-alt mr-1" style="color:#059669;"></i>${r.check_in ?? '—'}</span>
                                        <span><i class="fas fa-sign-out-alt mr-1" style="color:#ef4444;"></i>${r.check_out ?? '—'}</span>
                                        <span><i class="fas fa-clock mr-1" style="color:#4f46e5;"></i>${r.working_hours ? r.working_hours + ' hrs' : '—'}</span>
                                        <span style="${r.late_minutes ? 'color:#d97706;' : 'color:#6b7280;'}">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            ${r.late_minutes ? r.late_minutes + ' min late' : '0 min'}
                                        </span>
                                        <span><i class="fas fa-desktop mr-1" style="color:#6366f1;"></i>${r.device_name ?? '—'}</span>
                                        <span><i class="fas fa-map-marker-alt mr-1" style="color:#10b981;"></i>${r.gps_location ?? '—'}</span>
                                    </div>
                                </div>
                            </div>`;
                    });

                    $('#recentLogs').html(html);
                })
                .fail(function () {
                    $('#recentLogs').html('<div style="text-align:center;padding:20px 0;color:#ef4444;font-size:.85rem;">Failed to load recent records.</div>');
                });
        }

        /* ── Date helpers ───────────────────────────────────── */
        function formatMonth(dateStr) {
            if (!dateStr) return '—';
            const m = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
            const date = new Date(dateStr); // ← handles both formats
            return m[date.getMonth()];
        }

        function formatDay(dateStr) {
            if (!dateStr) return '—';
            const date = new Date(dateStr);
            return String(date.getDate()).padStart(2, '0');
        }

        $('#empPhoto').on('error', function () {
            $(this).attr('src', '').hide();
            $('#empInitials').show();
        });

            /* ── GPS helper ─────────────────────────────────────── */
            let currentGPS = null;
            let gpsReady = false;

            function getGPS() { return currentGPS; }

            // Request GPS immediately on page load
            if (navigator.geolocation) {
                navigator.geolocation.watchPosition(
                    pos => {
                        currentGPS = pos.coords.latitude + ',' + pos.coords.longitude;
                        gpsReady = true;
                    },
                    () => {
                        currentGPS = null;
                        gpsReady = true;
                    },
                    { enableHighAccuracy: true, timeout: 10000 }
                );
            } else {
                gpsReady = true;
            }

        /* ── Check In ───────────────────────────────────────── */
        $('#btnCheckIn').on('click', function () {
            const $btn = $(this);
            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> Getting location...');

            // Wait up to 5s for GPS, then proceed anyway
            let waited = 0;
            const interval = setInterval(function () {
                waited += 200;
                if (gpsReady || waited >= 5000) {
                    clearInterval(interval);
                    $btn.html('<i class="fas fa-spinner fa-spin mr-2"></i> Checking In...');

                    $.ajax({ url: CHECKIN_URL, method: 'POST', data: { _token: CSRF, gps_location: getGPS() } })
                        .done(function (res) {
                            if (res.success) {
                                toastr.success(res.message);
                                $btn.html('<i class="fas fa-sign-in-alt mr-2"></i> Check In');
                                loadToday();
                                loadRecent();
                            } else {
                                toastr.warning(res.message);
                                $btn.prop('disabled', false).html('<i class="fas fa-sign-in-alt mr-2"></i> Check In');
                            }
                        })
                        .fail(function (xhr) {
                            toastr.error(xhr.responseJSON?.message ?? 'Check-in failed.');
                            $btn.prop('disabled', false).html('<i class="fas fa-sign-in-alt mr-2"></i> Check In');
                        });
                }
            }, 200);
        });
        /* ── Check Out ──────────────────────────────────────── */
        $('#btnCheckOut').on('click', function () {
            const $btn = $(this);
            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> Getting location...');

            let waited = 0;
            const interval = setInterval(function () {
                waited += 200;
                if (gpsReady || waited >= 5000) {
                    clearInterval(interval);
                    $btn.html('<i class="fas fa-spinner fa-spin mr-2"></i> Checking Out...');

                    $.ajax({ url: CHECKOUT_URL, method: 'POST', data: { _token: CSRF, gps_location: getGPS() } })
                        .done(function (res) {
                            if (res.success) {
                                toastr.success(res.message);
                                $btn.html('<i class="fas fa-sign-out-alt mr-2"></i> Check Out');
                                loadToday();
                                loadRecent();
                            } else {
                                toastr.warning(res.message);
                                $btn.prop('disabled', false).html('<i class="fas fa-sign-out-alt mr-2"></i> Check Out');
                            }
                        })
                        .fail(function (xhr) {
                            toastr.error(xhr.responseJSON?.message ?? 'Check-out failed. Please try again.');
                            $btn.prop('disabled', false).html('<i class="fas fa-sign-out-alt mr-2"></i> Check Out');
                        });
                }
            }, 200);
        });

        /* ── Init ───────────────────────────────────────────── */
        loadToday();
        loadRecent();

    });
    </script>
@stop