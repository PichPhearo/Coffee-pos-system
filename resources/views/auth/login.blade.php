<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In | Doray's Coffee</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500&display=swap" rel="stylesheet">
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

        /* Coffee bean scatter background */
        .bean-bg {
            position: fixed;
            inset: 0;
            overflow: hidden;
            z-index: 0;
            pointer-events: none;
        }

        .bean {
            position: absolute;
            opacity: 0.07;
        }

        @keyframes float-slow {

            0%,
            100% {
                transform: translateY(0px) rotate(var(--r));
            }

            50% {
                transform: translateY(-12px) rotate(calc(var(--r) + 8deg));
            }
        }

        .bean {
            animation: float-slow var(--dur, 8s) ease-in-out infinite;
            animation-delay: var(--delay, 0s);
        }

        /* Input focus glow */
        .input-field {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(200, 169, 110, 0.2);
            color: #FDF8F2;
            border-radius: 0.875rem;
            padding: 0.75rem 1rem;
            width: 100%;
            font-size: 0.925rem;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }

        .input-field::placeholder {
            color: rgba(200, 169, 110, 0.35);
        }

        .input-field:focus {
            border-color: rgba(200, 169, 110, 0.6);
            background: rgba(255, 255, 255, 0.09);
            box-shadow: 0 0 0 3px rgba(200, 169, 110, 0.1);
        }

        .input-field:-webkit-autofill,
        .input-field:-webkit-autofill:focus {
            -webkit-box-shadow: 0 0 0 1000px #2D1B0E inset;
            -webkit-text-fill-color: #FDF8F2;
        }
    </style>
</head>

<body class="min-h-screen bg-espresso flex items-center justify-center p-4">

    {{-- ── Coffee bean SVG background ── --}}
    <div class="bean-bg" aria-hidden="true">

        {{-- Bean SVG path: a coffee bean shape (oval with center crease line) --}}
        {{-- Scattered across the screen at various sizes, rotations, positions --}}

        @php
        $beans = [
        ['x'=>'5%', 'y'=>'8%', 'size'=>44, 'r'=>-20, 'dur'=>9, 'delay'=>0],
        ['x'=>'18%', 'y'=>'3%', 'size'=>28, 'r'=>35, 'dur'=>11, 'delay'=>-2],
        ['x'=>'32%', 'y'=>'12%', 'size'=>52, 'r'=>10, 'dur'=>8, 'delay'=>-4],
        ['x'=>'50%', 'y'=>'5%', 'size'=>36, 'r'=>-45, 'dur'=>13, 'delay'=>-1],
        ['x'=>'68%', 'y'=>'9%', 'size'=>48, 'r'=>60, 'dur'=>10, 'delay'=>-3],
        ['x'=>'82%', 'y'=>'2%', 'size'=>32, 'r'=>-30, 'dur'=>7, 'delay'=>-5],
        ['x'=>'92%', 'y'=>'14%', 'size'=>56, 'r'=>15, 'dur'=>12, 'delay'=>-2],

        ['x'=>'2%', 'y'=>'30%', 'size'=>38, 'r'=>70, 'dur'=>9, 'delay'=>-1],
        ['x'=>'14%', 'y'=>'45%', 'size'=>26, 'r'=>-55, 'dur'=>11, 'delay'=>-4],
        ['x'=>'88%', 'y'=>'35%', 'size'=>42, 'r'=>25, 'dur'=>8, 'delay'=>0],
        ['x'=>'95%', 'y'=>'55%', 'size'=>30, 'r'=>-15, 'dur'=>14, 'delay'=>-3],

        ['x'=>'8%', 'y'=>'65%', 'size'=>50, 'r'=>40, 'dur'=>10, 'delay'=>-2],
        ['x'=>'22%', 'y'=>'80%', 'size'=>34, 'r'=>-70, 'dur'=>8, 'delay'=>-5],
        ['x'=>'40%', 'y'=>'88%', 'size'=>46, 'r'=>20, 'dur'=>12, 'delay'=>-1],
        ['x'=>'58%', 'y'=>'82%', 'size'=>28, 'r'=>-35, 'dur'=>9, 'delay'=>-3],
        ['x'=>'74%', 'y'=>'75%', 'size'=>54, 'r'=>55, 'dur'=>11, 'delay'=>0],
        ['x'=>'88%', 'y'=>'85%', 'size'=>38, 'r'=>-10, 'dur'=>7, 'delay'=>-4],

        ['x'=>'28%', 'y'=>'55%', 'size'=>22, 'r'=>80, 'dur'=>15, 'delay'=>-2],
        ['x'=>'62%', 'y'=>'48%', 'size'=>44, 'r'=>-50, 'dur'=>10, 'delay'=>-1],
        ['x'=>'76%', 'y'=>'28%', 'size'=>32, 'r'=>30, 'dur'=>13, 'delay'=>-3],
        ];
        @endphp

        @foreach($beans as $b)
        <div class="bean"
            style="left:{{ $b['x'] }};top:{{ $b['y'] }};--r:{{ $b['r'] }}deg;--dur:{{ $b['dur'] }}s;--delay:{{ $b['delay'] }}s;">
            <svg width="{{ $b['size'] }}" height="{{ (int)($b['size'] * 1.5) }}"
                viewBox="0 0 40 60" fill="none"
                style="transform: rotate({{ $b['r'] }}deg)">
                {{-- Bean body --}}
                <ellipse cx="20" cy="30" rx="18" ry="27" fill="#C8A96E" />
                {{-- Center crease --}}
                <path d="M20 4 Q14 15 14 30 Q14 45 20 56" stroke="#8B5E2A" stroke-width="2.5" stroke-linecap="round" fill="none" />
                <path d="M20 4 Q26 15 26 30 Q26 45 20 56" stroke="#8B5E2A" stroke-width="1" stroke-linecap="round" fill="none" opacity="0.5" />
            </svg>
        </div>
        @endforeach

    </div>

    {{-- ── Login card ── --}}
    <div class="relative z-10 w-full max-w-sm">

        {{-- Card --}}
        <div class="bg-roast/90 backdrop-blur-md rounded-3xl border border-white/[0.07] shadow-2xl overflow-hidden">

            {{-- Top accent line --}}
            <div class="h-1 w-full bg-gradient-to-r from-brown-700 via-crema to-brown-700"></div>

            <div class="px-8 pt-8 pb-9">

                {{-- Brand --}}
                <div class="flex flex-col items-center mb-8">
                    <div class="w-14 h-14 rounded-2xl bg-crema/10 border border-crema/20 flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 fill-crema" viewBox="0 0 24 24">
                            <path d="M18.5 3H6c-1.1 0-2 .9-2 2v5.71c0 3.83 2.95 7.18 6.78 7.29 3.96.12 7.22-3.06 7.22-7V8h.5c1.93 0 3.5-1.57 3.5-3.5S20.43 1 18.5 1zm0 5H16V5h2.5c.83 0 1.5.67 1.5 1.5S19.33 8 18.5 8zM4 19h16v2H4v-2z" />
                        </svg>
                    </div>
                    <h1 class="font-serif text-cream text-3xl leading-none">Doray's Coffee</h1>
                    <p class="text-brown-500 text-xs tracking-widest uppercase mt-2">Staff Portal</p>
                </div>

                {{-- Session status --}}
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-xs font-medium text-brown-400 uppercase tracking-wider mb-2">
                            Email address
                        </label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none">
                                <svg class="w-4 h-4 text-brown-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </span>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="you@example.com"
                                class="input-field pl-10">
                        </div>
                        @if($errors->get('email'))
                        <p class="mt-1.5 text-xs text-red-400 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $errors->get('email')[0] }}
                        </p>
                        @endif
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-xs font-medium text-brown-400 uppercase tracking-wider mb-2">
                            Password
                        </label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none">
                                <svg class="w-4 h-4 text-brown-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </span>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="••••••••"
                                class="input-field pl-10 pr-11">
                            {{-- Toggle visibility --}}
                            <button type="button" onclick="togglePassword()"
                                class="absolute right-3.5 top-1/2 -translate-y-1/2 text-brown-600 hover:text-crema transition-colors">
                                <svg id="eye-icon" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="eye-off-icon" class="w-4 h-4 hidden" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                        @if($errors->get('password'))
                        <p class="mt-1.5 text-xs text-red-400 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $errors->get('password')[0] }}
                        </p>
                        @endif
                    </div>

                    {{-- Remember + Forgot --}}
                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="flex items-center gap-2 cursor-pointer group">
                            <div class="relative">
                                <input id="remember_me" type="checkbox" name="remember" class="sr-only peer">
                                <div class="w-4 h-4 rounded border border-brown-600 bg-white/5 peer-checked:bg-crema peer-checked:border-crema transition-all"></div>
                                <svg class="absolute inset-0 w-4 h-4 text-espresso opacity-0 peer-checked:opacity-100 transition-opacity pointer-events-none" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <span class="text-sm text-brown-400 group-hover:text-brown-300 transition-colors select-none">Remember me</span>
                        </label>

                        @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                            class="text-sm text-brown-400 hover:text-crema transition-colors">
                            Forgot password?
                        </a>
                        @endif
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 px-6 py-3.5 rounded-2xl bg-crema hover:bg-brown-300 text-espresso text-sm font-bold transition-all duration-200 shadow-lg shadow-crema/10 mt-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        Sign in
                    </button>

                </form>

            </div>
        </div>

        {{-- Bottom hint --}}
        <p class="text-center text-brown-700 text-xs mt-5">
            For staff access only. Contact your manager for help.
        </p>

    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const eyeOn = document.getElementById('eye-icon');
            const eyeOff = document.getElementById('eye-off-icon');
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            eyeOn.classList.toggle('hidden', isHidden);
            eyeOff.classList.toggle('hidden', !isHidden);
        }
    </script>

</body>

</html>