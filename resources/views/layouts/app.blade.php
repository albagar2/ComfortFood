@props(['title' => null])

@php
    // Detect if we are in a component slot or a traditional layout
    $pageContent = $slot ?? $yieldContent ?? '';
@endphp

@php
    $user = auth()->user();
@endphp

@if($user && $user->isRestaurante())
    <x-layouts::app.nav-restaurant :title="$title">
        <flux:main class="min-h-screen md:px-4 py-2 md:py-8 relative z-10">
            <div class="glass-card p-2 md:p-6 rounded-3xl min-h-[calc(100vh-120px)] shadow-xl shadow-navy-dark/5">
                {{ $pageContent }}
                @include('partials.footer')
            </div>
        </flux:main>
    </x-layouts::app.nav-restaurant>
    <x-layouts::app.nav-client :title="$title">
        <flux:main class="min-h-screen md:px-4 py-2 md:py-8 relative z-10">
            <div class="glass-card p-2 md:p-6 rounded-3xl min-h-[calc(100vh-120px)] shadow-xl shadow-navy-dark/5">
                {{ $pageContent }}
                @include('partials.footer')
            </div>
        </flux:main>
    </x-layouts::app.nav-client>
@elseif($user)
    <x-layouts::app.nav-admin :title="$title">
        <flux:main class="min-h-screen md:px-4 py-2 md:py-8 relative z-10">
            <div class="glass-card p-2 md:p-6 rounded-3xl min-h-[calc(100vh-120px)] shadow-xl shadow-navy-dark/5">
                {{ $pageContent }}
                @include('partials.footer')
            </div>
        </flux:main>
    </x-layouts::app.nav-admin>
@else
    <x-layouts::auth.simple>
        {{ $pageContent }}
    </x-layouts::auth.simple>
@endif