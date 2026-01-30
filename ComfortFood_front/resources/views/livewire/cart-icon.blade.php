<div class="relative">
    <button wire:click="$dispatch('open-cart-modal')"
        class="p-2 rounded-md hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors relative">
        <flux:icon.shopping-cart class="size-5 !text-white/80 hover:!text-white" />
        @if($cartCount > 0)
            <span
                class="absolute -top-1 -right-1 bg-pastel-orange text-white text-xs font-bold rounded-full size-5 flex items-center justify-center">
                {{ $cartCount }}
            </span>
        @endif
    </button>
</div>