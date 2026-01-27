@props([
'sidebar' => false,
])

@if($sidebar)
<<<<<<< Updated upstream
    <flux:sidebar.brand name="Comfort Food" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-accent-content text-accent-foreground">
            <x-app-logo-icon class="size-5 fill-current text-white dark:text-black" />
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand name="Comfort Food" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-accent-content text-accent-foreground">
            <x-app-logo-icon class="size-5 fill-current text-white dark:text-black" />
=======
    <flux:sidebar.brand {{ $attributes }}  class="w-full flex justify-center px-0  py-10">
        <x-slot name="logo" class="flex aspect-square size-12 items-center justify-center rounded-md bg-accent-content text-accent-foreground">
            <x-app-logo-icon class="size-12 fill-current text-white dark:text-black" />
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand {{ $attributes }} class="w-full flex justify-center px-0">
        <x-slot name="logo" class="flex aspect-square size-12 items-center justify-center rounded-md bg-accent-content text-accent-foreground ">
            <x-app-logo-icon class="size-12 fill-current text-white dark:text-black" />
>>>>>>> Stashed changes
        </x-slot>
    </flux:brand>
@endif
