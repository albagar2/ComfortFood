@if(auth()->user()->isRestaurante())
    <x-layouts::app.sidebar :title="$title ?? null">
        <flux:main>
            {{ $slot }}
        </flux:main>
    </x-layouts::app.sidebar>
@elseif(auth()->user()->isAdmin())
    <x-layouts::app.nav-admin :title="$title ?? null">
        <flux:main>
            {{ $slot }}
        </flux:main>
    </x-layouts::app.nav-admin>
@else
    <x-layouts::app.nav-client :title="$title ?? null">
        <flux:main>
            {{ $slot }}
        </flux:main>
    </x-layouts::app.nav-client>
@endif
