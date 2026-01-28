<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:header sticky class="bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700 py-4">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <!-- Logout on the left (Desktop) -->
            <form method="POST" action="{{ route('logout') }}" class="max-lg:hidden">
                @csrf
                <flux:button type="submit" variant="ghost" icon="arrow-right-start-on-rectangle" class="text-xs uppercase tracking-wider">
                    {{ __('CERRAR SESIÓN') }}
                </flux:button>
            </form>

            <flux:spacer />

            <!-- Search and Selectors (Desktop) -->
            <div class="flex items-center gap-4 max-lg:hidden">
                <flux:input icon="magnifying-glass" placeholder="{{ __('Buscar') }}" class="w-64" />
                
                <flux:select class="w-44 bg-white/5 border-zinc-700/50 rounded-lg" icon="user-group">
                    <flux:select.option icon="user">{{ __('Cliente') }}</flux:select.option>
                    <flux:select.option icon="building-storefront">{{ __('Restaurante') }}</flux:select.option>
                </flux:select>

                <flux:select class="w-44 bg-white/5 border-zinc-700/50 rounded-lg" icon="signal">
                    <flux:select.option icon="check-circle">{{ __('Activo') }}</flux:select.option>
                    <flux:select.option icon="x-circle">{{ __('Inactivo') }}</flux:select.option>
                </flux:select>
            </div>

            <flux:spacer class="max-lg:hidden" />

            <!-- Logo and Theme Switcher on the right -->
            <div class="flex items-center gap-2">
                <x-appearance-dropdown />
                <x-app-logo href="{{ route('dashboard') }}" wire:navigate />
            </div>
        </flux:header>

        <!-- Mobile Sidebar -->
        <flux:sidebar stashable sticky collapsible="mobile" class="lg:hidden bg-zinc-50 dark:bg-zinc-900 border-e border-zinc-200 dark:border-zinc-700">
            <flux:sidebar.header>
                <flux:sidebar.toggle class="lg:hidden" icon="x-mark" inset="left" />
                <div class="flex items-center gap-2">
                    <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
                    <x-appearance-dropdown />
                </div>
            </flux:sidebar.header>

            <flux:sidebar.nav class="px-2 space-y-4">
                <div class="px-2 py-4">
                    <flux:input icon="magnifying-glass" placeholder="{{ __('Buscar') }}" class="w-full" />
                </div>

                <div class="space-y-4">
                    <flux:select class="w-full bg-white/5 border-zinc-700/50 rounded-lg" icon="user-group">
                        <flux:select.option icon="user">{{ __('Cliente') }}</flux:select.option>
                        <flux:select.option icon="building-storefront">{{ __('Restaurante') }}</flux:select.option>
                    </flux:select>

                    <flux:select class="w-full bg-white/5 border-zinc-700/50 rounded-lg" icon="signal">
                        <flux:select.option icon="check-circle">{{ __('Activo') }}</flux:select.option>
                        <flux:select.option icon="x-circle">{{ __('Inactivo') }}</flux:select.option>
                    </flux:select>
                </div>
            </flux:sidebar.nav>

            <flux:spacer />

            <flux:sidebar.nav>
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:sidebar.item
                        as="button"
                        type="submit"
                        icon="arrow-right-start-on-rectangle"
                        class="w-full cursor-pointer uppercase"
                    >
                        {{ __('Cerrar sesión') }}
                    </flux:sidebar.item>
                </form>
            </flux:sidebar.nav>
        </flux:sidebar>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
