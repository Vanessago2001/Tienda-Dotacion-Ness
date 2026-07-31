<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" href="{{ asset('images/logo_happy_store.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                background: linear-gradient(135deg, #0d3f3c 0%, #1c7a74 45%, #40E0D0 100%);
                min-height: 100vh;
                margin: 0;
            }
            .login-box {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(12px);
                border-radius: 35px !important;
                box-shadow: 0 20px 50px rgba(64, 224, 208, 0.35) !important;
                border: 1px solid #b7f3ec;
            }
            .btn-primary {
                background: linear-gradient(135deg, #2ec4b6, #40E0D0) !important;
                color: white !important;
                border: none;
                padding: 12px 24px;
                border-radius: 12px;
                font-weight: 600;
                cursor: pointer;
                transition: .3s;
                box-shadow: 0 4px 14px 0 rgba(64, 224, 208, 0.35) !important;
                display: inline-block;
                text-align: center;
            }
            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(64, 224, 208, 0.5) !important;
            }
            .custom-input:focus {
                border-color: #40E0D0 !important;
                box-shadow: 0 0 0 4px rgba(64, 224, 208, 0.16) !important;
            }
        </style>
    </head>
    <body class="font-sans text-[#40E0D0] antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div>
                <a href="/" style="font-size: 50px; font-weight: 700; color: #ffffff; text-decoration: none;">
                    Dotaciones Ness
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-8 px-8 py-10 login-box">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
