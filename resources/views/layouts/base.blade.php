<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        darkDeep: '#f0f7ff',
                        darkCard: 'rgba(255, 255, 255, 0.85)',
                        glassBg: 'rgba(255, 255, 255, 0.95)',
                        glassBorder: 'rgba(59, 130, 246, 0.08)',
                        accentIndigo: '#4f46e5',
                        accentViolet: '#764ba2',
                        accentEmerald: '#10b981',
                    },
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Customized Bootstrap Stylesheet & Custom CSS -->
    <link rel="stylesheet" href="{{ asset('asset/bootstrap/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{ asset('asset/bootstrap/all.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/bootstrap/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/bootstrap/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/bootstrap/responsive.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <title>{{config('app.name')}} @yield('title')</title>
    
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Outfit', sans-serif !important;
            background-color: #f0f7ff !important;
            color: #0f172a !important;
            overflow-x: hidden;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f0f7ff;
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(79, 70, 229, 0.2);
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(79, 70, 229, 0.4);
        }

        /* Custom glassmorphism utilities for Light Theme */
        .glass-card {
            background: rgba(255, 255, 255, 0.8) !important;
            backdrop-filter: blur(16px) !important;
            -webkit-backdrop-filter: blur(16px) !important;
            border: 1px solid rgba(59, 130, 246, 0.08) !important;
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.04) !important;
        }
        
        .glass-input {
            background: rgba(255, 255, 255, 0.9) !important;
            border: 1px solid rgba(59, 130, 246, 0.15) !important;
            color: #0f172a !important;
            transition: all 0.3s ease !important;
        }
        .glass-input:focus {
            background: #ffffff !important;
            border-color: #4f46e5 !important;
            box-shadow: 0 0 15px rgba(79, 70, 229, 0.15) !important;
            outline: none !important;
        }

        .text-gradient-primary {
            color: #4f46e5 !important;
            background: none !important;
            -webkit-background-clip: initial !important;
            -webkit-text-fill-color: initial !important;
        }

        /* Ambient background glow objects (very subtle) */
        .glow-orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(150px);
            opacity: 0.03;
            pointer-events: none;
            z-index: -1;
        }

        /* Light theme overrides for dark styles */
        .text-white:not(.btn):not(.btn *):not(.badge):not(.badge *):not(.keep-white) {
            color: #0f172a !important;
        }
        .text-gray-200, .text-gray-300, .text-gray-400 {
            color: #334155 !important;
        }
        .text-gray-500 {
            color: #475569 !important;
        }
        .text-indigo-300, .text-indigo-400, .text-indigo-500 {
            color: #4f46e5 !important;
        }
        .text-emerald-400 {
            color: #16a34a !important;
        }
        
        /* Restore dark footer colors */
        footer .text-white {
            color: #ffffff !important;
        }
        footer .text-gray-200 {
            color: #e2e8f0 !important;
        }
        footer .text-gray-300 {
            color: #cbd5e1 !important;
        }
        footer .text-gray-400 {
            color: #94a3b8 !important;
        }
        footer .text-gray-500 {
            color: #64748b !important;
        }
        footer a {
            color: #94a3b8 !important;
        }
        footer a:hover {
            color: #6366f1 !important;
        }
        
        .bg-white\/5 {
            background-color: rgba(59, 130, 246, 0.02) !important;
        }
        .bg-white\/10 {
            background-color: rgba(59, 130, 246, 0.05) !important;
        }
        .bg-black\/20, .bg-black\/30, .bg-black\/40, .bg-black\/60 {
            background-color: rgba(255, 255, 255, 0.95) !important;
            color: #0f172a !important;
            border: 1px solid rgba(59, 130, 246, 0.06) !important;
        }
        
        .border-white\/5 {
            border-color: rgba(59, 130, 246, 0.04) !important;
        }
        .border-white\/10 {
            border-color: rgba(59, 130, 246, 0.08) !important;
        }
        
        .bg-emerald-500\/80, .bg-emerald-500, .bg-emerald-500\/20, .bg-emerald-500\/10 {
            background-color: rgba(16, 185, 129, 0.1) !important;
            color: #047857 !important;
            border-color: rgba(16, 185, 129, 0.2) !important;
        }
        
        .bg-indigo-500\/10, .bg-indigo-500\/20, .bg-indigo-500 {
            background-color: rgba(79, 70, 229, 0.08) !important;
            color: #4f46e5 !important;
            border-color: rgba(79, 70, 229, 0.15) !important;
        }
        
        .bg-red-500\/20, .bg-red-500 {
            background-color: rgba(239, 68, 68, 0.1) !important;
            color: #b91c1c !important;
            border-color: rgba(239, 68, 68, 0.15) !important;
        }
        
        .bg-amber-500\/20, .bg-amber-500 {
            background-color: rgba(245, 158, 11, 0.1) !important;
            color: #b45309 !important;
            border-color: rgba(245, 158, 11, 0.15) !important;
        }

        .btn-outline-light {
            color: #334155 !important;
            border-color: rgba(59, 130, 246, 0.15) !important;
        }
        .btn-outline-light:hover {
            background-color: rgba(59, 130, 246, 0.05) !important;
            color: #0f172a !important;
        }

        select option {
            background-color: #ffffff !important;
            color: #0f172a !important;
        }

        /* ── Images de cards : couverture uniforme quel que soit le format ── */
        .card-thumb {
            position: relative;
            overflow: hidden;
        }
        .card-thumb img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            display: block;
        }
        /* Fallback pour les img sans .card-thumb mais dans un container à hauteur fixe */
        .glass-card img:not([data-no-cover]),
        .event-card img:not([data-no-cover]) {
            object-fit: cover;
            object-position: center;
            display: block;
        }
    </style>
</head>

<body>
    <div id="app">
        <!-- Loading Screen -->
        <div id="chargement" class="fixed inset-0 z-[9999] flex flex-col justify-center items-center bg-[#f0f7ff]">
            <span class="text-xl font-bold tracking-widest text-indigo-600 uppercase mb-6">TGEvent</span>
            <div class="w-48 h-0.5 bg-slate-200 rounded-full overflow-hidden">
                <div class="h-full bg-indigo-500 rounded-full animate-[load_1.2s_ease-in-out_infinite]"></div>
            </div>
        </div>
        <style>
            @keyframes load {
                0%   { width: 0%;   margin-left: 0; }
                50%  { width: 70%;  margin-left: 15%; }
                100% { width: 0%;   margin-left: 100%; }
            }
        </style>

        <!-- #contenu: hidden via native CSS (NO Bootstrap d-none dependency) -->
        <div id="contenu" style="display:none;" class="min-h-screen flex flex-col justify-between">
            <div>
                @include('layouts.publicnavbar')
                <div class="pt-[76px]">
                    @yield('content')
                </div>
            </div>
            @include('layouts.footer')
        </div>
    </div>

    <!-- Guaranteed loading screen removal - runs immediately, no dependencies -->
    <script>
        (function() {
            function showPage() {
                var loader = document.getElementById('chargement');
                var content = document.getElementById('contenu');
                if (loader) loader.style.display = 'none';
                if (content) content.style.display = '';
            }
            // Show page after 400ms regardless of other script states
            setTimeout(showPage, 400);
            // Also trigger on window load as backup
            window.addEventListener('load', function() { setTimeout(showPage, 100); });
        })();
    </script>

    <!-- Bootstrap & Popper JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="{{ asset('asset/bootstrap/bootstrap.min.js') }}"></script>

    <!-- JS Helpers (js.js moved AFTER Bootstrap so d-none class is available) -->
    <script src="{{ asset('asset/bootstrap/all.js') }}"></script>
    <script src="{{ asset('asset/bootstrap/jquery.js') }}"></script>
    <script src="{{ asset('asset/bootstrap/build-bootstrap.js') }}"></script>
    <script src="{{ asset('asset/bootstrap/aos.js') }}"></script>
    <script src="{{ asset('asset/bootstrap/js.js') }}"></script>
    <script src="{{ asset('asset/bootstrap/main.js') }}"></script>
</body>
</html>

