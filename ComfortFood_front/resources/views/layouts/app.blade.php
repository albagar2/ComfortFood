@if(auth()->user()->isRestaurante())
    <x-layouts::app.nav-restaurant :title="$title ?? null">
        <flux:main class="min-h-screen bg-violet-50 dark:bg-zinc-800">
            {{ $slot }}
        </flux:main>
    </x-layouts::app.nav-restaurant>
@elseif(auth()->user()->isAdmin())
    <x-layouts::app.nav-admin :title="$title ?? null">
        <flux:main class="min-h-screen bg-violet-50 dark:bg-zinc-800">
            {{ $slot }}
        </flux:main>
    </x-layouts::app.nav-admin>
@else
    <x-layouts::app.nav-client :title="$title ?? null">
        <flux:main class="min-h-screen bg-violet-50 dark:bg-zinc-800">
            {{ $slot }}
        </flux:main>
    </x-layouts::app.nav-client>
@endif