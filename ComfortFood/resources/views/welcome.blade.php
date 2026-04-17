<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ComfortFood - Bienvenida</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
    <style>
        .stripe-bg {
            background: linear-gradient(90deg,
                    #fbeee6 0%, #fbeee6 10%,
                    #f9e7dc 10%, #f9e7dc 20%,
                    #f4e7e6 20%, #f4e7e6 30%,
                    #efe7f0 30%, #efe7f0 40%,
                    #eae7f9 40%, #eae7f9 50%,
                    #e5e7ff 50%, #e5e7ff 60%,
                    #e0e7ff 60%, #e0e7ff 70%,
                    #dbe7ff 70%, #dbe7ff 80%,
                    #d6e7ff 80%, #d6e7ff 90%,
                    #d1e7ff 90%, #d1e7ff 100%);
            opacity: 0.3;
            mask-image: linear-gradient(to bottom, black, transparent);
        }
    </style>
</head>

<body class="font-sans antialiased text-[#2d365e] bg-app-bg dark:bg-zinc-950 dark:text-zinc-100 overflow-x-hidden">
    <!-- Background Stripes -->
    <div class="fixed inset-0 stripe-bg z-0 pointer-events-none"></div>

    <div class="relative z-10 min-h-screen flex flex-col">
        <!-- Header -->
        <header class="!bg-navy-dark backdrop-blur-xl sticky top-0 z-50 border-b border-slate-700/30 !text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
                <div class="flex items-center gap-10">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 transition-transform hover:scale-105">
                        <img src="{{ asset('images/logo.png') }}" alt="ComfortFood"
                            class="size-12 rounded-2xl shadow-sm">
                        <span class="sr-only">ComfortFood</span>
                    </a>
                    <nav class="hidden lg:flex gap-8 text-[13px] font-bold text-white/70 tracking-tight">
                        <a href="#" class="hover:text-pastel-orange transition-colors">¿Por
                            qué usar
                            ComfortFood?</a>
                        <a href="#" class="hover:text-[#3b4a81] dark:hover:text-white transition-colors">Funciones</a>
                        <a href="#" class="hover:text-[#3b4a81] dark:hover:text-white transition-colors">Nuestros
                            restaurantes</a>
                        <a href="#" class="hover:text-[#3b4a81] dark:hover:text-white transition-colors">Planes</a>
                    </nav>
                </div>

                <div class="flex items-center gap-6">
                    <button
                        class="text-white-500 hover:text-white-800 dark:text-white-400 dark:hover:text-white transition-colors">
                        <flux:icon.globe-alt class="size-5" />
                    </button>

                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="text-sm font-bold text-[#3b4a81] hover:underline dark:text-blue-400">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}"
                            class="text-sm font-bold text-pastel-orange hover:text-pastel-orange/80 transition-colors">Iniciar
                            sesión</a>
                        <a href="{{ route('login') }}"
                            class="px-6 py-2.5 bg-blue-500 text-white rounded-xl text-sm font-bold shadow-xl shadow-blue-500/20 hover:bg-blue-600 hover:shadow-2xl transition-all hover:-translate-y-0.5 active:translate-y-0">
                            ¡Comienza gratis!
                        </a>
                    @endauth
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-grow flex items-center">
            <div
                class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-20 grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
                <div class="space-y-12 text-center lg:text-left">
                    <div class="space-y-6">
                        <h1
                            class="text-5xl lg:text-7xl font-extrabold leading-[1.1] text-[#2d365e] dark:text-white tracking-tight">
                            Conectamos a los <span class="block text-zinc-800 dark:text-zinc-100">restaurantes
                                locales</span>
                            <span class="text-[#3b4a81] dark:text-blue-400 lg:block">con quienes disfrutan de su
                                cocina.</span>
                        </h1>
                        <p
                            class="text-lg lg:text-xl text-zinc-600/90 dark:text-zinc-400 max-w-xl mx-auto lg:mx-0 leading-relaxed font-medium">
                            Descubre los menús del día, comparte tus platos y mantente al tanto de todo lo que ocurre en
                            la gastronomía local. Todo en un mismo espacio que une a cocineros y comensales.
                        </p>
                    </div>

                    <div class="flex flex-col items-center lg:items-start gap-4">
                        <flux:dropdown>
                            <flux:button icon-trailing="chevron-down"
                                class="!bg-[#3b4a81] !text-white !border-none !h-14 !inline-flex !items-center !w-60 !justify-center !px-8 !text-lg !font-bold !rounded-xl !shadow-2xl !shadow-blue-900/20 !transition-all hover:!bg-[#2d365e] hover:!shadow-3xl hover:!-translate-y-1 active:!translate-y-0">
                                {{ __('Regístrate') }}
                            </flux:button>

                            <flux:menu class="w-64">
                                <flux:menu.item href="{{ route('register', ['rol' => 'cliente']) }}" icon="user">
                                    Registrarme como Cliente
                                </flux:menu.item>
                                <flux:menu.item href="{{ route('register', ['rol' => 'restaurante']) }}"
                                    icon="building-storefront">
                                    Registrar mi Restaurante
                                </flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>

                        <div class="text-[13px] font-bold tracking-tight text-[#3b4a81]/50 dark:text-white/40">
                            @comfortfood
                        </div>
                    </div>
                </div>

                <div class="relative group">
                    <!-- Image Decoration -->
                    <div
                        class="absolute -inset-6 bg-gradient-to-tr from-[#3b4a81]/5 to-[#fbeee6]/30 rounded-[3.5rem] blur-3xl group-hover:from-[#3b4a81]/15 transition-all duration-700">
                    </div>

                    <div
                        class="relative rounded-[3rem] overflow-hidden shadow-[0_32px_64px_-16px_rgba(45,54,94,0.15)] group-hover:shadow-[0_48px_80px_-16px_rgba(45,54,94,0.2)] transition-all duration-700 aspect-[4/3] sm:aspect-[3/2] lg:aspect-square">
                        <img src="{{ asset('images/welcom.png') }}" alt="ComfortFood Cooking"
                            class="w-full h-full object-cover scale-105 group-hover:scale-100 transition-transform duration-1000 ease-out">
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="w-full py-12 bg-white dark:bg-zinc-900 border-t border-zinc-200/50 dark:border-zinc-800/50">
            <div class="max-w-7xl mx-auto px-6">
                <div class="flex flex-col md:flex-row items-center justify-between gap-8">
                    <div class="flex items-center gap-4">
                        <img src="{{ asset('images/logo.png') }}" alt="ComfortFood" class="size-10 rounded-xl">
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-[#2d365e] dark:text-white">ComfortFood.</span>
                            <span class="text-xs text-zinc-500">© 2026. Todos los derechos reservados.</span>
                        </div>
                    </div>

                    <div class="text-[11px] uppercase tracking-[0.25em] text-zinc-400 font-bold text-center">
                        Developed by <span class="text-zinc-600 dark:text-zinc-300">Alba García</span> & <span
                            class="text-zinc-600 dark:text-zinc-300">Matilde Jiménez</span>
                    </div>

                    <div class="flex items-center gap-5">
                        <a href="https://blogsaverroes.juntadeandalucia.es/iesmarquesdecomares/" target="_blank"
                            class="px-4 py-1.5 text-[11px] font-black tracking-widest text-[#3b4a81] border-2 border-[#3b4a81]/10 rounded-full hover:bg-zinc-50 transition-colors dark:text-blue-400 dark:border-blue-900/30">
                            2º DAW
                        </a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    @fluxScripts
</body>

</html>