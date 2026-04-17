<div class="relative" x-data="{ showPopup: false }">
    <a href="{{ route('cart.index') }}" wire:navigate @mouseenter="showPopup = true" @mouseleave="showPopup = false"
        class="p-2 rounded-md hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors relative block">
        <flux:icon.shopping-cart class="size-5 !text-white/80 hover:!text-white" />
        @if($cartCount > 0)
            <span
                class="absolute -top-1 -right-1 bg-pastel-orange text-white text-xs font-bold rounded-full size-5 flex items-center justify-center">
                {{ $cartCount }}
            </span>
        @endif
    </a>

    <!-- Hover Popup -->
    <div x-show="showPopup" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-1" @mouseenter="showPopup = true" @mouseleave="showPopup = false"
        class="absolute right-0 top-full mt-2 w-80 bg-white dark:bg-zinc-900 rounded-lg shadow-xl border border-zinc-200 dark:border-zinc-800 z-50"
        style="display: none;">

        @if($cartCount > 0)
            <div class="p-4">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-bold text-zinc-900 dark:text-white">Mi Carrito</h3>
                    <span class="text-sm text-zinc-500">{{ $cartCount }}
                        {{ $cartCount === 1 ? 'artículo' : 'artículos' }}</span>
                </div>

                <!-- Cart Items Preview (max 3) -->
                <div class="space-y-2 mb-3 max-h-64 overflow-y-auto">
                    @php
                        $cliente = auth()->user()->cliente;
                        $previewItems = $cliente ? \App\Models\Carrito::where('id_cliente', $cliente->id_cliente)
                            ->with('menu')
                            ->take(3)
                            ->get() : collect();
                    @endphp

                    @foreach($previewItems as $item)
                        <a href="{{ route('cart.index') }}" wire:navigate
                            class="flex gap-3 p-2 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors">
                            <div class="size-12 bg-zinc-100 dark:bg-zinc-800 rounded flex-shrink-0 overflow-hidden">
                                @if($item->menu->url_foto)
                                    <img src="{{ $item->menu->url_foto }}" alt="{{ $item->menu->nombre_menu }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <flux:icon.photo class="size-6 text-zinc-300 m-auto mt-3" />
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-zinc-900 dark:text-white truncate">
                                    {{ $item->menu->nombre_menu }}</p>
                                <p class="text-xs text-zinc-500">{{ $item->cantidad }}x
                                    {{ number_format($item->menu->precio, 2) }}€</p>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="border-t border-zinc-200 dark:border-zinc-800 pt-3">
                    <div class="flex items-center justify-between mb-3">
                        <span class="font-semibold text-zinc-900 dark:text-white">Total</span>
                        <span
                            class="text-lg font-bold text-zinc-900 dark:text-white">{{ number_format($cartTotal, 2) }}€</span>
                    </div>
                    <a href="{{ route('cart.index') }}" wire:navigate
                        class="block w-full py-2 bg-pastel-orange text-white text-center rounded-lg hover:bg-orange-600 transition-colors font-semibold text-sm">
                        Ver Carrito Completo
                    </a>
                </div>
            </div>
        @else
            <div class="p-6 text-center">
                <flux:icon.shopping-cart class="size-12 text-zinc-300 mx-auto mb-2" />
                <p class="text-sm text-zinc-500">Tu carrito está vacío</p>
            </div>
        @endif
    </div>
</div>