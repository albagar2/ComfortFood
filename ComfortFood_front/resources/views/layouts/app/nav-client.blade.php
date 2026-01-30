<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:header sticky class="!bg-navy-dark backdrop-blur-xl border-b border-slate-700/30 py-4 !text-white">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <x-app-logo class="max-lg:hidden" href="{{ route('dashboard') }}" wire:navigate />
        <x-app-logo :sidebar="true" class="lg:hidden" href="{{ route('dashboard') }}" wire:navigate />

        <flux:navbar class="-mb-px max-lg:hidden ps-10">
            <flux:sidebar.item icon="home" href="{{ route('dashboard') }}" :current="request()->routeIs('dashboard')"
                wire:navigate
                class="!text-white/80 hover:!text-white data-[current]:!text-pastel-orange data-[current]:!border-pastel-orange data-[current]:border !rounded-xl !bg-transparent data-[current]:!bg-white/10">
                {{ __('Inicio') }}
            </flux:sidebar.item>
            <flux:sidebar.item icon="clipboard-document-list" href="{{ route('orders.history') }}"
                :current="request()->routeIs('orders.history')" wire:navigate
                class="!text-white/80 hover:!text-white data-[current]:!text-pastel-orange data-[current]:!border-pastel-orange data-[current]:border !rounded-xl !bg-transparent data-[current]:!bg-white/10">
                {{ __('Mis Pedidos') }}
            </flux:sidebar.item>
            <flux:sidebar.item icon="heart" href="{{ route('favorites') }}" :current="request()->routeIs('favorites')"
                wire:navigate
                class="!text-white/80 hover:!text-white data-[current]:!text-pastel-orange data-[current]:!border-pastel-orange data-[current]:border !rounded-xl !bg-transparent data-[current]:!bg-white/10">
                {{ __('Favoritos') }}
            </flux:sidebar.item>
        </flux:navbar>
        <flux:spacer />

        <!-- Desktop Icons -->
        <div class="flex items-center gap-2 max-lg:hidden">
            <x-appearance-dropdown />
            <livewire:cart-icon />
            <a href="{{ route('profile.edit') }}" wire:navigate
                class="p-2 rounded-md hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">
                <flux:avatar :src="auth()->user()->profile_photo_url" :name="auth()->user()->nombre_completo"
                    :initials="auth()->user()->initials()" size="xs" />
            </a>
            <flux:button variant="ghost" icon="cog" :href="route('profile.edit')" wire:navigate
                class="!text-white/80 hover:!text-white" />

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <flux:button type="submit" variant="ghost" icon="arrow-right-start-on-rectangle"
                    class="!text-white/80 hover:!text-white" />
            </form>
        </div>
    </flux:header>

    <flux:sidebar stashable sticky collapsible="mobile"
        class="lg:hidden border-e border-slate-700/30 !bg-navy-dark !text-white">
        <flux:sidebar.header class="border-b border-slate-700/30 py-6">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" inset="left" />
            <div class="flex items-center gap-2">
                <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
                <livewire:cart-icon />
                <x-appearance-dropdown />
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
        font-medium tracking-wide
    ">
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

    <livewire:cart-modal />

    @fluxScripts
</body>

</html>