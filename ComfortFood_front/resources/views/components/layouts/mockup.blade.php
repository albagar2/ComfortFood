<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? 'ComfortFood' }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @fluxStyles
    </head>
    <body class="min-h-screen bg-zinc-50 dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100">
        <flux:sidebar sticky collapsible="mobile" class="border-r border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-800">
            <flux:sidebar.header>
                <div class="flex items-center gap-2 px-2">
                    <img src="{{ asset('images/logo.png') }}" alt="ComfortFood" class="size-10 rounded-lg">
                </div>
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                <flux:sidebar.item icon="home" href="#" :current="false">{{ __('Inicio') }}</flux:sidebar.item>
                <flux:sidebar.item icon="clock" href="{{ route('orders.history') }}" :current="request()->routeIs('orders.history')">{{ __('Historial Pedidos') }}</flux:sidebar.item>
                <flux:sidebar.item icon="chart-bar" href="#" :current="false">{{ __('Estadísticas') }}</flux:sidebar.item>
                <flux:sidebar.item icon="lifebuoy" href="#" :current="false">{{ __('Soporte') }}</flux:sidebar.item>
            </flux:sidebar.nav>

            <flux:spacer />

            <flux:sidebar.nav>
                <flux:sidebar.item icon="cog-6-tooth" href="#" :current="false">{{ __('Configuración') }}</flux:sidebar.item>
                <flux:sidebar.item icon="arrow-right-start-on-rectangle" href="#">{{ __('Cerrar sesión') }}</flux:sidebar.item>
            </flux:sidebar.nav>
        </flux:sidebar>

        <flux:main>
            <div class="max-w-7xl mx-auto">
                {{ $slot }}
            </div>
        </flux:main>

        @fluxScripts
    </body>
</html>
