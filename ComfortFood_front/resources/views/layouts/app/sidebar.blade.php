<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header>
                <div class="flex items-center gap-2">
                    <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
                    <x-appearance-dropdown />
                </div>
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                <flux:sidebar.group :heading="__('')" class="grid gap-5">
                    <!-- Inicio -->
                    <flux:sidebar.item
                        icon="home"
                        :href="route('dashboard')"
                        :current="request()->routeIs('dashboard')"
                        wire:navigate>
                        INICIO
                    </flux:sidebar.item>
                    <!-- Historial pedidos -->
                    <flux:sidebar.item
                        icon="clock"
                        :href="route('dashboard')"
                        :current="request()->routeIs('dashboard')"
                        wire:navigate>
                        HISTORIAL PEDIDOS
                    </flux:sidebar.item>
                    <!-- Estadísticas -->
                    <flux:sidebar.item
                        icon="chart-bar"
                        :href="route('dashboard')"
                        :current="request()->routeIs('dashboard')"
                        wire:navigate>
                        ESTADÍSTICAS
                    </flux:sidebar.item>
                    <!-- Soporte -->
                    <flux:sidebar.item
                        icon="lifebuoy"
                        :href="route('dashboard')"
                        :current="request()->routeIs('dashboard')"
                        wire:navigate>
                        SOPORTE
                    </flux:sidebar.item>
                    <!-- Configuración -->
                    <flux:sidebar.item 
                        icon="adjustments-horizontal" 
                        href="{{ route('profile.edit') }}" 
                        wire:navigate
                        class="max-lg:hidden">
                        CONFIGURACIÓN
                    </flux:sidebar.item>
                </flux:sidebar.group>
            </flux:sidebar.nav>

            <flux:spacer />

            <!-- Sidebar Footer -->
            <div class="mt-auto px-2 pb-4">
                <!-- Desktop View: Direct logout and Profile Info -->
                <div class="max-lg:hidden space-y-1">
                    <flux:sidebar.nav>
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <flux:sidebar.item
                                as="button"
                                type="submit"
                                icon="arrow-right-start-on-rectangle"
                                class="w-full cursor-pointer"
                            >
                                CERRAR SESIÓN
                            </flux:sidebar.item>
                        </form>
                    </flux:sidebar.nav>

                    <div class="flex items-center gap-3 px-3 py-4 border-t border-zinc-200 dark:border-zinc-700 mt-2">
                        <flux:avatar
                            :name="auth()->user()->nombre_completo"
                            :initials="auth()->user()->initials()"
                            size="sm"
                        />
                        <div class="grid flex-1 text-start text-sm leading-tight">
                            <span class="truncate font-semibold text-zinc-800 dark:text-white uppercase">{{ auth()->user()->nombre_completo }}</span>
                            <span class="truncate text-xs text-zinc-500 dark:text-zinc-400">{{ auth()->user()->email }}</span>
                        </div>
                        <x-appearance-dropdown />
                    </div>
                </div>

                <!-- Mobile View: Everything in the unified dropdown -->
                <div class="lg:hidden">
                    <x-desktop-user-menu :name="auth()->user()->nombre_completo" />
                </div>
            </div>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
            <flux:spacer />
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
