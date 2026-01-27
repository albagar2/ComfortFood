<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ComfortFood - Bienvenida</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-zinc-900 bg-white dark:bg-zinc-900 dark:text-zinc-100">
        <div class="min-h-screen flex flex-col">
            <!-- Header -->
            <header class="border-b border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                    <div class="flex items-center gap-8">
                        <a href="#" class="flex items-center gap-2">
                            <img src="{{ asset('images/logo.png') }}" alt="ComfortFood" class="size-10 rounded-lg">
                            <span class="sr-only">ComfortFood</span>
                        </a>
                        <nav class="hidden md:flex gap-6 text-sm font-medium text-zinc-600 dark:text-zinc-400">
                            <a href="#" class="hover:text-zinc-900 dark:hover:text-white flex items-center gap-1">
                                ¿Por qué usar ComfortFood?
                                <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /></svg>
                            </a>
                            <a href="#" class="hover:text-zinc-900 dark:hover:text-white flex items-center gap-1">
                                Nuestros Restaurantes
                                <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /></svg>
                            </a>
                            <a href="#" class="hover:text-zinc-900 dark:hover:text-white flex items-center gap-1">
                                Planes
                                <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /></svg>
                            </a>
                            <a href="#" class="hover:text-zinc-900 dark:hover:text-white">Términos y Políticas de privacidad</a>
                        </nav>
                    </div>
                    <div class="flex items-center gap-4">
                        <button class="p-2 text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418" />
                            </svg>
                        </button>
                        <div class="h-6 w-px bg-zinc-200 dark:bg-zinc-700"></div>
                        @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">Iniciar sesión</a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-4 py-2 bg-zinc-600 text-white rounded-md text-sm font-medium hover:bg-zinc-700">¡Comienza ya!</a>
                            @endif
                        @endauth
                    @endif
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-grow">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                    <div>
                        <h1 class="text-4xl md:text-5xl font-bold text-zinc-900 dark:text-white mb-6">
                            Conectamos a los restaurantes locales
                        </h1>
                        <p class="text-lg text-zinc-600 dark:text-zinc-400 mb-8 max-w-lg">
                            Descubre los menús del día, comparte tus platos y mantente al tanto de todo lo que ocurre en la gastronomía local. Todo en un mismo espacio que une a cocineros y comensales.
                        </p>
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-white hover:bg-zinc-50 border-zinc-300 text-zinc-900 shadow-sm relative">
                            Regístrate
                        </a>
                        <div class="mt-4">
                            <span class="text-sm font-semibold text-zinc-900 dark:text-white">@comfortfood</span>
                        </div>
                    </div>
                    <div class="bg-zinc-100 dark:bg-zinc-800 rounded-lg aspect-video flex items-center justify-center">
                        <svg class="size-24 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                        </svg>
                    </div>
                </div>
            </main>

            <!-- Footer -->
            <footer class="w-full py-4 border-t border-white/10 bg-zinc-900/50 backdrop-blur-sm">
                <div class="container mx-auto px-6">
                    <div class="flex flex-col items-center justify-between space-y-4 md:flex-row md:space-y-0">

                        <div class="flex items-center space-x-2">
                                <span class="text-sm font-semibold tracking-tighter text-indigo-100">
                                    ComfortFood<span class="text-indigo-400">.</span>
                                </span>
                            <span class="text-xs text-indigo-200/50">© 2026</span>
                        </div>

                        <div class="text-[11px] uppercase tracking-[0.2em] text-gray-400 font-light text-center">
                            Diseñado y Desarrollado por
                            <span class="text-indigo-50 font-medium italic">Alba García</span>
                            <span class="mx-1 text-gray-500">&</span>
                            <span class="text-indigo-50 font-medium italic">Matilde Jiménez</span>
                        </div>

                        <div class="flex items-center">
                            <a href="https://blogsaverroes.juntadeandalucia.es/iesmarquesdecomares/"
                               title="IES MARQUÉS DE COMARES"
                               target="_blank"
                               class="px-3 py-1 text-[10px] font-bold tracking-widest text-indigo-100 border border-gray-600 rounded-md hover:border-indigo-400 hover:text-white transition-colors">
                                2º DAW
                            </a>
                        </div>

                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
