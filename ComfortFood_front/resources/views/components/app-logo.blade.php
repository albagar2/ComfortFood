@props([
'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand {{ $attributes }}  class="w-full flex justify-center px-0  py-10">
        <x-slot name="logo" class="flex aspect-square size-20 items-center justify-center rounded-md">
            <img src="{{ asset('images/logo.png') }}" class="size-20 object-contain" alt="Logo" />
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand {{ $attributes }} class="w-full flex justify-center px-0">
        <x-slot name="logo" class="flex aspect-square size-20 items-center justify-center rounded-md">
            <img src="{{ asset('images/logo.png') }}" class="size-20 object-contain" alt="Logo" />
        </x-slot>
    </flux:brand>
@endif
