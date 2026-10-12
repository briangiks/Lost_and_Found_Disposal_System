<!DOCTYPE html>
<html lang="en" x-data="{ showPwd: false }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login · Strathmore Lost &amp; Found Disposal System</title>
    <meta name="description" content="Admin login for the Strathmore University Lost &amp; Found Disposal System.">
    <link rel="icon" href="{{ asset('images/strathmore-logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js" defer></script>
    <script>
        tailwind.config = { theme: { extend: { colors: { brand: { red: '#b5253b', blue: '#2b4c7e', bluedark: '#1c3357' } } } } };
    </script>
</head>
<body class="min-h-screen grid lg:grid-cols-[1.1fr_1fr] bg-white relative overflow-hidden">

    <div class="relative hidden lg:block">
        <img src="{{ asset('images/login-campus.png') }}" alt="Strathmore University campus" class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-b from-black/30 via-black/5 to-transparent"></div>

        <div class="relative z-10 flex items-center gap-3 p-8">
            <img src="{{ asset('images/strathmore-logo.png') }}" alt="Strathmore University" class="h-16 w-auto drop-shadow">
            <div class="text-white leading-tight drop-shadow">
                <div class="text-lg font-semibold tracking-wide">Strathmore</div>
                <div class="text-[11px] tracking-[0.28em] text-white/90">UNIVERSITY</div>
            </div>
        </div>

        <div class="absolute z-10 bottom-32 left-10 right-10">
            <h1 class="text-white text-4xl xl:text-5xl font-bold leading-tight drop-shadow-lg">
                Lost and Found<br>Department
            </h1>
            <div class="mt-3 h-1 w-24 bg-brand-red rounded"></div>
            <p class="mt-4 text-white/95 text-base drop-shadow">
                Reuniting lost items<br>with their rightful owners.
            </p>
        </div>

        <svg viewBox="0 0 800 200" preserveAspectRatio="none" class="absolute bottom-0 left-0 w-full h-40 z-0">
            <path d="M0,120 C250,20 550,220 800,80 L800,200 L0,200 Z" fill="#b5253b" opacity="0.9"/>
            <path d="M0,160 C250,60 550,260 800,120 L800,200 L0,200 Z" fill="#1c3357" opacity="0.95"/>
        </svg>
    </div>

    <div class="flex items-center justify-center p-6 sm:p-10 bg-white relative">
        <div class="pointer-events-none absolute inset-0 opacity-30" style="background-image:radial-gradient(circle,#cbd5e1 1px,transparent 1px);background-size:22px 22px"></div>

        <form method="POST" action="{{ route('login.attempt') }}" class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl p-8 sm:p-10 space-y-6 border">
              @if ($errors->any())
                <div class="rounded-lg border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-700">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif
            <div class="flex flex-col items-center text-center">
                <div class="w-16 h-16 rounded-full bg-brand-red/10 flex items-center justify-center">
                    <x-icon name="user" class="w-8 h-8 text-brand-red" />
                </div>
                <h2 class="mt-4 text-3xl font-bold">
                    <span class="text-brand-bluedark">Welcome </span><span class="text-brand-red">Back!</span>
                </h2>
                <p class="text-sm text-slate-500 mt-1">Sign in to access the Lost and Found System</p>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="text-sm font-semibold text-slate-800">Email Address</label>
                    <div class="mt-1 relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"><x-icon name="mail" class="w-4 h-4" /></span>
                        <input type="email" name="email" value="{{ old('email', 'admin@strathmore.edu') }}" required
                               class="w-full pl-10 pr-3 py-3 rounded-lg border border-slate-300 bg-white focus:outline-none focus:ring-2 focus:ring-brand-red/40">
                    </div>
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-800">Password</label>
                    <div class="mt-1 relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"><x-icon name="lock" class="w-4 h-4" /></span>
                        <input :type="showPwd ? 'text' : 'password'" name="password" value="Demo1234" required
                               class="w-full pl-10 pr-10 py-3 rounded-lg border border-slate-300 bg-white focus:outline-none focus:ring-2 focus:ring-brand-red/40">
                        <button type="button" @click="showPwd = !showPwd" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400" aria-label="Toggle password visibility">
                            <x-icon name="eye-off" class="w-4 h-4" x-show="showPwd" x-cloak />
                            <x-icon name="eye" class="w-4 h-4" x-show="!showPwd" />
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="remember" value="1" checked class="accent-brand-red w-4 h-4">
                        <span>Remember Me</span>
                    </label>
                    <a class="text-brand-red font-semibold hover:underline" href="#">Forgot Password?</a>
                </div>
            </div>

            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-lg bg-brand-red text-white font-semibold hover:opacity-95 shadow-md">
                <x-icon name="lock" class="w-4 h-4" /> Sign In
            </button>

            <div class="flex items-center gap-3 text-xs text-slate-400">
                <div class="flex-1 h-px bg-slate-200"></div>
                <x-icon name="shield-check" class="w-4 h-4 text-brand-red" />
                <div class="flex-1 h-px bg-slate-200"></div>
            </div>

            <div class="text-center space-y-1">
                <div class="inline-flex items-center gap-2 text-xs text-slate-500">
                    <x-icon name="shield-check" class="w-3 h-3 text-brand-red" />
                    Authorized staff only. All activities are monitored.
                </div>
                <div class="text-xs text-slate-400">© Strathmore University</div>
                <div class="text-[11px] text-slate-400 pt-1">
                    Demo credentials are pre-filled above — just click Sign In.
                </div>
            </div>
        </form>
    </div>
</body>
</html>
