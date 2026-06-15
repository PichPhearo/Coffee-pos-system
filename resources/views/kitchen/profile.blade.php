<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Profile | Doray's coffee</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        espresso: '#1C1410',
                        roast: '#2D1B0E',
                        crema: '#C8A96E',
                        cream: '#FDF8F2',
                        brown: {
                            50: '#FAF5EF',
                            100: '#F2E4CC',
                            200: '#E4C99A',
                            300: '#CFA76A',
                            400: '#B8863E',
                            500: '#8B5E2A',
                            600: '#6B4420',
                            700: '#4E2F14',
                            800: '#33200D',
                            900: '#1C1208',
                        },
                    },
                    fontFamily: {
                        serif: ['"DM Serif Display"', 'serif'],
                        sans: ['"DM Sans"', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'DM Sans', sans-serif;
        }

        /* Tailwind-CDN-friendly form input styles to match kitchen aesthetic */
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            background-color: #fff;
            border: 1px solid #F2E4CC;
            border-radius: 0.75rem;
            padding: 0.55rem 0.85rem;
            font-size: 0.875rem;
            color: #1C1410;
            outline: none;
            transition: border-color .15s, box-shadow .15s;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #C8A96E;
            box-shadow: 0 0 0 3px rgba(200, 169, 110, 0.18);
        }

        label {
            color: #4E2F14;
            font-weight: 500;
            font-size: 0.8rem;
        }
    </style>
</head>

<body class="bg-brown-50 min-h-screen flex flex-col">

    {{-- ── Top bar ── --}}
    <header class="bg-espresso border-b border-white/[0.06] px-6 py-3 flex items-center justify-between shrink-0">

        {{-- Brand --}}
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-crema flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 fill-espresso" viewBox="0 0 24 24">
                    <path d="M18.5 3H6c-1.1 0-2 .9-2 2v5.71c0 3.83 2.95 7.18 6.78 7.29 3.96.12 7.22-3.06 7.22-7V8h.5c1.93 0 3.5-1.57 3.5-3.5S20.43 1 18.5 1zm0 5H16V5h2.5c.83 0 1.5.67 1.5 1.5S19.33 8 18.5 8zM4 19h16v2H4v-2z" />
                </svg>
            </div>
            <div>
                <p class="font-serif text-cream text-base leading-none">Doray's coffee</p>
                <p class="text-crema/60 text-[10px] tracking-widest uppercase mt-0.5">Profile Settings</p>
            </div>
        </div>

        {{-- Back + User + Logout --}}
        <div class="flex items-center gap-2">
            <a href="{{ route('kitchen.index') }}"
                class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white/[0.06] text-crema text-xs font-medium hover:bg-white/[0.1] transition-all">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Back to Kitchen
            </a>

            <div class="hidden sm:flex items-center gap-2.5 px-3 py-1.5 rounded-xl bg-white/[0.06]">
                <div class="w-6 h-6 rounded-full bg-brown-700 flex items-center justify-center shrink-0">
                    <span class="text-crema text-[10px] font-semibold uppercase">
                        {{ substr(Auth::user()->name ?? 'K', 0, 1) }}
                    </span>
                </div>
                <span class="text-cream text-xs font-medium">{{ Auth::user()->name ?? 'Kitchen Staff' }}</span>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-8 h-8 rounded-lg flex items-center justify-center text-brown-400 hover:text-red-400 hover:bg-red-950/30 transition-all"
                    title="Logout">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </button>
            </form>
        </div>
    </header>

    {{-- ── Main content ── --}}
    <main class="flex-1 px-6 py-6 overflow-auto">
        <div class="max-w-3xl mx-auto space-y-5">

            {{-- Page heading --}}
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="font-serif text-2xl text-espresso leading-tight">My Profile</h1>
                    <p class="text-xs text-brown-400 mt-1">Manage your account information and security</p>
                </div>
                <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-cream border border-brown-100 text-[11px] font-medium text-brown-600">
                    <span class="w-1.5 h-1.5 rounded-full bg-crema"></span>
                    {{ ucfirst(Auth::user()->role ?? 'Barista') }}
                </span>
            </div>

            {{-- Profile information --}}
            <section class="bg-cream rounded-2xl border border-brown-100 border-t-4 border-t-crema shadow-sm overflow-hidden">
                <div class="px-5 pt-4 pb-3 border-b border-brown-100">
                    <p class="text-[10px] uppercase tracking-widest text-brown-400">Account</p>
                    <h2 class="text-lg font-serif text-espresso leading-tight">Profile Information</h2>
                </div>
                <div class="px-5 py-5">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </section>

            {{-- Password --}}
            <section class="bg-cream rounded-2xl border border-brown-100 border-t-4 border-t-blue-400 shadow-sm overflow-hidden">
                <div class="px-5 pt-4 pb-3 border-b border-brown-100">
                    <p class="text-[10px] uppercase tracking-widest text-brown-400">Security</p>
                    <h2 class="text-lg font-serif text-espresso leading-tight">Update Password</h2>
                </div>
                <div class="px-5 py-5">
                    @include('profile.partials.update-password-form')
                </div>
            </section>

            {{-- Delete --}}
            <section class="bg-cream rounded-2xl border border-brown-100 border-t-4 border-t-red-400 shadow-sm overflow-hidden">
                <div class="px-5 pt-4 pb-3 border-b border-brown-100">
                    <p class="text-[10px] uppercase tracking-widest text-brown-400">Danger Zone</p>
                    <h2 class="text-lg font-serif text-espresso leading-tight">Delete Account</h2>
                </div>
                <div class="px-5 py-5">
                    @include('profile.partials.delete-user-form')
                </div>
            </section>
        </div>
    </main>

    {{-- ── Footer ── --}}
    <footer class="shrink-0 px-6 py-3 bg-cream border-t border-brown-100 flex items-center justify-between">
        <span class="text-[11px] text-brown-300">Doray's cofee</span>
        <span class="text-[11px] text-brown-300" id="clock"></span>
    </footer>

    <script>
        function tick() {
            const now = new Date();
            document.getElementById('clock').textContent = now.toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
        }
        tick();
        setInterval(tick, 1000);
    </script>
</body>

</html>