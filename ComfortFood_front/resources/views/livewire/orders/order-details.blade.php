<div class="flex flex-col gap-6 p-6">
    <!-- Back Link -->
    <div class="flex items-center gap-2 mb-2">
        <a href="{{ Auth::user()->isRestaurante() ? route('dashboard') : route('orders.history') }}" wire:navigate
            class="flex items-center gap-2 text-zinc-900 font-bold hover:underline">
            <flux:icon.arrow-left class="size-4" />
            Volver al historial
        </a>
    </div>

    <h3 class="text-lg font-bold text-zinc-800 dark:text-zinc-100">Detalle del pedido</h3>

    <!-- Main Card -->
    <div class="bg-white dark:bg-zinc-900 border border-blue-400 rounded-none p-8 border-dashed shadow-sm relative">
        <!-- Using dashed border per design hint in image logic, though image shows dots/dashed outline -->

        <!-- Order Header -->
        <div class="flex flex-col md:flex-row justify-between items-start mb-12">
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Pedido #{{ $order->id_pedido }}</h1>
            <div class="flex items-center gap-4 text-sm text-zinc-500">
                <span>{{ $order->created_at->format('d M Y, h:i A') }}
                    ({{ $order->created_at->diffForHumans() }})</span>
                @php
                    $statusColor = match ($order->estado->nombre_estado) {
                        'En espera' => 'text-yellow-500',
                        'En preparación' => 'text-blue-500',
                        'Completado' => 'text-green-500',
                        'Cancelado' => 'text-red-500',
                        default => 'text-zinc-500'
                    };
                @endphp
                <span class="flex items-center gap-1 font-medium {{ $statusColor }}">
                    <span class="size-2 rounded-full bg-current"></span>
                    {{ $order->estado->nombre_estado }}
                </span>
            </div>
        </div>

        <div class="flex flex-col md:flex-row gap-12 relative">
            <!-- Vertical Divider Implementation -->
            <div class="hidden md:block absolute left-1/3 top-10 bottom-10 w-px bg-zinc-200"></div>

            <!-- Left Column: Customer & Delivery Info -->
            <div class="w-full md:w-1/3 flex flex-col items-center text-center px-4">
                <!-- Customer Avatar placeholder if distinct from top right, or just icon -->
                <div class="size-20 bg-zinc-200 dark:bg-zinc-800 rounded-2xl flex items-center justify-center mb-6">
                    <flux:icon.photo class="size-10 text-zinc-400" />
                </div>

                <p class="text-sm text-zinc-500 mb-2">realizado por {{ $order->cliente->user->nombre_completo }}</p>
                <p class="text-xl font-bold text-red-500 mb-8">{{ $order->cliente->telefono ?? '65 56 77 789' }}
                    <flux:icon.phone class="size-4 inline text-red-500" />
                </p>

                <div class="w-full text-left space-y-6 text-sm text-zinc-600 dark:text-zinc-400">
                    <div>
                        <p>Entrega a domicilio : {{ $order->direccion_entrega }}</p>
                    </div>
                    <div>
                        <p>Tiempo de entrega estimado: 30 minutos</p>
                    </div>
                    <div>
                        <p>Método de pago: tarjeta</p>
                    </div>
                    <div>
                        <p>Observación cliente: sin cebolla, extra salsa</p>
                    </div>
                </div>

                <!-- Review Box if exists or placeholder -->
                <div class="mt-12 border border-blue-300 bg-white p-4 rounded-lg w-full relative">
                    <div class="flex justify-center mb-2 text-yellow-400 gap-1">
                        @for($i = 0; $i < 5; $i++)
                        <flux:icon.star class="size-4 fill-current" /> @endfor
                    </div>
                    <p class="text-center font-medium text-zinc-900 text-sm mb-1">Comentario opcional</p>
                    <p
                        class="text-center text-xs text-zinc-400 dotted-underline p-1 border-b border-blue-200 border-dashed inline-block w-full">
                        Fecha - Nombre cliente</p>
                </div>
            </div>

            <!-- Right Column: Order Items -->
            <div class="w-full md:w-2/3 md:pl-12 space-y-6">
                @foreach($order->detalles as $detalle)
                    <div class="flex gap-4 items-start">
                        <div class="size-14 bg-zinc-200 dark:bg-zinc-800 rounded-xl flex-shrink-0 overflow-hidden">
                            @if($detalle->menu && $detalle->menu->url_foto)
                                <img src="{{ $detalle->menu->url_foto }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <flux:icon.photo class="size-6 text-zinc-400" />
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between">
                                <div>
                                    <h4 class="font-bold text-zinc-900 dark:text-white">
                                        {{ $detalle->menu->nombre_menu ?? 'Item' }}
                                    </h4>
                                    <p class="text-xs text-zinc-500">{{ $detalle->menu->descripcion_menu ?? '' }}</p>
                                </div>
                            </div>
                            <div class="flex justify-between items-end mt-2">
                                <span
                                    class="font-bold text-zinc-900 dark:text-white">{{ number_format($detalle->precio_unitario, 2) }}€</span>
                                <span class="text-sm text-zinc-500">cantidad: {{ $detalle->cantidad }}</span>
                            </div>
                        </div>
                    </div>
                    <!-- Divider -->
                    <div class="h-px bg-zinc-100 dark:bg-zinc-800 w-full mb-4"></div>
                @endforeach

                <div class="flex justify-end">
                    <button class="text-xs text-zinc-400 hover:text-zinc-600">+2 artículos, ver más</button>
                </div>

                <div class="pt-8 flex justify-between items-center mt-auto">
                    <span class="font-bold text-lg text-zinc-900 dark:text-white">Total:
                        {{ number_format($order->precio_total, 2) }}€</span>
                    <span class="text-sm text-zinc-500">cantidad: {{ $order->detalles->sum('cantidad') }}</span>
                </div>
            </div>
        </div>

        <!-- Action Buttons (Only for Restaurant/Pending) -->
        @if(Auth::user()->isRestaurante() && !in_array($order->estado->nombre_estado, ['Completado', 'Cancelado']))
            <div class="flex justify-center gap-4 mt-8">
                <button wire:click="cancelOrder"
                    class="size-14 flex items-center justify-center rounded-xl border border-zinc-300 text-zinc-400 hover:bg-zinc-50 hover:text-red-500 transition-colors">
                    <flux:icon.x-mark class="size-8" />
                </button>
                <button wire:click="acceptOrder"
                    class="size-14 flex items-center justify-center rounded-xl border border-zinc-300 text-zinc-400 hover:bg-zinc-50 hover:text-green-500 transition-colors">
                    <flux:icon.check class="size-8" />
                </button>
            </div>
        @endif
    </div>
</div>