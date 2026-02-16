<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Attendance System'))</title>

    <!-- Modern Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    @if(file_exists(public_path('build/manifest.json')))
            @php
                $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
            @endphp
            <link rel="stylesheet" href="{{ asset('build/' . $manifest['resources/css/app.css']['file']) }}">
            <script type="module" src="{{ asset('build/' . $manifest['resources/js/app.js']['file']) }}"></script>
        @else
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

    <!-- Alpine.js for interactive components -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        body {
            font-family: 'Inter', sans-serif;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 antialiased h-full">

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        @include('partials.sidebar')

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Header -->
            @include('partials.header')

            <!-- Content -->
            <main class="flex-1 overflow-y-auto bg-slate-50 px-8 py-8 custom-scrollbar">
                <!-- Session Alerts -->
                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                        class="mb-6 bg-emerald-50 border border-emerald-100 rounded-2xl p-4 flex items-center justify-between shadow-sm shadow-emerald-100/50">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-500 text-white flex items-center justify-center">
                                <i data-lucide="check-circle" class="w-4 h-4"></i>
                            </div>
                            <p class="text-sm font-bold text-emerald-800">{{ session('success') }}</p>
                        </div>
                        <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 transition-colors">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div x-data="{ show: true }" x-show="show"
                        class="mb-6 bg-rose-50 border border-rose-100 rounded-2xl p-4 flex items-center justify-between shadow-sm shadow-rose-100/50 text-rose-800">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-rose-500 text-white flex items-center justify-center">
                                <i data-lucide="alert-circle" class="w-4 h-4"></i>
                            </div>
                            <p class="text-sm font-bold">{{ session('error') }}</p>
                        </div>
                        <button @click="show = false" class="text-rose-400 hover:text-rose-600 transition-colors">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>
                @endif

                @if(isset($slot))
                    {{ $slot }}
                @else
                    @yield('content')
                @endif
            </main>
        </div>
    </div>

    <script>
        // Sidebar State Management (Vanilla JS)
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('sidebarToggle');
        const iconOpen = document.getElementById('toggleIconOpen');
        const iconClose = document.getElementById('toggleIconClose');

        function updateIcons(isCollapsed) {
            if (isCollapsed) {
                iconOpen.classList.remove('opacity-0', 'scale-50', 'rotate-90');
                iconClose.classList.add('opacity-0', 'scale-50', '-rotate-90');
                iconClose.classList.add('pointer-events-none');
                iconOpen.classList.remove('pointer-events-none');
            } else {
                iconOpen.classList.add('opacity-0', 'scale-50', 'rotate-90');
                iconClose.classList.remove('opacity-0', 'scale-50', '-rotate-90');
                iconClose.classList.remove('pointer-events-none');
                iconOpen.classList.add('pointer-events-none');
            }
        }

        // Load initial state
        const isCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
        if (isCollapsed) {
            sidebar.classList.add('w-20');
            sidebar.classList.remove('w-64');
        } else {
            sidebar.classList.add('w-64');
            sidebar.classList.remove('w-20');
        }
        updateIcons(isCollapsed);

        // Toggle logic
        toggleBtn.addEventListener('click', () => {
            const willCollapse = !sidebar.classList.contains('w-20');
            sidebar.classList.toggle('w-64');
            sidebar.classList.toggle('w-20');
            localStorage.setItem('sidebar-collapsed', willCollapse);
            updateIcons(willCollapse);
        });

        // Initialize Lucide Icons
        lucide.createIcons();
    </script>

    @stack('scripts')

</body>

</html>