<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    {{-- Base Meta Tags --}}
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Custom Meta Tags --}}
    @yield('meta_tags')

    {{-- Title --}}
    <title>
        @yield('title_prefix', config('adminlte.title_prefix', ''))
        @yield('title', config('adminlte.title', 'AdminLTE 3'))
        @yield('title_postfix', config('adminlte.title_postfix', ''))
    </title>

    <style>
        /* ── Sidebar: White background ── */
        .main-sidebar,
        .sidebar-dark-indigo,
        .sidebar-dark-indigo .sidebar,
        .sidebar-dark-indigo .os-viewport,
        .sidebar-dark-indigo .os-content {
            background: #ffffff !important;
            box-shadow: 2px 0 8px rgba(0,0,0,0.04) !important;
            border-right: 1px solid #e5e7eb !important;
        }

        /* ── Sidebar width ── */
        .main-sidebar {
            width: 250px !important;
            overflow-x: hidden !important;
        }
        .main-header .navbar {
            margin-left: 250px !important;
        }
        .content-wrapper,
        .main-footer {
            margin-left: 250px !important;
        }

        /* ── Brand/Logo area ── */
        .brand-link,
        .brand-link:hover {
            background: linear-gradient(135deg, #4f46e5, #7c3aed) !important;
            border-bottom: none !important;
            padding: 16px 20px !important;
        }
        .brand-link .brand-text,
        .brand-link .brand-text b,
        .brand-link i {
            color: #ffffff !important;
            font-weight: 700 !important;
            font-size: 1.1rem !important;
        }

        /* ── Section headers ── */
        .nav-sidebar .nav-header {
            color: #9ca3af !important;
            font-size: .72rem !important;
            letter-spacing: .8px !important;
            padding: 14px 20px 4px !important;
        }

        /* ── Nav items ── */
        .nav-sidebar .nav-item {
            padding: 3px 10px !important;
            overflow: hidden !important;
        }

        /* ── Nav links ── */
        .nav-sidebar .nav-link {
            color: #6b7280 !important;
            border-radius: 10px !important;
            padding: 10px 14px !important;
            font-size: 1rem !important;
            font-weight: 500 !important;
            transition: all .2s !important;
            width: 100% !important;
            overflow: hidden !important;
            border: none !important;
            background: transparent !important;
        }
        .nav-sidebar .nav-link:hover {
            color: #4f46e5 !important;
            background: #eef2ff !important;
        }

        /* ── Active nav link ── */
        .nav-sidebar .nav-item > .nav-link.active,
        .nav-sidebar .nav-item > .nav-link.active:hover,
        .nav-sidebar .nav-treeview > .nav-item > .nav-link.active,
        .nav-sidebar .nav-treeview > .nav-item > .nav-link.active:hover,
        .sidebar-dark-indigo .nav-sidebar .nav-link.active,
        .sidebar-dark-indigo .nav-sidebar .nav-treeview .nav-link.active {
            color: #4f46e5 !important;
            background: #eef2ff !important;
            font-weight: 600 !important;
            box-shadow: none !important;
            border: none !important;
        }

        /* ── Icons ── */
        .nav-sidebar .nav-icon {
            color: #9ca3af !important;
            font-size: 1.1rem !important;
            margin-right: 10px !important;
            width: 20px !important;
            text-align: center !important;
        }
        .nav-sidebar .nav-link:hover .nav-icon,
        .nav-sidebar .nav-link.active .nav-icon {
            color: #4f46e5 !important;
        }

        /* ── Treeview submenu ── */
        .nav-sidebar .nav-treeview {
            background: transparent !important;
            padding-left: 10px !important;
        }
        .nav-sidebar .nav-treeview .nav-link {
            color: #9ca3af !important;
            font-size: .9rem !important;
            border: none !important;
            background: transparent !important;
        }
        .nav-sidebar .nav-treeview .nav-link:hover {
            color: #4f46e5 !important;
            background: #eef2ff !important;
        }
        .nav-sidebar .nav-treeview .nav-link.active {
            color: #4f46e5 !important;
            background: #eef2ff !important;
            font-weight: 600 !important;
        }

        /* ── Scrollbar ── */
        .main-sidebar::-webkit-scrollbar { width: 3px; }
        .main-sidebar::-webkit-scrollbar-thumb {
            background: #e5e7eb;
            border-radius: 4px;
        }

        /* ── Collapsed sidebar ── */
        .sidebar-collapse .main-sidebar {
            width: 4.6rem !important;
        }
        .sidebar-collapse .main-sidebar:hover {
            width: 250px !important;
        }
        .sidebar-collapse .content-wrapper,
        .sidebar-collapse .main-footer {
            margin-left: 4.6rem !important;
        }
        .sidebar-collapse .main-header .navbar {
            margin-left: 4.6rem !important;
        }

        /* ── Mobile: no margin, full width ── */
        @media (max-width: 767.98px) {
            .main-header .navbar {
                margin-left: 0 !important;
            }
            .content-wrapper,
            .main-footer {
                margin-left: 0 !important;
            }
            .main-sidebar {
                position: fixed !important;
                transform: translateX(-250px) !important;
                transition: transform 0.3s ease !important;
                z-index: 1050 !important;
            }
            /* Closed by default (sidebar-collapse on mobile = hidden) */
            body.sidebar-collapse .main-sidebar {
                transform: translateX(-250px) !important;
            }
            /* Open state */
            body:not(.sidebar-collapse) .main-sidebar,
            .sidebar-open .main-sidebar {
                transform: translateX(0) !important;
                width: 250px !important;
                box-shadow: 4px 0 15px rgba(0,0,0,0.15) !important;
            }
            /* Overlay when sidebar open on mobile */
            .sidebar-open .main-sidebar::after,
            body:not(.sidebar-collapse) .main-sidebar::after {
                content: '';
                position: fixed;
                top: 0; left: 250px;
                width: 100vw; height: 100vh;
                background: rgba(0,0,0,0.3);
                z-index: -1;
            }
            /* Tables scroll horizontally on mobile */
            .table-responsive,
            .dataTables_wrapper {
                overflow-x: auto !important;
                -webkit-overflow-scrolling: touch !important;
            }
            /* Cards full width */
            .card {
                margin-bottom: 1rem !important;
            }
            /* Content padding */
            .content-wrapper > .content {
                padding: 0.75rem !important;
            }
        }

        /* ── Desktop fixed ── */
        @media (min-width: 768px) {
            .main-sidebar {
                position: fixed !important;
                transform: none !important;
            }
            body:not(.sidebar-collapse) .content-wrapper,
            body:not(.sidebar-collapse) .main-footer {
                margin-left: 250px !important;
            }
            body:not(.sidebar-collapse) .main-header .navbar {
                margin-left: 250px !important;
            }
        }

        /* ── NProgress bar ── */
        #nprogress .bar {
            background: #4f46e5 !important;
            height: 3px !important;
            z-index: 9999 !important;
        }
        #nprogress .peg {
            box-shadow: 0 0 10px #4f46e5, 0 0 5px #4f46e5 !important;
        }
    </style>

    {{-- NProgress CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css">

    {{-- Custom stylesheets (pre AdminLTE) --}}
    @yield('adminlte_css_pre')

    {{-- Base Stylesheets --}}
    @if(config('adminlte.enabled_laravel_mix', false))
        <link rel="stylesheet" href="{{ mix(config('adminlte.laravel_mix_css_path', 'css/app.css')) }}">
    @else
        @switch(config('adminlte.laravel_asset_bundling', false))
            @case('mix')
                <link rel="stylesheet" href="{{ mix(config('adminlte.laravel_css_path', 'css/app.css')) }}">
            @break

            @case('vite')
                @vite([config('adminlte.laravel_css_path', 'resources/css/app.css'), config('adminlte.laravel_js_path', 'resources/js/app.js')])
            @break

            @case('vite_js_only')
                @vite(config('adminlte.laravel_js_path', 'resources/js/app.js'))
            @break

            @default
                <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
                <link rel="stylesheet" href="{{ asset('vendor/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
                <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">

                @if(config('adminlte.google_fonts.allowed', true))
                    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
                @endif
        @endswitch
    @endif

    {{-- Extra Configured Plugins Stylesheets --}}
    @include('adminlte::plugins', ['type' => 'css'])

    {{-- Livewire Styles --}}
    @if(config('adminlte.livewire'))
        @if(intval(app()->version()) >= 7)
            @livewireStyles
        @else
            <livewire:styles />
        @endif
    @endif

    {{-- Custom Stylesheets (post AdminLTE) --}}
    @yield('adminlte_css')

    {{-- Favicon --}}
    @if(config('adminlte.use_ico_only'))
        <link rel="shortcut icon" href="{{ asset('favicons/favicon.ico') }}" />
    @elseif(config('adminlte.use_full_favicon'))
        <link rel="shortcut icon" href="{{ asset('favicons/favicon.ico') }}" />
        <link rel="apple-touch-icon" sizes="57x57"  href="{{ asset('favicons/apple-icon-57x57.png') }}">
        <link rel="apple-touch-icon" sizes="60x60"  href="{{ asset('favicons/apple-icon-60x60.png') }}">
        <link rel="apple-touch-icon" sizes="72x72"  href="{{ asset('favicons/apple-icon-72x72.png') }}">
        <link rel="apple-touch-icon" sizes="76x76"  href="{{ asset('favicons/apple-icon-76x76.png') }}">
        <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('favicons/apple-icon-114x114.png') }}">
        <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('favicons/apple-icon-120x120.png') }}">
        <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('favicons/apple-icon-144x144.png') }}">
        <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('favicons/apple-icon-152x152.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicons/apple-icon-180x180.png') }}">
        <link rel="icon" type="image/png" sizes="16x16"  href="{{ asset('favicons/favicon-16x16.png') }}">
        <link rel="icon" type="image/png" sizes="32x32"  href="{{ asset('favicons/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="96x96"  href="{{ asset('favicons/favicon-96x96.png') }}">
        <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicons/android-icon-192x192.png') }}">
        <link rel="manifest" crossorigin="use-credentials" href="{{ asset('favicons/manifest.json') }}">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="{{ asset('favicons/ms-icon-144x144.png') }}">
    @endif

</head>

<body class="@yield('classes_body')" @yield('body_data')>

    {{-- Body Content --}}
    @yield('body')

    {{-- Base Scripts --}}
    @if(config('adminlte.enabled_laravel_mix', false))
        <script src="{{ mix(config('adminlte.laravel_mix_js_path', 'js/app.js')) }}"></script>
    @else
        @switch(config('adminlte.laravel_asset_bundling', false))
            @case('mix')
                <script src="{{ mix(config('adminlte.laravel_js_path', 'js/app.js')) }}"></script>
            @break

            @case('vite')
            @case('vite_js_only')
            @break

            @default
                <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
                <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
                <script src="{{ asset('vendor/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
                <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
        @endswitch
    @endif

    {{-- Extra Configured Plugins Scripts --}}
    @include('adminlte::plugins', ['type' => 'js'])

    {{-- Livewire Script --}}
    @if(config('adminlte.livewire'))
        @if(intval(app()->version()) >= 7)
            @livewireScripts
        @else
            <livewire:scripts />
        @endif
    @endif

    {{-- Custom Scripts --}}
    @yield('adminlte_js')

    {{-- NProgress + Push Menu Fix --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.js"></script>
    <script>
        NProgress.configure({ showSpinner: false, speed: 400, minimum: 0.15 });

        // Start progress bar immediately on any link click
        $(document).on('click', 'a', function () {
            var href = $(this).attr('href');
            if (
                !href ||
                href === '#' ||
                href.startsWith('javascript') ||
                href.startsWith('mailto') ||
                href.startsWith('tel') ||
                $(this).attr('target') === '_blank' ||
                $(this).attr('data-toggle') ||    // Bootstrap modals/dropdowns
                $(this).attr('data-widget')        // AdminLTE widgets
            ) return;
            NProgress.start();
        });

        // Finish bar when new page is fully loaded
        $(window).on('load', function () {
            NProgress.done();
        });

        // Safety net: always finish after 5s in case load event misfires
        setTimeout(function () { NProgress.done(); }, 5000);

        // Push Menu margin fix
        $(document).ready(function () {
            $(document).on('collapsed.pushMenu', function () {
                $('.content-wrapper, .main-footer').css('margin-left', '4.6rem');
                $('.main-header .navbar').css('margin-left', '4.6rem');
            });
            $(document).on('expanded.pushMenu', function () {
                $('.content-wrapper, .main-footer').css('margin-left', '250px');
                $('.main-header .navbar').css('margin-left', '250px');
            });
        });
    </script>

</body>

</html>