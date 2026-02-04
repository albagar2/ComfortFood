@if(auth()->user()->isRestaurante())
    <x-layouts::app.nav-restaurant :title="$title ?? null">
        <flux:main class="min-h-screen px-4 py-8">
            <div class="glass-card p-6 rounded-3xl min-h-[calc(100vh-120px)] shadow-xl shadow-navy-dark/5">
                {{ $slot }}
            </div>
        </flux:main>
    </x-layouts::app.nav-restaurant>
@elseif(auth()->user()->isCliente())
    <x-layouts::app.nav-client :title="$title ?? null">
        <flux:main class="min-h-screen px-4 py-8">
            <div class="glass-card p-6 rounded-3xl min-h-[calc(100vh-120px)] shadow-xl shadow-navy-dark/5">
                {{ $slot }}
            </div>
        </flux:main>
    </x-layouts::app.nav-client>
@else
    <x-layouts::app.nav-admin :title="$title ?? null">
        <flux:main class="min-h-screen px-4 py-8">
            <div class="glass-card p-6 rounded-3xl min-h-[calc(100vh-120px)] shadow-xl shadow-navy-dark/5">
                {{ $slot }}
            </div>
        </flux:main>
    </x-layouts::app.nav-admin>
@endif