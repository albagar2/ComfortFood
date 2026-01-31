<div class="p-4 md:p-6">
    @if(session()->has('success'))
        <div
            class="mb-4 p-3 bg-green-100 dark:bg-green-900 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 rounded-lg">
            {{ session('success') }}
        </div>
    @endif
    @if(session()->has('error'))
        <div
            class="mb-4 p-3 bg-red-100 dark:bg-red-900 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <h1 class="text-2xl font-bold text-zinc-900 dark:text-white mb-6">Gestión de Pedidos</h1>

    <!-- Tabs -->
    <div class="flex gap-2 mb-6 border-b border-zinc-200 dark:border-zinc-800">
        <button wire:click="setTab('pendiente')"
            class="px-4 py-2 font-medium {{ $activeTab === 'pendiente' ? 'text-pastel-orange border-b-2 border-pastel-orange' : 'text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300' }}">
            Pendientes
        </button>
        <button wire:click="setTab('aceptado')"
            class="px-4 py-2 font-medium {{ $activeTab === 'aceptado' ? 'text-pastel-orange border-b-2 border-pastel-orange' : 'text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300' }}">
            Aceptados
        </button>
        <button wire:click="setTab('en preparación')"
            class="px-4 py-2 font-medium {{ $activeTab === 'en preparación' ? 'text-pastel-orange border-b-2 border-pastel-orange' : 'text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300' }}">
            En Preparación
        </button>
        <button wire:click="setTab('completado')"
            class="px-4 py-2 font-medium {{ $activeTab === 'completado' ? 'text-pastel-orange border-b-2 border-pastel-orange' : 'text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300' }}">
            Completados
        </button>
        <button wire:click="setTab('cancelado')"
            class="px-4 py-2 font-medium {{ $activeTab === 'cancelado' ? 'text-pastel-orange border-b-2 border-pastel-orange' : 'text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300' }}">
            Cancelados
        </button>
        <button wire:click="setTab('todos')"
            class="px-4 py-2 font-medium {{ $activeTab === 'todos' ? 'text-pastel-orange border-b-2 border-pastel-orange' : 'text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300' }}">
            Todos
        </button>
    </div>

    <!-- Orders List -->
    @if(count($orders) === 0)
        <div class="text-center py-12">
            <flux:icon.clipboard-document-list class="size-16 text-zinc-300 mx-auto mb-4" />
            <p class="text-zinc-500">No hay pedidos en esta categoría</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($orders as $order)
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg p-4 md:p-6">
                    <!-- Order Header -->
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="font-bold text-lg text-zinc-900 dark:text-white">Pedido #{{ $order['id_pedido'] }}</h3>
                            <p class="text-sm text-zinc-500">{{ $order['cliente']['user']['nombre_completo'] ?? 'Cliente' }}</p>
                            <p class="text-xs text-zinc-400">
                                {{ \Carbon\Carbon::parse($order['created_at'])->format('d/m/Y H:i') }}
                            </p>
                        </div>
                        <div class="flex flex-col items-end gap-2">
                            <span class="px-3 py-1 rounded-full text-sm font-semibold
                                                        @if($order['estado']['nombre_estado'] === 'Pendiente') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                        @elseif($order['estado']['nombre_estado'] === 'Aceptado') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                        @elseif($order['estado']['nombre_estado'] === 'En Preparación') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                                                        @elseif($order['estado']['nombre_estado'] === 'Completado') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                        @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                                        @endif">
                                {{ $order['estado']['nombre_estado'] }}
                            </span>
                            <span
                                class="text-xl font-bold text-zinc-900 dark:text-white">{{ number_format($order['precio_total'], 2) }}€</span>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="mb-4 space-y-2">
                        <h4 class="font-semibold text-zinc-700 dark:text-zinc-300">Artículos:</h4>
                        @foreach($order['detalles'] as $detalle)
                            <div class="flex justify-between text-sm">
                                <span class="text-zinc-600 dark:text-zinc-400">{{ $detalle['cantidad'] }}x
                                    {{ $detalle['menu']['nombre_menu'] ?? 'Menú eliminado' }}</span>
                                <span
                                    class="text-zinc-900 dark:text-white font-medium">{{ number_format($detalle['cantidad'] * $detalle['precio_unitario'], 2) }}€</span>
                            </div>
                        @endforeach
                    </div>

                    <!-- Delivery Address -->
                    <div class="mb-4">
                        <p class="text-sm text-zinc-600 dark:text-zinc-400">
                            <span class="font-semibold">Dirección:</span> {{ $order['direccion_entrega'] }}
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-2 flex-wrap">
                        @if($order['estado']['nombre_estado'] === 'Pendiente')
                            <button wire:click="advanceStatus({{ $order['id_pedido'] }})"
                                class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors font-semibold shadow-emerald-500/20 shadow-lg">
                                Aceptar Pedido
                            </button>
                            <button wire:click="rejectOrder({{ $order['id_pedido'] }})"
                                class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors font-semibold">
                                Rechazar
                            </button>
                        @elseif($order['estado']['nombre_estado'] === 'En Preparación')
                            <button wire:click="advanceStatus({{ $order['id_pedido'] }})"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold shadow-blue-500/20 shadow-lg">
                                Marcar Entregado
                            </button>
                        @elseif($order['estado']['nombre_estado'] === 'Entregado')
                            <button wire:click="advanceStatus({{ $order['id_pedido'] }})"
                                class="px-4 py-2 bg-zinc-600 text-white rounded-lg hover:bg-zinc-700 transition-colors font-semibold shadow-zinc-500/20 shadow-lg">
                                Marcar Completado
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>