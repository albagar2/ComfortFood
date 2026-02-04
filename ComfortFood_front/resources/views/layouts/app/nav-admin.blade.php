<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-app-bg dark:bg-zinc-950 font-sans antialiased relative">
    <!-- Fondos decorativos para resaltar el efecto cristal -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none -z-10">
        <div class="absolute top-0 left-0 w-full h-full stripe-bg"></div>
        <div
            class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] bg-blue-500/10 blur-[120px] rounded-full animate-pulse">
        </div>
    </div>

    <flux:header sticky
        class="!bg-navy-dark/80 backdrop-blur-xl backdrop-saturate-150 border-b border-white/10 py-5 z-50">
        <!-- Logout on the left (Desktop) -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <flux:button type="submit" variant="ghost" icon="arrow-right-start-on-rectangle"
                class="text-xs uppercase tracking-wider !text-white">
                {{ __('CERRAR SESIÓN') }}
            </flux:button>
        </form>

        <flux:spacer />

        <flux:spacer class="max-lg:hidden" />

        <!-- Logo and Theme Switcher on the right -->
        <div class="flex items-center gap-2">
            <x-appearance-dropdown />
            <x-app-logo href="{{ route('dashboard') }}" wire:navigate />
        </div>
    </flux:header>

    {{ $slot }}

    @fluxScripts
</body>

</html>