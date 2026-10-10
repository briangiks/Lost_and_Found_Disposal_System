<!DOCTYPE html>
<html lang="en" x-data="themeStore()" x-init="init()" :class="dark ? 'dark' : ''">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') · Strathmore Lost &amp; Found</title>
    <meta name="description" content="Strathmore University Lost &amp; Found Disposal System.">
    <link rel="icon" href="{{ asset('images/strathmore-logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js" defer></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            red: '#b5253b',
                            blue: '#2b4c7e',
                            bluedark: '#1c3357',
                        },
                    },
                },
            },
        };
        function themeStore() {
            return {
                dark: localStorage.getItem('lnf-theme') === 'dark',
                init() {},
                toggle() {
                    this.dark = !this.dark;
                    localStorage.setItem('lnf-theme', this.dark ? 'dark' : 'light');
                },
            };
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: ui-sans-serif, system-ui, -apple-system, 'Segoe UI', sans-serif; }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-slate-50 text-slate-900 dark:bg-slate-900 dark:text-slate-100">

    <header class="border-b bg-white dark:bg-slate-800 dark:border-slate-700 sticky top-0 z-30">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 h-16 flex items-center justify-between gap-4">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 shrink-0">
                <img src="{{ asset('images/strathmore-logo.png') }}" alt="Strathmore University" class="h-9 w-auto">
                <div class="leading-tight hidden sm:block">
                    <div class="text-sm font-semibold text-brand-bluedark dark:text-white">Strathmore University</div>
                    <div class="text-[11px] text-slate-500 dark:text-slate-400 -mt-0.5">Lost &amp; Found · Disposal System</div>
                </div>
            </a>

            <nav class="hidden md:flex items-center gap-1">
                @php
                    $navItems = [
                        ['route' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'layout-dashboard'],
                        ['route' => 'disposal.index', 'label' => 'Disposal Workflow', 'icon' => 'workflow'],
                        ['route' => 'emails.index', 'label' => 'Email Preview', 'icon' => 'mail'],
                        ['route' => 'audit.index', 'label' => 'Audit Log', 'icon' => 'clipboard-list'],
                    ];
                @endphp
                @foreach ($navItems as $item)
                    <a href="{{ route($item['route']) }}"
                       class="flex items-center gap-2 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs($item['route']) || ($item['route'] === 'dashboard' && request()->routeIs('items.*')) ? 'bg-brand-blue text-white' : 'text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700' }}">
                        <x-icon :name="$item['icon']" class="w-4 h-4" />
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="flex items-center gap-2">
                <button @click="toggle()" class="p-2 rounded-md hover:bg-slate-100 dark:hover:bg-slate-700" aria-label="Toggle light/dark mode">
                    <x-icon name="sun" class="w-5 h-5" x-show="dark" x-cloak />
                    <x-icon name="moon" class="w-5 h-5" x-show="!dark" />
                </button>
                <button class="relative p-2 rounded-md hover:bg-slate-100 dark:hover:bg-slate-700" aria-label="Notifications">
                    <x-icon name="bell" class="w-5 h-5" />
                    <span class="absolute top-1 right-1 w-2 h-2 bg-brand-red rounded-full"></span>
                </button>
                <div class="hidden sm:flex items-center gap-2 pl-3 border-l dark:border-slate-700">
                    <div class="w-9 h-9 rounded-full bg-brand-red/10 border border-brand-red/20 flex items-center justify-center overflow-hidden">
                        <img src="{{ asset('images/admin-avatar.png') }}" alt="Admin" class="w-7 h-7 object-contain">
                    </div>
                    <div class="text-sm leading-tight">
                        <div class="font-medium">{{ auth()->user()->name ?? 'Admin' }}</div>
                        <div class="text-[11px] text-slate-500 dark:text-slate-400">L&amp;F Officer</div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="ml-2 p-2 rounded-md hover:bg-slate-100 dark:hover:bg-slate-700" aria-label="Sign out" title="Sign out">
                            <x-icon name="log-out" class="w-4 h-4" />
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <nav class="md:hidden flex items-center gap-1 px-4 pb-3 overflow-x-auto">
            @foreach ($navItems as $item)
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-1.5 px-3 py-1.5 rounded-md text-xs font-medium whitespace-nowrap {{ request()->routeIs($item['route']) ? 'bg-brand-blue text-white' : 'text-slate-700 dark:text-slate-200 bg-slate-100 dark:bg-slate-700' }}">
                    <x-icon :name="$item['icon']" class="w-3.5 h-3.5" />
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>
    </header>

    @if (session('status'))
        <div class="mx-auto max-w-7xl w-full px-4 sm:px-6 pt-4">
            <div class="rounded-lg border border-brand-blue/30 bg-brand-blue/5 dark:bg-brand-blue/10 px-4 py-3 text-sm text-brand-bluedark dark:text-slate-100 flex items-center gap-2">
                <x-icon name="check-circle" class="w-4 h-4 shrink-0" />
                {{ session('status') }}
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="mx-auto max-w-7xl w-full px-4 sm:px-6 pt-4">
            <div class="rounded-lg border border-red-300 bg-red-50 dark:bg-red-900/20 dark:border-red-800 px-4 py-3 text-sm text-red-700 dark:text-red-300">
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <main class="flex-1">
        @yield('content')
    </main>

    <footer class="border-t bg-white dark:bg-slate-800 dark:border-slate-700">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 py-4 text-xs text-slate-500 dark:text-slate-400 flex flex-col sm:flex-row gap-1 justify-between">
            <span>© {{ date('Y') }} Strathmore University · Lost &amp; Found Disposal System</span>
            <span>Built with Laravel {{ app()->version() }}</span>
        </div>
    </footer>
</body>
</html>
