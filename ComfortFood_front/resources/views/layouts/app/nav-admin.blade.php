<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:header sticky class="!bg-navy-dark backdrop-blur-xl border-b border-slate-700/30 py-4 !text-white">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <!-- Logout on the left (Desktop) -->
        <form method="POST" action="{{ route('logout') }}" class="max-lg:hidden">
            @csrf
            <flux:button type="submit" variant="ghost" icon="arrow-right-start-on-rectangle"
                class="text-xs uppercase tracking-wider">
                {{ __('CERRAR SESIÓN') }}
            </flux:button>
        </form>

        <flux:spacer />

        <!-- Search and Selectors (Desktop) -->
        <div class="flex items-center gap-4 max-lg:hidden">
            <flux:input icon="magnifying-glass" placeholder="{{ __('Buscar por DNI, nombre, email o dirección...') }}"
                class="w-80"
                x-on:input.debounce.500ms="Livewire.dispatch('searchUpdated', { query: $event.target.value })"
                :disabled="request()->routeIs('admin.users.show') || request()->routeIs('admin.users.edit')" />

            <flux:select class="w-44 bg-white/5 border-zinc-700/50 rounded-lg"
                x-on:change="Livewire.dispatch('typeUpdated', { type: $event.target.value })"
                :disabled="request()->routeIs('admin.users.show') || request()->routeIs('admin.users.edit')">
                <flux:select.option value="" icon="user-group">{{ __('Todos los tipos') }}</flux:select.option>
                <flux:select.option value="cliente" icon="user">{{ __('Cliente') }}</flux:select.option>
                <flux:select.option value="restaurante" icon="building-storefront">{{ __('Restaurante') }}
                </flux:select.option>
            </flux:select>

            <flux:select class="w-44 bg-white/5 border-zinc-700/50 rounded-lg"
                x-on:change="Livewire.dispatch('statusUpdated', { status: $event.target.value })"
                :disabled="request()->routeIs('admin.users.show') || request()->routeIs('admin.users.edit')">
                <flux:select.option value="" icon="signal">{{ __('Todos los estados') }}</flux:select.option>
                <flux:select.option value="activo" icon="check-circle">{{ __('Activo') }}</flux:select.option>
                <flux:select.option value="inactivo" icon="x-circle">{{ __('Inactivo') }}</flux:select.option>
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
    <flux:sidebar stashable sticky collapsible="mobile"
        class="lg:hidden !bg-navy-dark border-e border-slate-700/30 !text-white">
        <flux:sidebar.header>
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" inset="left" />
            <div class="flex items-center gap-2">
                <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
                <x-appearance-dropdown />
            </div>
        </flux:sidebar.header>

        <flux:sidebar.nav class="px-2 space-y-4">
            <div class="px-2 py-4">
                <flux:input icon="magnifying-glass"
                    placeholder="{{ __('Buscar por DNI, nombre, email o dirección...') }}" class="w-full"
                    x-on:input.debounce.500ms="Livewire.dispatch('searchUpdated', { query: $event.target.value })"
                    :disabled="request()->routeIs('admin.users.show') || request()->routeIs('admin.users.edit')" />
            </div>

            <div class="space-y-4">
                <flux:select class="w-full bg-white/5 border-zinc-700/50 rounded-lg"
                    x-on:change="Livewire.dispatch('typeUpdated', { type: $event.target.value })"
                    :disabled="request()->routeIs('admin.users.show') || request()->routeIs('admin.users.edit')">
                    <flux:select.option value="" icon="user-group">{{ __('Todos los tipos') }}</flux:select.option>
                    <flux:select.option value="cliente" icon="user">{{ __('Cliente') }}</flux:select.option>
                    <flux:select.option value="restaurante" icon="building-storefront">{{ __('Restaurante') }}
                    </flux:select.option>
                </flux:select>

                <flux:select class="w-full bg-white/5 border-zinc-700/50 rounded-lg"
                    x-on:change="Livewire.dispatch('statusUpdated', { status: $event.target.value })"
                    :disabled="request()->routeIs('admin.users.show') || request()->routeIs('admin.users.edit')">
                    <flux:select.option value="" icon="signal">{{ __('Todos los estados') }}</flux:select.option>
                    <flux:select.option value="activo" icon="check-circle">{{ __('Activo') }}</flux:select.option>
                    <flux:select.option value="inactivo" icon="x-circle">{{ __('Inactivo') }}</flux:select.option>
                </flux:select>
            </div>
        </flux:sidebar.nav>

        <flux:spacer />

        <flux:sidebar.nav>
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <flux:sidebar.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                    class="w-full cursor-pointer uppercase">
                    {{ __('Cerrar sesión') }}
                </flux:sidebar.item>
            </form>
        </flux:sidebar.nav>
    </flux:sidebar>

    {{ $slot }}

    @fluxScripts
</body>

</html>