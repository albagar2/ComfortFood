<div>
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-zinc-900 bg-opacity-75 transition-opacity" wire:click="showModal = false">
                </div>

                <!-- Modal panel -->
                <div
                    class="inline-block align-bottom bg-white dark:bg-zinc-900 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <!-- Header -->
                    <div
                        class="bg-white dark:bg-zinc-900 px-4 pt-5 pb-4 sm:p-6 border-b border-zinc-200 dark:border-zinc-800">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-zinc-900 dark:text-white">Mi Carrito</h3>
                                @if($restaurantName)
                                    <p class="text-sm text-zinc-500 mt-1">{{ $restaurantName }}</p>
                                @endif
                            </div>
                            <button wire:click="showModal = false"
                                class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200">
                                <flux:icon.x-mark class="size-6" />
                            </button>
                        </div>
                    </div>

                    <!-- Cart Items -->
                    <div class="bg-white dark:bg-zinc-900 px-4 py-5 sm:p-6 max-h-96 overflow-y-auto">
                        @if(count($cartItems) === 0)
                            <div class="text-center py-8">
                                <flux:icon.shopping-cart class="size-16 text-zinc-300 mx-auto mb-4" />
                                <p class="text-zinc-500">Tu carrito está vacío</p>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($cartItems as $item)
                                    <div class="flex gap-4 p-3 rounded-lg border border-zinc-200 dark:border-zinc-800">
                                        <!-- Image -->
                                        <div class="size-20 bg-zinc-100 dark:bg-zinc-800 rounded-lg flex-shrink-0 overflow-hidden">
                                            @if($item['menu']['url_foto'])
                                                <img src="{{ $item['menu']['url_foto'] }}" alt="{{ $item['menu']['nombre_menu'] }}"
                                                    class="w-full h-full object-cover">
                                            @else
                                                <flux:icon.photo class="size-8 text-zinc-300 m-auto mt-6" />
                                            @endif
                                        </div>

                                        <!-- Details -->
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-semibold text-zinc-900 dark:text-white truncate">
                                                {{ $item['menu']['nombre_menu'] }}</h4>
                                            <p class="text-sm text-zinc-500 mt-1">{{ number_format($item['menu']['precio'], 2) }}€
                                            </p>

                                            <!-- Quantity controls -->
                                            <div class="flex items-center gap-2 mt-2">
                                                <button wire:click="decreaseQuantity({{ $item['id_carrito'] }})"
                                                    class="size-7 flex items-center justify-center rounded border border-zinc-300 dark:border-zinc-700 hover:bg-zinc-100 dark:hover:bg-zinc-800">
                                                    <flux:icon.minus class="size-3" />
                                                </button>
                                                <span class="text-sm font-medium w-8 text-center">{{ $item['cantidad'] }}</span>
                                                <button wire:click="increaseQuantity({{ $item['id_carrito'] }})"
                                                    class="size-7 flex items-center justify-center rounded border border-zinc-300 dark:border-zinc-700 hover:bg-zinc-100 dark:hover:bg-zinc-800">
                                                    <flux:icon.plus class="size-3" />
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Price & Remove -->
                                        <div class="flex flex-col items-end justify-between">
                                            <button wire:click="removeItem({{ $item['id_carrito'] }})"
                                                class="text-red-500 hover:text-red-700">
                                                <flux:icon.trash class="size-5" />
                                            </button>
                                            <p class="font-bold text-zinc-900 dark:text-white">
                                                {{ number_format($item['cantidad'] * $item['menu']['precio'], 2) }}€
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Footer -->
                    @if(count($cartItems) > 0)
                        <div
                            class="bg-zinc-50 dark:bg-zinc-800 px-4 py-4 sm:px-6 border-t border-zinc-200 dark:border-zinc-700">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-lg font-bold text-zinc-900 dark:text-white">Total</span>
                                <span
                                    class="text-2xl font-bold text-zinc-900 dark:text-white">{{ number_format($total, 2) }}€</span>
                            </div>
                            <div class="flex gap-3">
                                <button wire:click="clearCart"
                                    class="flex-1 px-4 py-2 border border-zinc-300 dark:border-zinc-700 rounded-lg text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-700 transition-colors">
                                    Vaciar Carrito
                                </button>
                                <button wire:click="checkout"
                                    class="flex-1 px-4 py-2 bg-pastel-orange text-white rounded-lg hover:bg-orange-600 transition-colors font-semibold">
                                    Confirmar Pedido
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>