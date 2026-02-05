@props(['title' => null])

@php
    // Detect if we are in a component slot or a traditional layout
    $pageContent = $slot ?? $yieldContent ?? '';
@endphp

@if(auth()->user()->isRestaurante())
    <x-layouts::app.nav-restaurant :title="$title">
        <flux:main class="min-h-screen px-4 py-8 relative z-10">
            <div class="glass-card p-6 rounded-3xl min-h-[calc(100vh-120px)] shadow-xl shadow-navy-dark/5">
                {{ $pageContent }}
            </div>
        </flux:main>
    </x-layouts::app.nav-restaurant>
@elseif(auth()->user()->isCliente())
    <x-layouts::app.nav-client :title="$title">
        <flux:main class="min-h-screen px-4 py-8 relative z-10">
            <div class="glass-card p-6 rounded-3xl min-h-[calc(100vh-120px)] shadow-xl shadow-navy-dark/5">
                {{ $pageContent }}
            </div>
        </flux:main>
    </x-layouts::app.nav-client>
@else
    <x-layouts::app.nav-admin :title="$title">
        <flux:main class="min-h-screen px-4 py-8 relative z-10">
            <div class="glass-card p-6 rounded-3xl min-h-[calc(100vh-120px)] shadow-xl shadow-navy-dark/5">
                {{ $pageContent }}
            </div>
        </flux:main>
    </x-layouts::app.nav-admin>
@endif