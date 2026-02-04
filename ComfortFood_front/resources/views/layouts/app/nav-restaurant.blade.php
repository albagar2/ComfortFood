<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-app-bg dark:bg-zinc-950 font-sans antialiased relative">
    <!-- Fondos decorativos para resaltar el efecto cristal -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none -z-10">
        <div class="absolute top-0 left-0 w-full h-full stripe-bg"></div>
        <div class="absolute top-[20%] -left-[10%] w-[45%] h-[45%] bg-navy-dark/5 blur-[120px] rounded-full"></div>
        <div class="absolute -bottom-[5%] -right-[5%] w-[40%] h-[40%] bg-blue-500/5 blur-[100px] rounded-full"></div>
    </div>

    <flux:sidebar sticky collapsible="mobile"
        class="border-e border-white/10 !bg-navy-dark/80 backdrop-blur-2xl !text-white z-50">
        <flux:sidebar.header class="border-b border-white/10 py-6">
            <div class="flex mx-auto items-center gap-2">
                <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
            </div>
            <flux:sidebar.collapse class="lg:hidden text-white/70 hover:text-white" />
        </flux:sidebar.header>

        <flux:sidebar.nav class="px-2 space-y-1">
            <flux:sidebar.group :heading="__('')" class="grid gap-2">
                <!-- Inicio -->
                <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
                    wire:navigate
                    class="!text-white/80 hover:!text-white hover:!bg-white/10 data-[current]:!bg-white/10 data-[current]:!text-pastel-orange data-[current]:!border-pastel-orange data-[current]:border font-medium tracking-wide !rounded-xl">
                    <livewire:shared.badges.pending-orders-badge />
                    INICIO
                </flux:sidebar.item>
                <!-- Historial pedidos -->
                <flux:sidebar.item icon="clock" :href="route('orders.history')"
                    :current="request()->routeIs('orders.history')" wire:navigate
                    class="!text-white/80 hover:!text-white hover:!bg-white/10 data-[current]:!bg-white/10 data-[current]:!text-pastel-orange data-[current]:!border-pastel-orange data-[current]:border font-medium tracking-wide !rounded-xl">
                    HISTORIAL PEDIDOS
                </flux:sidebar.item>
                <!-- Estadísticas -->
                <flux:sidebar.item icon="presentation-chart-bar" :href="route('restaurant.statistics')"
                    :current="request()->routeIs('restaurant.statistics')" wire:navigate
                    class="!text-white/80 hover:!text-white hover:!bg-white/10 data-[current]:!bg-white/10 data-[current]:!text-pastel-orange data-[current]:!border-pastel-orange data-[current]:border font-medium tracking-wide !rounded-xl relative">
                    <livewire:shared.badges.new-reviews-badge />
                    ESTADÍSTICAS
                </flux:sidebar.item>
                <!-- Soporte -->
                <flux:sidebar.item icon="lifebuoy" :href="route('restaurant.support')"
                    :current="request()->routeIs('restaurant.support')" wire:navigate
                    class="!text-white/80 hover:!text-white hover:!bg-white/10 data-[current]:!bg-white/10 data-[current]:!text-pastel-orange data-[current]:!border-pastel-orange data-[current]:border font-medium tracking-wide !rounded-xl">
                    SOPORTE
                </flux:sidebar.item>
                <!-- Configuración -->
                <flux:sidebar.item icon="adjustments-horizontal" href="{{ route('profile.edit') }}" wire:navigate
                    class="max-lg:hidden !text-white/80 hover:!text-white hover:!bg-white/10 data-[current]:!bg-pastel-orange/20 data-[current]:!text-pastel-orange data-[current]:border-l-2 data-[current]:border-pastel-orange font-medium tracking-wide">
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
                        <flux:sidebar.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                            class="w-full cursor-pointer !text-white/70 hover:!text-white hover:!bg-white/10">
                            CERRAR SESIÓN
                        </flux:sidebar.item>
                    </form>
                </flux:sidebar.nav>

                <div class="flex items-center gap-3 px-3 py-4 border-t border-slate-700/30 mt-2">
                    <a href="{{ route('restaurant.show', auth()->user()->restaurante) }}" wire:navigate
                        class="flex items-center gap-3 flex-1 hover:opacity-80 transition-opacity">
                        <flux:avatar :src="auth()->user()->profile_photo_url" :name="auth()->user()->nombre_completo"
                            :initials="auth()->user()->initials()" size="sm" />
                        <div class="grid flex-1 text-start text-sm leading-tight">
                            <span
                                class="truncate font-semibold text-white uppercase">{{ auth()->user()->nombre_completo }}</span>
                            <span class="truncate text-xs text-white/60">{{ auth()->user()->email }}</span>
                        </div>
                    </a>
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

    <livewire:shared.modals.confirmation-modal />
    <livewire:shared.notifications.toast-container />
    <livewire:shared.notifications.notification-toast />

    @fluxScripts
</body>

</html>