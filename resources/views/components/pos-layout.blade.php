<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="google" content="notranslate">

        <title>{{ config('app.name', 'Laravel') }} - POS</title>

        <!-- PWA -->
        <meta name="theme-color" content="#1e66f5">
        <link rel="manifest" href="/manifest.json">
        <link rel="apple-touch-icon" href="/favicon.ico">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            [x-cloak] { display: none !important; }
            .scrollbar-hide::-webkit-scrollbar { display: none !important; }
            .scrollbar-hide { -ms-overflow-style: none !important; scrollbar-width: none !important; }
        </style>

        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.store('theme', {
                    darkMode: localStorage.getItem('darkMode') === 'true',
                    init() {
                        if (this.darkMode) {
                            document.documentElement.classList.add('dark');
                        }
                    },
                    toggle() {
                        this.darkMode = !this.darkMode;
                        localStorage.setItem('darkMode', this.darkMode);
                        if (this.darkMode) {
                            document.documentElement.classList.add('dark');
                        } else {
                            document.documentElement.classList.remove('dark');
                        }
                    }
                })
            })
        </script>
    </head>
    <body x-init="$store.theme.init()" class="font-sans antialiased bg-gray-100 dark:bg-[#0B0E14] transition-colors duration-300 scrollbar-hide overflow-hidden">
        <div class="min-h-screen scrollbar-hide">
            {{ $slot }}
        </div>
        <x-toast />
    </body>
</html>
