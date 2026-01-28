<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:header sticky class="bg-white/70 dark:bg-zinc-900/70 backdrop-blur-xl border-b border-zinc-200/50 dark:border-zinc-800/50 py-4">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <x-app-logo class="max-lg:hidden" href="{{ route('dashboard') }}" wire:navigate />
            <x-app-logo :sidebar="true" class="lg:hidden" href="{{ route('dashboard') }}" wire:navigate />

            <flux:navbar class="-mb-px max-lg:hidden ps-10">
                 <flux:sidebar.item icon="home" href="{{ route('dashboard') }}" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Inicio') }}</flux:sidebar.item>
                <flux:sidebar.item icon="clipboard-document-list" href="{{ route('orders.history') }}" :current="request()->routeIs('orders.history')" wire:navigate>{{ __('Mis Pedidos') }}</flux:sidebar.item>
                <flux:sidebar.item icon="heart" href="#" wire:navigate>{{ __('Favoritos') }}</flux:sidebar.item>
            </flux:navbar>
            <flux:spacer />

            <!-- Desktop Icons -->
            <div class="flex items-center gap-2 max-lg:hidden">
                <x-appearance-dropdown />
                <a href="{{ route('profile.edit') }}" wire:navigate class="p-2 rounded-md hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">
                    <flux:avatar
                        :name="auth()->user()->nombre_completo"
                        :initials="auth()->user()->initials()"
                        size="xs"
                    />
                </a>
                <flux:button variant="ghost" icon="cog" :href="route('profile.edit')" wire:navigate />
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <flux:button type="submit" variant="ghost" icon="arrow-right-start-on-rectangle" />
                </form>
            </div>
        </flux:header>

        <flux:sidebar stashable sticky collapsible="mobile" class="lg:hidden bg-zinc-50 dark:bg-zinc-900 border-e border-zinc-200 dark:border-zinc-700">
            <flux:sidebar.header>
                <flux:sidebar.toggle class="lg:hidden" icon="x-mark" inset="left" />
                <div class="flex items-center gap-2">
                    <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
                    <x-appearance-dropdown />
                </div>
            </flux:sidebar.header>

            <flux:sidebar.nav>
                <flux:sidebar.item icon="home" href="{{ route('dashboard') }}" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Inicio') }}</flux:sidebar.item>
                <flux:sidebar.item icon="clipboard-document-list" href="{{ route('orders.history') }}" :current="request()->routeIs('orders.history')" wire:navigate>{{ __('Mis Pedidos') }}</flux:sidebar.item>
                <flux:sidebar.item icon="heart" href="#" wire:navigate>{{ __('Favoritos') }}</flux:sidebar.item>
                <flux:sidebar.item icon="cog" href="{{ route('profile.edit') }}" wire:navigate>{{ __('Configuración') }}</flux:sidebar.item>
            </flux:sidebar.nav>

            <flux:spacer />

            <flux:sidebar.nav>
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:sidebar.item
                        as="button"
                        type="submit"
                        icon="arrow-right-start-on-rectangle"
                        class="w-full cursor-pointer"
                    >
                        {{ __('Cerrar sesión') }}
                    </flux:sidebar.item>
                </form>
            </flux:sidebar.nav>

            <div class="flex items-center gap-2 px-4 py-4 border-t border-zinc-200 dark:border-zinc-700">
                <flux:avatar
                    :name="auth()->user()->nombre_completo"
                    :initials="auth()->user()->initials()"
                />
                <div class="grid flex-1 text-start text-sm leading-tight">
                    <span class="truncate font-semibold text-zinc-950 dark:text-white">{{ auth()->user()->nombre_completo }}</span>
                    <span class="truncate text-xs text-zinc-700 dark:text-zinc-400">{{ auth()->user()->email }}</span>
                </div>
            </div>
        </flux:sidebar>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
