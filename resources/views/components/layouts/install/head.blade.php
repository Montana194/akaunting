@props([
    'title',
])

<head>
    @stack('head_start')

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8; charset=ISO-8859-1"/>

    <title>{!! $title !!} - {{ config('app.name') }}</title>

    <base href="{{ config('app.url') . '/' }}">

    <x-layouts.pwa.head />

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('img/favicon.ico') }}" type="image/png">

    <!--Icons-->
    <link rel="stylesheet" href="{{ asset('css/fonts/material-icons/style.css?v=' . version('short')) }}" type="text/css">

     <!-- Font -->
    <link rel="stylesheet" href="{{ asset('vendor/quicksand/css/quicksand.css?v=' . version('short')) }}" type="text/css">

    <!-- Css -->
    <link rel="stylesheet" href="{{ asset('css/third_party/swiper-bundle.min.css?v=' . version('short')) }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/element.css?v=' . version('short')) }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/app.css?v=' . version('short')) }}" type="text/css">

    <!-- Premium Modern UI Styling -->
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #10b981 0%, #059669 100%);
            --accent-glow: rgba(16, 185, 129, 0.35);
            --card-glass: rgba(255, 255, 255, 0.95);
        }

        body {
            font-family: 'Quicksand', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%) !important;
            min-height: 100vh;
        }

        .install-page-shell {
            position: relative;
            min-height: 100vh;
            height: auto;
            overflow: hidden;
            isolation: isolate;
        }
        .install-page-shell::before {
            position: absolute;
            z-index: -1;
            inset: 0;
            background: linear-gradient(125deg, rgba(15, 23, 42, 0.88), rgba(15, 23, 42, 0.48));
            content: "";
        }

        /* Glassmorphism Card Container */
        .install-glass-card {
            width: 100%;
            box-sizing: border-box;
            background: var(--card-glass) !important;
            backdrop-filter: blur(24px) saturate(180%) !important;
            -webkit-backdrop-filter: blur(24px) saturate(180%) !important;
            border-radius: 24px !important;
            border: 1px solid rgba(255, 255, 255, 0.8) !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25), 0 0 0 1px rgba(255, 255, 255, 0.3) !important;
            padding: clamp(1.25rem, 3vw, 2.5rem) !important;
            transition: all 0.3s ease !important;
        }

        .install-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            padding: 7px 12px;
            border-radius: 9999px;
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.25);
            color: #059669;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }
        .install-status-dot {
            width: 8px;
            height: 8px;
            flex: 0 0 8px;
            border-radius: 50%;
            background-color: #10b981;
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            animation: pulse-green 2s infinite;
        }
        @keyframes pulse-green {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 8px rgba(16, 185, 129, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        /* Stepper Navigation Polish */
        nav[aria-label="Progress"] ol {
            display: flex;
            gap: 12px;
            margin-bottom: 24px !important;
            padding: 0;
            list-style: none;
        }
        nav[aria-label="Progress"] li {
            flex: 1;
        }
        nav[aria-label="Progress"] span.w-full {
            height: 5px !important;
            border-radius: 9999px !important;
            transition: all 0.4s ease !important;
        }
        nav[aria-label="Progress"] span.bg-purple {
            background: var(--primary-gradient) !important;
            box-shadow: 0 0 10px var(--accent-glow) !important;
        }

        /* Language Select Box Styling */
        select#lang {
            width: 100% !important;
            border-radius: 16px !important;
            border: 1.5px solid #e2e8f0 !important;
            background: #ffffff !important;
            padding: 8px !important;
            box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.03), 0 4px 6px -1px rgba(0, 0, 0, 0.05) !important;
            outline: none !important;
            overflow-y: auto !important;
            height: 320px !important;
            transition: all 0.2s ease !important;
            font-size: 14px !important;
        }
        select#lang:focus {
            border-color: #10b981 !important;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15) !important;
        }
        select#lang option {
            padding: 10px 16px !important;
            border-radius: 10px !important;
            margin-bottom: 4px !important;
            font-size: 14px !important;
            font-weight: 500 !important;
            color: #1e293b !important;
            cursor: pointer !important;
            transition: all 0.15s ease !important;
        }
        select#lang option:hover {
            background-color: #f1f5f9 !important;
        }
        select#lang option:checked,
        select#lang option:selected {
            background: var(--primary-gradient) !important;
            color: #ffffff !important;
            font-weight: 600 !important;
            box-shadow: 0 4px 10px rgba(16, 185, 129, 0.3) !important;
        }

        /* Custom Scrollbar for Select */
        select#lang::-webkit-scrollbar {
            width: 7px;
        }
        select#lang::-webkit-scrollbar-track {
            background: #f8fafc;
            border-radius: 8px;
        }
        select#lang::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 8px;
        }
        select#lang::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Form Inputs in Card Body */
        .card-body input,
        #form-install input {
            width: 100% !important;
            border-radius: 12px !important;
            border: 1.5px solid #e2e8f0 !important;
            padding: 12px 16px !important;
            font-size: 14px !important;
            color: #0f172a !important;
            background-color: #ffffff !important;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
        }
        .card-body input[type="checkbox"],
        .card-body input[type="radio"],
        #form-install input[type="checkbox"],
        #form-install input[type="radio"] {
            width: auto !important;
            padding: 0 !important;
            border-radius: 4px !important;
            accent-color: #059669;
        }
        .card-body input:focus,
        #form-install input:focus {
            border-color: #10b981 !important;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.18) !important;
            outline: none !important;
        }
        #form-install :focus-visible {
            outline: 3px solid rgba(16, 185, 129, 0.55);
            outline-offset: 3px;
        }
        .card-body label {
            font-size: 13px !important;
            font-weight: 600 !important;
            color: #334155 !important;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 6px;
            display: inline-block;
        }

        .install-step-title {
            color: #0f172a !important;
            font-size: clamp(1.25rem, 2vw, 1.65rem);
            font-weight: 700;
            line-height: 1.25;
            letter-spacing: -0.03em;
        }
        .install-glass-card .card-body {
            padding: 1.25rem 0 1rem;
        }
        .install-glass-card .card-footer {
            display: flex;
            justify-content: flex-end;
            border-top: 1px solid #e2e8f0;
            padding: 1.25rem 0 0;
            margin-top: 0.5rem;
        }

        /* Next / Action Button */
        #next-button,
        button[type="submit"]#next-button {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            background: var(--primary-gradient) !important;
            color: #ffffff !important;
            font-weight: 600 !important;
            font-size: 15px !important;
            letter-spacing: 0.025em !important;
            padding: 12px 32px !important;
            border-radius: 12px !important;
            border: none !important;
            box-shadow: 0 8px 20px -4px rgba(16, 185, 129, 0.45) !important;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
            cursor: pointer !important;
        }
        #next-button:hover:not(:disabled),
        button[type="submit"]#next-button:hover:not(:disabled) {
            transform: translateY(-2px) !important;
            box-shadow: 0 12px 25px -4px rgba(16, 185, 129, 0.6) !important;
            filter: brightness(1.05) !important;
        }
        #next-button:active:not(:disabled),
        button[type="submit"]#next-button:active:not(:disabled) {
            transform: translateY(0) !important;
            box-shadow: 0 4px 12px -2px rgba(16, 185, 129, 0.4) !important;
        }
        #next-button:disabled,
        button[type="submit"]#next-button:disabled {
            opacity: 0.6 !important;
            cursor: not-allowed !important;
            box-shadow: none !important;
        }

        /* Animated Logo */
        .akaunting-logo-glow {
            filter: drop-shadow(0 4px 12px rgba(16, 185, 129, 0.3));
            transition: transform 0.3s ease;
        }
        .akaunting-logo-glow:hover {
            transform: scale(1.05);
        }

        .install-page-shell .swiper-slide h1 {
            color: #f8fafc !important;
            text-align: center;
            text-shadow: 0 2px 20px rgba(15, 23, 42, 0.4);
        }

        @media (max-width: 1023px) {
            .install-page-shell > .relative {
                min-height: 100vh;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                scroll-behavior: auto !important;
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>

    @stack('css')

    @stack('stylesheet')

    <script type="text/javascript"><!--
        var url = '{{ url("/") }}';
        var app_url = '{{ config("app.url") }}';
    //--></script>

    @stack('js')

    <script type="text/javascript"><!--
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>;

        var flash_notification = {!! (session()->has('flash_notification')) ? json_encode(session()->get('flash_notification')) : 'false' !!};
    //--></script>

    {{ session()->forget('flash_notification') }}

    @stack('scripts')

    @stack('head_end')
</head>
