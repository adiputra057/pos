<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google" content="notranslate">

    <title>{{ config('app.name', 'Laravel') }} - Admin Console</title>

    <!-- PWA -->
    <meta name="theme-color" content="#4f46e5">
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/favicon.ico">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/htmx.org@1.9.10"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased overflow-hidden h-screen">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <x-sidebar />

        <!-- Content Area -->
        <div class="relative flex flex-col flex-1 overflow-hidden">
            <!-- Main Content -->
            <main class="w-full flex-grow overflow-y-auto overflow-x-hidden scrollbar-hide flex flex-col"
                  id="main-content"
                  hx-boost="true"
                  hx-select="#main-content"
                  hx-target="#main-content"
                  hx-swap="innerHTML scroll:false">
                
                <!-- Header -->
                <x-topbar :title="$title ?? 'Analytics Dashboard'" :subtitle="$subtitle ?? ''" :showActions="$showActions ?? false" />

                <div class="w-full flex-grow px-6 pt-4 pb-6">
                    {{-- Global Flash Notifications for HTMX partial swaps --}}
                    @if(request()->header('HX-Request'))
                    <div id="flash-notifications">
                        @if(session('success'))
                            <div x-init="$dispatch('notify', { message: '{{ session('success') }}', type: 'success' })"></div>
                        @endif
                        @if(session('error'))
                            <div x-init="$dispatch('notify', { message: '{{ session('error') }}', type: 'error' })"></div>
                        @endif
                        @if(session('warning'))
                            <div x-init="$dispatch('notify', { message: '{{ session('warning') }}', type: 'warning' })"></div>
                        @endif
                        @if(session('info'))
                            <div x-init="$dispatch('notify', { message: '{{ session('info') }}', type: 'info' })"></div>
                        @endif
                        @if($errors->any())
                            @foreach($errors->all() as $error)
                                <div x-init="$dispatch('notify', { message: '{{ $error }}', type: 'error' })"></div>
                            @endforeach
                        @endif
                    </div>
                    @endif

                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>
    <x-toast />
    <x-confirm-modal />

    <script>
        // Allow HTMX to execute scripts in swapped content
        htmx.config.allowScriptTags = true;

        if ('scrollRestoration' in history) {
            history.scrollRestoration = 'manual';
        }

        let _scrollLockInterval = null;

        function lockScrollToTop() {
            const mainEl = document.querySelector('#main-content');
            const wrapperEl = mainEl ? mainEl.parentElement : null;
            
            if (mainEl) mainEl.scrollTop = 0;
            if (wrapperEl) wrapperEl.scrollTop = 0;
            
            if (_scrollLockInterval) clearInterval(_scrollLockInterval);
            _scrollLockInterval = setInterval(function() {
                if (mainEl) mainEl.scrollTop = 0;
                if (wrapperEl) wrapperEl.scrollTop = 0;
            }, 10);
            setTimeout(function() {
                clearInterval(_scrollLockInterval);
                _scrollLockInterval = null;
            }, 600);
        }

        document.body.addEventListener('htmx:beforeSwap', function() {
            lockScrollToTop();
        });

        document.body.addEventListener('htmx:afterSwap', function(evt) {
            // Re-execute any <script> tags injected by HTMX manually
            const target = evt.detail.elt;
            target.querySelectorAll('script').forEach(function(oldScript) {
                const newScript = document.createElement('script');
                Array.from(oldScript.attributes).forEach(function(attr) {
                    newScript.setAttribute(attr.name, attr.value);
                });
                newScript.textContent = oldScript.textContent;
                oldScript.parentNode.replaceChild(newScript, oldScript);
            });
        });

        document.body.addEventListener('htmx:afterSettle', function(evt) {
            // Update Sidebar active state
            window.dispatchEvent(new CustomEvent('nav-changed', { 
                detail: { url: window.location.pathname } 
            }));

            // Reinitialize Alpine.js on the swapped content
            if (window.Alpine && typeof window.Alpine.initTree === 'function') {
                const target = evt.detail.elt || document.getElementById('main-content');
                if (target) {
                    window.Alpine.initTree(target);
                }
            }

            // Call page-specific chart init if defined
            if (typeof window.__initPageCharts === 'function') {
                window.__initPageCharts();
            }
        });
    </script>
</body>
</html>
