<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:header sticky class="!bg-navy-dark backdrop-blur-xl border-b border-zinc-200/50 dark:border-zinc-800/50 py-5">
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