<flux:dropdown {{ $attributes->merge(['align' => 'end']) }}>
    <flux:button variant="ghost" square aria-label="{{ __('Cambiar apariencia') }}">
        <flux:icon.sun class="dark:hidden" />
        <flux:icon.moon class="hidden dark:block" />
    </flux:button>

    <flux:menu class="min-w-32">
        <flux:menu.item icon="sun" x-on:click="Flux.appearance = 'light'">{{ __('Claro') }}</flux:menu.item>
        <flux:menu.item icon="moon" x-on:click="Flux.appearance = 'dark'">{{ __('Oscuro') }}</flux:menu.item>
        <flux:menu.item icon="computer-desktop" x-on:click="Flux.appearance = 'system'">{{ __('Sistema') }}</flux:menu.item>
    </flux:menu>
</flux:dropdown>
