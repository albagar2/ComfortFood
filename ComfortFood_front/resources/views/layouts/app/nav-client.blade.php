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
            class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] bg-pastel-orange/20 blur-[120px] rounded-full animate-pulse">
        </div>
        <div class="absolute top-[20%] -right-[5%] w-[35%] h-[35%] bg-blue-400/10 blur-[100px] rounded-full"></div>
    </div>

    <flux:header sticky
        class="!bg-navy-dark/80 backdrop-blur-xl backdrop-saturate-150 border-b border-white/10 py-4 !text-white z-50">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <x-app-logo class="max-lg:hidden" href="{{ route('dashboard') }}" wire:navigate />
        <x-app-logo :sidebar="true" class="lg:hidden" href="{{ route('dashboard') }}" wire:navigate />

        <flux:navbar class="-mb-px max-lg:hidden ps-10">
            <flux:sidebar.item icon="home" href="{{ route('dashboard') }}" :current="request()->routeIs('dashboard')"
                wire:navigate
                class="!text-white/80 hover:!text-white data-[current]:!text-pastel-orange data-[current]:!border-pastel-orange/50 data-[current]:border !rounded-xl !bg-transparent data-[current]:!bg-white/10">
                {{ __('Inicio') }}
            </flux:sidebar.item>
            <flux:sidebar.item icon="clipboard-document-list" href="{{ route('orders.history') }}"
                :current="request()->routeIs('orders.history')" wire:navigate
                class="!text-white/80 hover:!text-white data-[current]:!text-pastel-orange data-[current]:!border-pastel-orange/50 data-[current]:border !rounded-xl !bg-transparent data-[current]:!bg-white/10 relative">
                <livewire:shared.badges.new-completed-orders-badge />
                {{ __('Mis Pedidos') }}
            </flux:sidebar.item>
            <flux:sidebar.item icon="heart" href="{{ route('favorites') }}" :current="request()->routeIs('favorites')"
                wire:navigate
                class="!text-white/80 hover:!text-white data-[current]:!text-pastel-orange data-[current]:!border-pastel-orange/50 data-[current]:border !rounded-xl !bg-transparent data-[current]:!bg-white/10">
                {{ __('Favoritos') }}
            </flux:sidebar.item>
        </flux:navbar>
        <flux:spacer />

        <div class="flex items-center gap-3 mr-4">
            <livewire:shared.cart-icon />
            <x-appearance-dropdown />
        </div>

        <!-- Desktop Icons -->
        <div class="flex items-center gap-2 max-lg:hidden">
            <div class="flex items-center gap-3 scale-110">
                <a href="{{ route('profile.edit') }}" wire:navigate
                    class="p-2 rounded-md !text-white/80 hover:!text-white" title="mi perfil">
                    <flux:avatar :src="auth()->user()->profile_photo_url" :name="auth()->user()->nombre_completo"
                        :initials="auth()->user()->initials()" size="m" />
                </a>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <flux:button type="submit" variant="ghost" icon="arrow-right-start-on-rectangle"
                    class="!text-white/80 hover:!text-white ms-10" title="cerrar sesion" />
            </form>
        </div>
    </flux:header>

    <flux:sidebar stashable sticky collapsible="mobile"
        class="lg:hidden border-e border-white/10 !bg-navy-dark/90 backdrop-blur-2xl !text-white z-50">
        <flux:sidebar.header class="border-b border-slate-700/30 py-6">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" inset="left" />
            <div class="flex items-center gap-2">
                <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
                <x-appearance-dropdown />
                <livewire:shared.cart-icon />
            </div>
        </flux:sidebar.header>

        <flux:sidebar.nav>
            <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
                wire:navigate class="
        !text-white/80
        hover:!text-white hover:!bg-white/10
        data-[current]:!bg-white/10
        data-[current]:!text-pastel-orange
        data-[current]:border
        data-[current]:!border-pastel-orange
        !rounded-xl
        font-medium tracking-wide
    ">
                INICIO
            </flux:sidebar.item>
            <flux:sidebar.item icon="clipboard-document-list" href="{{ route('orders.history') }}"
                :current="request()->routeIs('orders.history')" wire:navigate class="
        !text-white/80
        hover:!text-white hover:!bg-white/10
        data-[current]:!bg-white/10
        data-[current]:!text-pastel-orange
        data-[current]:border
        data-[current]:!border-pastel-orange
        !rounded-xl
        font-medium tracking-wide relative
    ">
                <livewire:shared.badges.new-completed-orders-badge />
                {{ __('Mis Pedidos') }}
            </flux:sidebar.item>

            <flux:sidebar.item icon="heart" href="{{ route('favorites') }}" :current="request()->routeIs('favorites')"
                wire:navigate class="
        !text-white/80
        hover:!text-white hover:!bg-white/10
        data-[current]:!bg-white/10
        data-[current]:!text-pastel-orange
        data-[current]:border
        data-[current]:!border-pastel-orange
        !rounded-xl
        font-medium tracking-wide
    ">
                {{ __('Favoritos') }}
            </flux:sidebar.item>

            <flux:sidebar.item icon="cog" href="{{ route('profile.edit') }}" wire:navigate class="
        !text-white/80
        hover:!text-white hover:!bg-white/10
        data-[current]:!bg-white/10
        data-[current]:!text-pastel-orange
        data-[current]:border
        data-[current]:!border-pastel-orange
        !rounded-xl
        font-medium tracking-wide
    ">
                {{ __('Configuración') }}
            </flux:sidebar.item>
        </flux:sidebar.nav>

        <flux:spacer />

        <flux:sidebar.nav>
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <flux:sidebar.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full cursor-pointer
                        !text-white/80
                          hover:!text-white
                          hover:!bg-white/10">
                    {{ __('Cerrar sesión') }}
                </flux:sidebar.item>
            </form>
        </flux:sidebar.nav>

        <div class="flex items-center gap-2 px-4 py-4 border-t border-zinc-200 dark:border-zinc-700">
            <flux:avatar :src="auth()->user()->profile_photo_url" :name="auth()->user()->nombre_completo"
                :initials="auth()->user()->initials()" />
            <div class="grid flex-1 text-start text-sm leading-tight">
                <span class="truncate font-semibold text-white uppercase">
                    {{ auth()->user()->nombre_completo }}
                </span>
                <span class="truncate text-xs text-white/60">
                    {{ auth()->user()->email }}
                </span>
            </div>
        </div>
    </flux:sidebar>

    {{ $slot }}

    <livewire:shared.modals.confirmation-modal />
    <livewire:shared.modals.cart-modal />
    <livewire:shared.notifications.toast-container />
    <livewire:shared.notifications.notification-toast />

    @fluxScripts
</body>

</html>