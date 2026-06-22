{{-- resources/views/errors/403.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Restricted</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #fafbff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .error-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,.08), 0 20px 60px rgba(79,70,229,.12);
            max-width: 460px;
            width: 100%;
            padding: 48px 40px;
            text-align: center;
        }
        .icon-wrap {
            width: 84px;
            height: 84px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 28px;
            box-shadow: 0 8px 24px rgba(79,70,229,.3);
        }
        .icon-wrap i {
            font-size: 1.8rem;
            color: #fff;
        }
        h1 {
            font-size: 1.4rem;
            font-weight: 700;
            color: #1a1f36;
            letter-spacing: -0.3px;
            margin-bottom: 10px;
        }
        p.message {
            font-size: .92rem;
            color: #6b7280;
            line-height: 1.6;
            margin-bottom: 8px;
        }
        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #eef2ff;
            color: #4f46e5;
            font-size: .78rem;
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 20px;
            margin: 18px 0 28px;
        }
        .actions {
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .btn {
            padding: 11px 22px;
            border-radius: 10px;
            font-size: .85rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
            cursor: pointer;
            transition: all .2s;
        }
        .btn-primary {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: #fff;
            box-shadow: 0 4px 14px rgba(79,70,229,.3);
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(79,70,229,.4);
        }
        .btn-secondary {
            background: #fff;
            color: #6b7280;
            border: 1.5px solid #e5e7eb;
        }
        .btn-secondary:hover {
            background: #f9fafb;
            color: #374151;
        }
        .footer-note {
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid #f0f0f5;
            font-size: .78rem;
            color: #9ca3af;
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="icon-wrap">
            <i class="fas fa-lock"></i>
        </div>

        <h1>Access Restricted</h1>
        <p class="message">
            You don't have permission to view this page.<br>
            This area is limited to specific roles within the system.
        </p>

        @auth
            @php $userRole = auth()->user()->roles->first(); @endphp
            @if ($userRole)
                <div class="role-badge">
                    <i class="fas fa-user-shield"></i> Signed in as {{ $userRole->name }}
                </div>
            @endif
        @endauth

        <div class="actions">
            <a href="{{ route('dashboard') }}" class="btn btn-primary">
                <i class="fas fa-home"></i> Back to Dashboard
            </a>
            <a href="javascript:history.back()" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Go Back
            </a>
        </div>

        <div class="footer-note">
            If you believe this is a mistake, contact your system administrator.
        </div>
    </div>
</body>
</html>