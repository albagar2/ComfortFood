<div class="flex flex-col gap-8 p-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="w-full md:w-96">
            <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass" placeholder="Buscar"
                class="w-full" />
        </div>
        <flux:button variant="primary" icon="plus" href="{{ route('menu.edit') }}" wire:navigate>Añadir Menú
        </flux:button>
        <a href="{{ route('menu.index') }}" wire:navigate
            class="px-6 py-2.5 text-sm font-medium text-zinc-600 bg-white border border-zinc-200 rounded-lg hover:bg-zinc-50 transition-colors uppercase">
            EDITAR MENÚS
        </a>
        <div class="flex gap-4">
            <!-- Used standard buttons to match the exact look in the image (White with border) -->
            <a href="{{ route('restaurant.show', auth()->user()->restaurante->id_restaurante) }}" wire:navigate
                class="px-6 py-2.5 text-sm font-medium text-zinc-600 bg-white border border-zinc-200 rounded-lg hover:bg-zinc-50 transition-colors">
                Vista restaurante
            </a>

        </div>
    </div>

    <!-- Top List (Pending/Active Orders) -->
    <div>
        <h2 class="text-lg font-bold text-zinc-800 dark:text-zinc-100 mb-4">Lista pedidos</h2>
        <div class="flex gap-3 overflow-x-auto pb-4 scrollbar-hide">
            @foreach($orders as $order)
                <div
                    class="flex items-center gap-3 px-4 py-2 min-w-[100px] bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-lg shadow-sm whitespace-nowrap opacity-{{ $order->estado->nombre_estado == 'Cancelado' ? '50' : '100' }}">
                    <span class="text-zinc-400">
                        @if($order->estado->nombre_estado == 'Completado')
                            <flux:icon.check class="size-4 text-zinc-400" />
                        @elseif($order->estado->nombre_estado == 'Cancelado')
                            <flux:icon.x-mark class="size-4 text-zinc-400" />
                        @else
                            <!-- Diagonal line icon or just slash -->
                            <svg class="size-4 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 5L5 19" />
                            </svg>
                        @endif
                    </span>
                    <span class="text-sm font-medium text-zinc-500 dark:text-zinc-400">#{{ $order->id_pedido }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-6">
        @foreach($orders as $order)
            <a href="{{ route('orders.details', $order->id_pedido) }}" wire:navigate
                class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl p-6 flex flex-col gap-6 shadow-sm group hover:border-zinc-300 dark:hover:border-zinc-700 transition-colors cursor-pointer">
                <!-- Card Header -->
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-bold text-base text-zinc-900 dark:text-white">Pedido #{{ $order->id_pedido }}</h3>
                        <p class="text-xs text-zinc-400 mt-1">{{ $order->created_at->format('d M Y, h:i A') }}</p>
                    </div>
                    <!-- Placeholder Icon for Order/User -->
                    <div
                        class="size-10 bg-zinc-100 dark:bg-zinc-800 rounded-lg flex items-center justify-center text-zinc-400">
                        <flux:icon.photo class="size-6" />
                    </div>
                </div>

                <!-- Items -->
                <div class="space-y-6 flex-1">
                    @foreach($order->detalles as $detalle)
                        <div class="flex gap-4">
                            <!-- Item Image -->
                            <div
                                class="size-16 bg-zinc-100 dark:bg-zinc-800 rounded-full flex-shrink-0 overflow-hidden border-2 border-zinc-50 dark:border-zinc-800">
                                @if($detalle->menu && $detalle->menu->url_foto)
                                    <img src="{{ $detalle->menu->url_foto }}" alt="" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-zinc-300">
                                        <flux:icon.photo class="size-6" />
                                    </div>
                                @endif
                            </div>

                            <!-- Item Details -->
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-start gap-2">
                                    <h4 class="font-bold text-sm text-zinc-900 dark:text-white truncate">
                                        {{ $detalle->menu->nombre_menu ?? 'Item eliminado' }}
                                    </h4>
                                </div>
                                <p class="text-xs text-zinc-500 mb-1 truncate">{{ $detalle->menu->descripcion_menu ?? '' }}</p>
                                <div class="flex justify-between items-center">
                                    <span
                                        class="font-bold text-sm text-zinc-700 dark:text-zinc-300">{{ number_format($detalle->precio_unitario, 2) }}€</span>
                                    <span class="text-xs font-medium text-zinc-500">cantidad: {{ $detalle->cantidad }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Footer/Actions -->
                <div class="mt-auto pt-4 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-between">
                    <span
                        class="text-xs font-medium text-zinc-400 uppercase tracking-wide">{{ $order->detalles->sum('cantidad') }}
                        Artículos</span>

                    <div class="flex gap-3">
                        @if($order->estado->nombre_estado == 'Cancelado')
                            <button disabled
                                class="flex items-center gap-2 px-4 py-2 text-xs font-bold text-zinc-400 border border-zinc-300 rounded-lg uppercase tracking-wider">
                                <flux:icon.x-mark class="size-4" /> CANCELADO
                            </button>
                        @elseif($order->estado->nombre_estado == 'Completado')
                            <button disabled
                                class="flex items-center gap-2 px-4 py-2 text-xs font-bold text-zinc-400 border border-zinc-300 rounded-lg uppercase tracking-wider">
                                <flux:icon.check class="size-4" /> COMPLETADO
                            </button>
                        @else
                            <!-- Action Buttons -->
                            <button wire:click="cancelOrder({{ $order->id_pedido }})"
                                class="size-10 flex items-center justify-center text-zinc-400 hover:text-zinc-600 border border-zinc-300 rounded-lg hover:bg-zinc-50 transition-colors">
                                <flux:icon.x-mark class="size-5" />
                            </button>
                            <button wire:click="acceptOrder({{ $order->id_pedido }})"
                                class="size-10 flex items-center justify-center text-zinc-400 hover:text-zinc-600 border border-zinc-300 rounded-lg hover:bg-zinc-50 transition-colors">
                                <flux:icon.check class="size-5" />
                            </button>
                        @endif
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>