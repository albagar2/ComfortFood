<div class="min-h-screen bg-zinc-50 dark:bg-zinc-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Flash Messages -->
        @if(session()->has('success'))
            <div
                class="mb-6 p-4 bg-green-100 dark:bg-green-900 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 rounded-lg">
                {{ session('success') }}
            </div>
        @endif
        @if(session()->has('error'))
            <div
                class="mb-6 p-4 bg-red-100 dark:bg-red-900 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white">Mi Carrito</h1>
                @if($restaurantName)
                    <p class="text-sm text-zinc-500 mt-1">Pedido de: <span
                            class="font-semibold">{{ $restaurantName }}</span></p>
                @endif
            </div>
            @if(count($cartItems) > 0)
                <button wire:click="clearCart" wire:confirm="¿Estás seguro de que quieres vaciar el carrito?"
                    class="px-4 py-2 text-sm font-medium text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                    <div class="flex items-center gap-2">
                        <flux:icon.trash class="size-4" />
                        Vaciar Carrito
                    </div>
                </button>
            @endif
        </div>

        @if(count($cartItems) === 0)
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="inline-flex items-center justify-center size-24 bg-zinc-100 dark:bg-zinc-800 rounded-full mb-6">
                    <flux:icon.shopping-cart class="size-12 text-zinc-400" />
                </div>
                <h2 class="text-2xl font-bold text-zinc-900 dark:text-white mb-2">Tu carrito está vacío</h2>
                <p class="text-zinc-500 mb-8">Añade algunos menús deliciosos para empezar</p>
                <a href="{{ route('dashboard') }}" wire:navigate
                    class="inline-flex items-center gap-2 px-6 py-3 bg-pastel-orange text-white rounded-lg hover:bg-orange-600 transition-colors font-semibold">
                    <flux:icon.arrow-left class="size-5" />
                    Explorar Menús
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2 space-y-4">
                    @foreach($cartItems as $item)
                        <div
                            class="bg-white dark:bg-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-700 p-6 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex gap-6">
                                <!-- Menu Image -->
                                <div
                                    class="size-32 bg-yellow-400 border-4 border-yellow-500/50 rounded-lg flex-shrink-0 overflow-hidden shadow-inner relative rotate-1">
                                    <div class="absolute inset-0 border-[6px] border-yellow-600/20 z-10 pointer-events-none">
                                    </div>
                                    @if($item['menu']['url_foto'])
                                        <img src="{{ $item['menu']['url_foto'] }}" alt="{{ $item['menu']['nombre_menu'] }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <div class="text-white text-opacity-80 flex items-center justify-center h-full">
                                            <flux:icon.photo class="size-16" />
                                        </div>
                                    @endif
                                </div>

                                <!-- Menu Details -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex-1">
                                            <h3 class="text-xl font-bold text-zinc-900 dark:text-white mb-2">
                                                {{ $item['menu']['nombre_menu'] }}</h3>
                                            <p class="text-sm text-zinc-500 line-clamp-2">
                                                {{ $item['menu']['descripcion_menu'] }}</p>
                                        </div>
                                        <button wire:click="removeItem({{ $item['id_carrito'] }})"
                                            class="ml-4 text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                                            <flux:icon.trash class="size-5" />
                                        </button>
                                    </div>

                                    <!-- Menu Components -->
                                    <div class="grid grid-cols-2 gap-2 mb-4">
                                        @if($item['menu']['plato_principal'])
                                            <div class="flex items-center gap-2 text-xs text-zinc-600 dark:text-zinc-400">
                                                <span
                                                    class="size-5 rounded-full bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center text-orange-600 dark:text-orange-400 font-bold text-[10px]">1</span>
                                                <span class="truncate">{{ $item['menu']['plato_principal'] }}</span>
                                            </div>
                                        @endif
                                        @if($item['menu']['segundo_plato'])
                                            <div class="flex items-center gap-2 text-xs text-zinc-600 dark:text-zinc-400">
                                                <span
                                                    class="size-5 rounded-full bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center text-orange-600 dark:text-orange-400 font-bold text-[10px]">2</span>
                                                <span class="truncate">{{ $item['menu']['segundo_plato'] }}</span>
                                            </div>
                                        @endif
                                        @if($item['menu']['postre'])
                                            <div class="flex items-center gap-2 text-xs text-zinc-600 dark:text-zinc-400">
                                                <span
                                                    class="size-5 rounded-full bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center text-orange-600 dark:text-orange-400 font-bold text-[10px]">3</span>
                                                <span class="truncate">{{ $item['menu']['postre'] }}</span>
                                            </div>
                                        @endif
                                        @if($item['menu']['bebida'])
                                            <div class="flex items-center gap-2 text-xs text-zinc-600 dark:text-zinc-400">
                                                <span
                                                    class="size-5 rounded-full bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center text-orange-600 dark:text-orange-400 font-bold text-[10px]">4</span>
                                                <span class="truncate">{{ $item['menu']['bebida'] }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Quantity and Price -->
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <span class="text-sm text-zinc-500">Cantidad:</span>
                                            <div class="flex items-center gap-2">
                                                <button wire:click="decreaseQuantity({{ $item['id_carrito'] }})"
                                                    class="size-8 flex items-center justify-center rounded-lg border-2 border-zinc-300 dark:border-zinc-600 hover:bg-zinc-100 dark:hover:bg-zinc-700 transition-colors">
                                                    <flux:icon.minus class="size-4" />
                                                </button>
                                                <span
                                                    class="text-lg font-bold text-zinc-900 dark:text-white w-12 text-center">{{ $item['cantidad'] }}</span>
                                                <button wire:click="increaseQuantity({{ $item['id_carrito'] }})"
                                                    class="size-8 flex items-center justify-center rounded-lg border-2 border-zinc-300 dark:border-zinc-600 hover:bg-zinc-100 dark:hover:bg-zinc-700 transition-colors">
                                                    <flux:icon.plus class="size-4" />
                                                </button>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm text-zinc-500">{{ number_format($item['menu']['precio'], 2) }}€ /
                                                unidad</p>
                                            <p class="text-2xl font-bold text-zinc-900 dark:text-white">
                                                {{ number_format($item['cantidad'] * $item['menu']['precio'], 2) }}€</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div
                        class="bg-white dark:bg-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-700 p-6 shadow-sm sticky top-6">
                        <h2 class="text-xl font-bold text-zinc-900 dark:text-white mb-6">Resumen del Pedido</h2>

                        <div class="space-y-3 mb-6">
                            @foreach($cartItems as $item)
                                <div class="flex justify-between text-sm">
                                    <span class="text-zinc-600 dark:text-zinc-400">{{ $item['cantidad'] }}x
                                        {{ $item['menu']['nombre_menu'] }}</span>
                                    <span
                                        class="font-medium text-zinc-900 dark:text-white">{{ number_format($item['cantidad'] * $item['menu']['precio'], 2) }}€</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="border-t border-zinc-200 dark:border-zinc-700 pt-4 mb-6">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-bold text-zinc-900 dark:text-white">Total</span>
                                <span class="text-3xl font-bold text-pastel-orange">{{ number_format($total, 2) }}€</span>
                            </div>
                        </div>

                        <button wire:click="checkout"
                            class="w-full py-4 bg-pastel-orange text-white rounded-xl hover:bg-orange-600 transition-colors font-bold text-lg shadow-lg shadow-orange-200 dark:shadow-orange-900/30 active:scale-95 transform transition-transform">
                            Confirmar Pedido
                        </button>

                        <a href="{{ route('dashboard') }}" wire:navigate
                            class="block w-full mt-3 py-3 text-center border-2 border-zinc-300 dark:border-zinc-600 text-zinc-700 dark:text-zinc-300 rounded-xl hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors font-semibold">
                            Seguir Comprando
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>