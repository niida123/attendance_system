{{-- resources/views/profile/edit.blade.php --}}
@extends('adminlte::page')

@section('title', 'My Profile - Attendance System')

@section('content_header')
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h1 class="m-0 font-weight-bold" style="color:#1a1f36;font-size:1.4rem;letter-spacing:-0.3px;">
                <i class="fas fa-user-circle mr-2" style="color:#4f46e5;"></i> My Profile
            </h1>
            <ol class="breadcrumb mt-1 mb-0" style="background:transparent;padding:0;font-size:.8rem;">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" style="color:#4f46e5;">Home</a></li>
                <li class="breadcrumb-item active" style="color:#6b7280;">My Profile</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-10">

        {{-- ── Profile Header Card ─────────────────────────────────── --}}
        <div style="background:linear-gradient(135deg,#4f46e5 0%,#7c3aed 100%);border-radius:20px;padding:32px;margin-bottom:24px;position:relative;overflow:hidden;">
            {{-- decorative circles --}}
            <div style="position:absolute;top:-40px;right:-40px;width:180px;height:180px;border-radius:50%;background:rgba(255,255,255,.07);"></div>
            <div style="position:absolute;bottom:-60px;right:60px;width:120px;height:120px;border-radius:50%;background:rgba(255,255,255,.05);"></div>

            <div class="d-flex align-items-center" style="gap:24px;position:relative;z-index:1;flex-wrap:wrap;">
                {{-- Avatar --}}
                <div style="position:relative;flex-shrink:0;">
                    <div id="headerAvatar" style="width:88px;height:88px;border-radius:50%;background:rgba(255,255,255,.2);border:3px solid rgba(255,255,255,.4);display:flex;align-items:center;justify-content:center;font-size:2rem;color:#fff;font-weight:700;overflow:hidden;position:relative;">
                        <span id="headerInitials">?</span>
                        <img id="headerPhoto" src="" alt="" style="display:none;position:absolute;inset:0;width:100%;height:100%;object-fit:cover;">
                    </div>
                    <label for="avatarInput" style="position:absolute;bottom:0;right:0;width:26px;height:26px;border-radius:50%;background:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;box-shadow:0 2px 8px rgba(0,0,0,.2);" title="Change photo">
                        <i class="fas fa-camera" style="font-size:.65rem;color:#4f46e5;"></i>
                    </label>
                    <input type="file" id="avatarInput" accept="image/*" style="display:none;">
                </div>

                {{-- Name & Role --}}
                <div style="flex:1;min-width:0;">
                    <div id="headerName" style="font-size:1.5rem;font-weight:800;color:#fff;letter-spacing:-.3px;">Loading...</div>
                    <div id="headerRole" style="font-size:.82rem;color:rgba(255,255,255,.7);margin-top:2px;"></div>
                    <div id="headerEmail" style="font-size:.8rem;color:rgba(255,255,255,.6);margin-top:4px;"></div>
                    <div style="margin-top:10px;">
                        <span id="headerStatus" style="background:rgba(255,255,255,.15);color:#fff;border-radius:20px;padding:4px 14px;font-size:.72rem;font-weight:700;letter-spacing:.3px;">—</span>
                    </div>
                </div>

                {{-- Quick Stats --}}
                <div class="d-flex" style="gap:16px;flex-wrap:wrap;">
                    <div style="background:rgba(255,255,255,.12);border-radius:14px;padding:14px 20px;text-align:center;min-width:80px;">
                        <div id="statDept" style="font-size:1rem;font-weight:800;color:#fff;">—</div>
                        <div style="font-size:.68rem;color:rgba(255,255,255,.65);text-transform:uppercase;letter-spacing:.4px;margin-top:2px;">Department</div>
                    </div>
                    <div style="background:rgba(255,255,255,.12);border-radius:14px;padding:14px 20px;text-align:center;min-width:80px;">
                        <div id="statPosition" style="font-size:1rem;font-weight:800;color:#fff;">—</div>
                        <div style="font-size:.68rem;color:rgba(255,255,255,.65);text-transform:uppercase;letter-spacing:.4px;margin-top:2px;">Position</div>
                    </div>
                    <div style="background:rgba(255,255,255,.12);border-radius:14px;padding:14px 20px;text-align:center;min-width:80px;">
                        <div id="statJoined" style="font-size:1rem;font-weight:800;color:#fff;">—</div>
                        <div style="font-size:.68rem;color:rgba(255,255,255,.65);text-transform:uppercase;letter-spacing:.4px;margin-top:2px;">Joined</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">

            {{-- ── LEFT COLUMN ──────────────────────────────────────── --}}
            <div class="col-md-6 mb-4">

                {{-- Personal Info --}}
                <div style="background:#fff;border-radius:16px;border:1px solid #f0f0f5;padding:24px;margin-bottom:24px;">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:34px;height:34px;border-radius:9px;background:linear-gradient(135deg,#4f46e5,#7c3aed);display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-user" style="color:#fff;font-size:.8rem;"></i>
                            </div>
                            <div>
                                <div style="font-weight:700;color:#1a1f36;font-size:.95rem;">Personal Info</div>
                                <div style="font-size:.72rem;color:#9ca3af;">Update your account details</div>
                            </div>
                        </div>
                    </div>

                    <form id="formPersonal">
                        @csrf
                        <div class="form-group mb-3">
                            <label style="font-size:.75rem;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.4px;">Username</label>
                            <input type="text" id="username" name="username" class="form-control"
                                style="border-radius:10px;border:1.5px solid #e5e7eb;padding:10px 14px;font-size:.88rem;color:#1a1f36;">
                        </div>
                        <div class="form-group mb-3">
                            <label style="font-size:.75rem;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.4px;">Email Address</label>
                            <input type="email" id="email" name="email" class="form-control"
                                style="border-radius:10px;border:1.5px solid #e5e7eb;padding:10px 14px;font-size:.88rem;color:#1a1f36;">
                        </div>
                        <button type="button" id="btnSavePersonal"
                            style="background:linear-gradient(135deg,#4f46e5,#7c3aed);color:#fff;border:none;border-radius:10px;padding:10px 24px;font-weight:700;font-size:.85rem;width:100%;transition:all .2s;">
                            <i class="fas fa-save mr-2"></i> Save Changes
                        </button>
                    </form>
                </div>

                {{-- Change Password --}}
                <div style="background:#fff;border-radius:16px;border:1px solid #f0f0f5;padding:24px;">
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:20px;">
                        <div style="width:34px;height:34px;border-radius:9px;background:linear-gradient(135deg,#059669,#047857);display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-lock" style="color:#fff;font-size:.8rem;"></i>
                        </div>
                        <div>
                            <div style="font-weight:700;color:#1a1f36;font-size:.95rem;">Change Password</div>
                            <div style="font-size:.72rem;color:#9ca3af;">Keep your account secure</div>
                        </div>
                    </div>

                    <form id="formPassword">
                        @csrf
                        <div class="form-group mb-3">
                            <label style="font-size:.75rem;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.4px;">Current Password</label>
                            <div style="position:relative;">
                                <input type="password" id="current_password" name="current_password" class="form-control"
                                    style="border-radius:10px;border:1.5px solid #e5e7eb;padding:10px 40px 10px 14px;font-size:.88rem;">
                                <i class="fas fa-eye toggle-pw" data-target="current_password"
                                    style="position:absolute;right:12px;top:50%;transform:translateY(-50%);color:#9ca3af;cursor:pointer;font-size:.85rem;"></i>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label style="font-size:.75rem;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.4px;">New Password</label>
                            <div style="position:relative;">
                                <input type="password" id="new_password" name="new_password" class="form-control"
                                    style="border-radius:10px;border:1.5px solid #e5e7eb;padding:10px 40px 10px 14px;font-size:.88rem;">
                                <i class="fas fa-eye toggle-pw" data-target="new_password"
                                    style="position:absolute;right:12px;top:50%;transform:translateY(-50%);color:#9ca3af;cursor:pointer;font-size:.85rem;"></i>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label style="font-size:.75rem;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.4px;">Confirm New Password</label>
                            <div style="position:relative;">
                                <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-control"
                                    style="border-radius:10px;border:1.5px solid #e5e7eb;padding:10px 40px 10px 14px;font-size:.88rem;">
                                <i class="fas fa-eye toggle-pw" data-target="new_password_confirmation"
                                    style="position:absolute;right:12px;top:50%;transform:translateY(-50%);color:#9ca3af;cursor:pointer;font-size:.85rem;"></i>
                            </div>
                        </div>
                        <button type="button" id="btnSavePassword"
                            style="background:linear-gradient(135deg,#059669,#047857);color:#fff;border:none;border-radius:10px;padding:10px 24px;font-weight:700;font-size:.85rem;width:100%;transition:all .2s;">
                            <i class="fas fa-shield-alt mr-2"></i> Update Password
                        </button>
                    </form>
                </div>

            </div>

            {{-- ── RIGHT COLUMN ─────────────────────────────────────── --}}
            <div class="col-md-6 mb-4">

                {{-- Employee Details --}}
                <div style="background:#fff;border-radius:16px;border:1px solid #f0f0f5;padding:24px;margin-bottom:24px;">
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:20px;">
                        <div style="width:34px;height:34px;border-radius:9px;background:linear-gradient(135deg,#f59e0b,#d97706);display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-id-badge" style="color:#fff;font-size:.8rem;"></i>
                        </div>
                        <div>
                            <div style="font-weight:700;color:#1a1f36;font-size:.95rem;">Employee Details</div>
                            <div style="font-size:.72rem;color:#9ca3af;">Your linked employee record</div>
                        </div>
                    </div>

                    <div id="empDetailsContent">
                        <div style="text-align:center;padding:30px 0;color:#9ca3af;">
                            <i class="fas fa-spinner fa-spin" style="font-size:1.4rem;"></i>
                        </div>
                    </div>
                </div>

                {{-- Account Activity --}}
                <div style="background:#fff;border-radius:16px;border:1px solid #f0f0f5;padding:24px;">
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:20px;">
                        <div style="width:34px;height:34px;border-radius:9px;background:linear-gradient(135deg,#06b6d4,#0891b2);display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-history" style="color:#fff;font-size:.8rem;"></i>
                        </div>
                        <div>
                            <div style="font-weight:700;color:#1a1f36;font-size:.95rem;">Account Activity</div>
                            <div style="font-size:.72rem;color:#9ca3af;">Recent account events</div>
                        </div>
                    </div>

                    <div id="activityContent">
                        <div style="text-align:center;padding:30px 0;color:#9ca3af;">
                            <i class="fas fa-spinner fa-spin" style="font-size:1.4rem;"></i>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        .form-control:focus { border-color:#4f46e5 !important; box-shadow:0 0 0 3px rgba(79,70,229,.12) !important; }
        #btnSavePersonal:hover { opacity:.9; transform:translateY(-1px); }
        #btnSavePassword:hover { opacity:.9; transform:translateY(-1px); }
        .emp-row { display:flex; align-items:center; padding:10px 0; border-bottom:1px solid #f3f4f6; gap:12px; }
        .emp-row:last-child { border-bottom:none; }
        .emp-label { font-size:.72rem; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:.4px; width:110px; flex-shrink:0; }
        .emp-value { font-size:.88rem; color:#1a1f36; font-weight:600; }
        .activity-item { display:flex; align-items:flex-start; gap:12px; padding:10px 0; border-bottom:1px solid #f3f4f6; }
        .activity-item:last-child { border-bottom:none; }
        #toast-container>.toast { border-radius:12px !important; box-shadow:0 8px 30px rgba(0,0,0,.12) !important; }
        .toggle-pw:hover { color:#4f46e5 !important; }
    </style>
@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
    $(document).ready(function () {

        toastr.options = { closeButton:true, progressBar:true, positionClass:'toast-top-right', timeOut:3500 };

        const PROFILE_URL  = '{{ route("profile.show") }}';
        const UPDATE_URL   = '{{ route("profile.update-info") }}';
        const PASSWORD_URL = '{{ route("profile.update-password") }}';
        const AVATAR_URL   = '{{ route("profile.update-avatar") }}';
        const CSRF         = '{{ csrf_token() }}';
        const STORAGE_BASE = '{{ asset("storage") }}';

        /* ── Load profile data ──────────────────────────────── */
        function loadProfile() {
            $.get(PROFILE_URL).done(function (res) {
                if (!res.success) return;

                const u   = res.user;
                const emp = res.employee;

                /* Header */
                const initials = u.initials || u.username?.substring(0,2).toUpperCase() || '?';
                $('#headerInitials').text(initials);
                if (u.avatar_url) {
                    $('#headerPhoto').attr('src', u.avatar_url).show();
                    $('#headerInitials').hide();
                }
                $('#headerName').text(u.username);
                $('#headerRole').text(u.role || '—');
                $('#headerEmail').text(u.email);
                $('#headerStatus').text(u.status).css({
                    background: u.status === 'Active' ? 'rgba(5,150,105,.2)' : 'rgba(239,68,68,.2)',
                    color: u.status === 'Active' ? '#d1fae5' : '#fee2e2'
                });

                /* Quick stats */
                $('#statDept').text(emp?.department?.department_name || '—');
                $('#statPosition').text(emp?.position?.position_name || '—');
                $('#statJoined').text(emp?.hire_date ? formatDate(emp.hire_date) : (u.created_at ? formatDate(u.created_at) : '—'));

                /* Personal Info form */
                $('#username').val(u.username);
                $('#email').val(u.email);

                /* Employee details */
                renderEmpDetails(emp);

                /* Activity */
                renderActivity(res.activity);

            }).fail(function () {
                toastr.error('Failed to load profile.');
            });
        }

        /* ── Employee details block ─────────────────────────── */
        function renderEmpDetails(emp) {
            if (!emp) {
                $('#empDetailsContent').html(`
                    <div style="text-align:center;padding:24px 0;color:#9ca3af;">
                        <i class="fas fa-user-slash" style="font-size:2rem;opacity:.3;display:block;margin-bottom:8px;"></i>
                        <div style="font-size:.85rem;">No employee record linked to your account.</div>
                    </div>`);
                return;
            }

            const rows = [
                ['Employee ID',  emp.employee_id   || '—'],
                ['Full Name',    `${emp.first_name} ${emp.last_name}`],
                ['Department',   emp.department?.department_name  || '—'],
                ['Position',     emp.position?.position_name      || '—'],
                ['Phone',        emp.phone         || '—'],
                ['Gender',       emp.gender        || '—'],
                ['Hire Date',    emp.hire_date     ? formatDate(emp.hire_date) : '—'],
                ['Employment',   emp.employment_type || '—'],
            ];

            let html = '';
            rows.forEach(([label, value]) => {
                html += `<div class="emp-row">
                    <span class="emp-label">${label}</span>
                    <span class="emp-value">${value}</span>
                </div>`;
            });

            $('#empDetailsContent').html(html);
        }

        /* ── Activity block ─────────────────────────────────── */
        function renderActivity(activity) {
            if (!activity || !activity.length) {
                $('#activityContent').html(`<div style="text-align:center;padding:24px 0;color:#9ca3af;font-size:.85rem;">No recent activity.</div>`);
                return;
            }

            const iconMap = {
                'login'           : ['fa-sign-in-alt',  '#4f46e5', '#eef2ff'],
                'password_change' : ['fa-lock',         '#059669', '#ecfdf5'],
                'profile_update'  : ['fa-user-edit',    '#f59e0b', '#fef3c7'],
                'avatar_change'   : ['fa-camera',       '#06b6d4', '#ecfeff'],
            };

            let html = '';
            activity.forEach(function (a) {
                const [icon, color, bg] = iconMap[a.type] || ['fa-circle', '#6b7280', '#f3f4f6'];
                html += `
                <div class="activity-item">
                    <div style="width:34px;height:34px;border-radius:9px;background:${bg};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fas ${icon}" style="color:${color};font-size:.8rem;"></i>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-size:.85rem;font-weight:600;color:#1a1f36;">${a.label}</div>
                        <div style="font-size:.73rem;color:#9ca3af;margin-top:2px;">${a.description || ''}</div>
                        <div style="font-size:.7rem;color:#d1d5db;margin-top:3px;"><i class="fas fa-clock mr-1"></i>${a.time}</div>
                    </div>
                </div>`;
            });

            $('#activityContent').html(html);
        }

        /* ── Save personal info ─────────────────────────────── */
        $('#formPersonal').on('submit', function (e) { e.preventDefault(); return false; });

        $('#btnSavePersonal').on('click', function () {
            const $btn = $(this);
            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> Saving...');

            $.ajax({
                url: UPDATE_URL, method: 'POST',
                data: { _token: CSRF, username: $('#username').val(), email: $('#email').val() }
            }).done(function (res) {
                if (res.success) {
                    toastr.success(res.message || 'Profile updated.');
                    loadProfile();
                } else {
                    toastr.warning(res.message || 'Could not update profile.');
                }
            }).fail(function (xhr) {
                const errors = xhr.responseJSON?.errors;
                if (errors) {
                    Object.values(errors).flat().forEach(msg => toastr.error(msg));
                } else {
                    toastr.error(xhr.responseJSON?.message || 'Update failed.');
                }
            }).always(function () {
                $btn.prop('disabled', false).html('<i class="fas fa-save mr-2"></i> Save Changes');
            });
        });

        /* ── Save password ──────────────────────────────────── */
        $('#formPassword').on('submit', function (e) { e.preventDefault(); return false; });

        $('#btnSavePassword').on('click', function () {
            const $btn = $(this);
            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> Updating...');

            $.ajax({
                url: PASSWORD_URL, method: 'POST',
                data: {
                    _token: CSRF,
                    current_password:          $('#current_password').val(),
                    new_password:              $('#new_password').val(),
                    new_password_confirmation: $('#new_password_confirmation').val(),
                }
            }).done(function (res) {
                if (res.success) {
                    toastr.success(res.message || 'Password updated.');
                    $('#formPassword')[0].reset();
                } else {
                    toastr.warning(res.message || 'Could not update password.');
                }
            }).fail(function (xhr) {
                const errors = xhr.responseJSON?.errors;
                if (errors) {
                    Object.values(errors).flat().forEach(msg => toastr.error(msg));
                } else {
                    toastr.error(xhr.responseJSON?.message || 'Password update failed.');
                }
            }).always(function () {
                $btn.prop('disabled', false).html('<i class="fas fa-shield-alt mr-2"></i> Update Password');
            });
        });

        /* ── Avatar upload ──────────────────────────────────── */
        $('#avatarInput').on('change', function () {
            const file = this.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('_token', CSRF);
            formData.append('avatar', file);

            // Preview immediately
            const reader = new FileReader();
            reader.onload = e => {
                $('#headerPhoto').attr('src', e.target.result).show();
                $('#headerInitials').hide();
            };
            reader.readAsDataURL(file);

            $.ajax({
                url: AVATAR_URL, 
                method: 'POST',
                data: formData, 
                processData: false, 
                contentType: false,
                headers: { 
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
            }).done(function (res) {
                console.log('AVATAR:', res);
                if (res.success) {
                    toastr.success('Avatar updated.');
                } else {
                    toastr.warning(res.message || 'Could not update avatar.');
                }
            }).fail(function (xhr) {
                console.log('AVATAR FAIL:', xhr.status, xhr.responseJSON);
                toastr.error('Avatar upload failed.');
            });
        });

        /* ── Password visibility toggle ─────────────────────── */
        $(document).on('click', '.toggle-pw', function () {
            const target = $(this).data('target');
            const input  = $('#' + target);
            const isPass = input.attr('type') === 'password';
            input.attr('type', isPass ? 'text' : 'password');
            $(this).toggleClass('fa-eye fa-eye-slash');
        });

        /* ── Helpers ────────────────────────────────────────── */
        function formatDate(dateStr) {
            if (!dateStr) return '—';
            const d = new Date(dateStr);
            const m = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
            return `${m[d.getMonth()]} ${d.getFullYear()}`;
        }

        /* ── Init ───────────────────────────────────────────── */
        loadProfile();
    });
    </script>
@stop 