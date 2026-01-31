<div class="min-h-screen  px-4 py-8 md:px-8">
    <div class="max-w-7xl mx-auto space-y-10">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 bg-white dark:bg-zinc-900 p-8 rounded-3xl border-2 border-zinc-200 dark:border-zinc-800 shadow-sm">
            <div class="space-y-1">
                <h1 class="text-3xl font-black text-zinc-950 dark:text-white tracking-tight">Panel de Gestión</h1>
                <p class="text-zinc-500 font-medium">¡Hola, {{ auth()->user()->nombre_completo }}! Tienes <span class="text-indigo-600 dark:text-indigo-400 font-bold">{{ $orders->whereNotIn('estado.nombre_estado', ['Completado', 'Cancelado'])->count() }}</span> pedidos activos para hoy.</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                <div class="flex-1 md:flex-none">
                    <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass" placeholder="Buscar pedido..." class="min-w-[240px] !rounded-xl" />
                </div>
                <flux:button variant="primary" icon="plus" href="{{ route('menu.edit') }}" wire:navigate class="!rounded-xl">Añadir Menú</flux:button>
                
                <div class="flex gap-2">
                    <a href="{{ route('menu.index') }}" wire:navigate title="Gestionar Carta" class="p-2.5 bg-zinc-50 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 border-2 border-zinc-200 dark:border-zinc-700 rounded-xl hover:bg-zinc-100 dark:hover:bg-zinc-700 transition-all">
                        <flux:icon.document-text class="size-5" />
                    </a>
                    <a href="{{ route('restaurant.show', auth()->user()->restaurante->id_restaurante) }}" wire:navigate title="Ver Perfil Público" class="p-2.5 bg-zinc-50 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 border border-zinc-200 dark:border-zinc-700 rounded-xl hover:bg-zinc-100 dark:hover:bg-zinc-700 transition-all">
                        <flux:icon.eye class="size-5" />
                    </a>
                </div>
            </div>
        </div>

        <!-- Order Activity Tracker -->
        <section class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-xs font-black text-zinc-400 uppercase tracking-[0.2em]">Actividad Reciente</h2>
                <div class="h-px flex-1 bg-zinc-200 dark:bg-zinc-800 mx-6"></div>
            </div>
            
            <div class="flex gap-4 overflow-x-auto pb-4 scrollbar-hide snap-x">
                @forelse($orders->take(8) as $topOrder)
                    @php
                        $isFinished = in_array($topOrder->estado->nombre_estado, ['Completado', 'Cancelado']);
                        $statusStyles = match($topOrder->estado->nombre_estado) {
                            'Completado' => 'border-emerald-500/30 bg-emerald-50 dark:bg-emerald-900/10',
                            'Cancelado' => 'border-rose-500/30 bg-rose-50 dark:bg-rose-900/10',
                            'En preparación' => 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/10',
                            default => 'border-zinc-200 bg-white dark:bg-zinc-800 dark:border-zinc-700'
                        };
                    @endphp
                    <a href="{{ route('orders.details', $topOrder->id_pedido) }}" wire:navigate 
                       class="flex items-center gap-3 px-5 py-3 min-w-[160px] border-3 rounded-2xl shadow-sm snap-start hover:scale-105 transition-all duration-300 {{ $statusStyles }}">
                        <div class="size-2 rounded-full {{ $isFinished ? 'bg-zinc-300' : 'bg-indigo-500 animate-pulse' }}"></div>
                        <div class="flex flex-col">
                            <span class="text-xs font-black text-zinc-950 dark:text-white">#{{ $topOrder->id_pedido }}</span>
                            <span class="text-[10px] font-bold text-zinc-400 uppercase">{{ $topOrder->created_at->format('H:i') }}</span>
                        </div>
                    </a>
                @empty
                    <div class="py-4 text-zinc-400 text-sm italic">No hay actividad hoy.</div>
                @endforelse
            </div>
        </section>

        <!-- Main Orders Grid -->
        <section class="space-y-6">
            <h2 class="text-2xl font-black text-zinc-950 dark:text-white uppercase tracking-tight">Gestión de Comandas</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($orders as $order)
                    <div class="group bg-white dark:bg-zinc-900 border-2 border-zinc-200 dark:border-zinc-800 rounded-3xl overflow-hidden hover:shadow-2xl hover:shadow-zinc-200/50 dark:hover:shadow-none transition-all duration-500 flex flex-col h-full relative">
                        <!-- Card Status Badge -->
                        @php
                            $statusInfo = match ($order->estado->nombre_estado) {
                                'En espera', 'Pendiente' => ['class' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400', 'icon' => 'clock'],
                                'En preparación' => ['class' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400', 'icon' => 'fire'],
                                'Completado' => ['class' => 'bg-emerald-500 text-white', 'icon' => 'check-circle'],
                                'Cancelado' => ['class' => 'bg-rose-500 text-white', 'icon' => 'x-circle'],
                                default => ['class' => 'bg-zinc-100 text-zinc-600', 'icon' => 'hashtag']
                            };
                        @endphp
                        <div class="absolute top-1 right-2 z-10 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $statusInfo['class'] }}">
                            {{ $order->estado->nombre_estado }}
                        </div>

                        <a href="{{ route('orders.details', $order->id_pedido) }}" wire:navigate class="p-6 flex-1 space-y-6">
                            <!-- Customer Info -->
                            <div class="flex items-center gap-4">
                                <flux:avatar :src="$order->cliente->user->profile_photo_url" :name="$order->cliente->user->nombre_completo" size="sm" class="!rounded-xl" />
                                <div>
                                    <h3 class="font-bold text-zinc-950 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                        {{ $order->cliente->user->nombre_completo }}
                                    </h3>
                                    <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Pedido #{{ $order->id_pedido }} • {{ $order->created_at->diffForHumans() }}</p>
                                </div>
                            </div>

                            <!-- Items Preview -->
                            <div class="space-y-4">
                                @foreach($order->detalles->take(2) as $detalle)
                                    <div class="flex items-center gap-3">
                                        <div class="size-10 rounded-xl bg-zinc-50 dark:bg-zinc-800 overflow-hidden border-2 border-zinc-100 dark:border-zinc-700 shrink-0">
                                            @if($detalle->menu && $detalle->menu->url_foto)
                                                <img src="{{ $detalle->menu->url_foto }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center">
                                                    <flux:icon.photo class="size-4 text-zinc-300" />
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-bold text-zinc-800 dark:text-zinc-200 truncate">{{ $detalle->menu->nombre_menu ?? 'Item' }}</p>
                                            <p class="text-[10px] text-zinc-500 tracking-tight">Cantidad: {{ $detalle->cantidad }}</p>
                                        </div>
                                    </div>
                                @endforeach

                                @if($order->detalles->count() > 2)
                                    <p class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest">+{{ $order->detalles->count() - 2 }} artículos más</p>
                                @endif
                            </div>
                        </a>

                        <!-- Actions Area -->
                        <div class="p-4 bg-zinc-50/50 dark:bg-zinc-900/50 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-between gap-3">
                            <div class="flex flex-col">
                                <span class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Total</span>
                                <span class="font-black text-zinc-950 dark:text-white">{{ number_format($order->precio_total, 2) }}€</span>
                            </div>

                            <div class="flex gap-2">
                                @if(in_array($order->estado->nombre_estado, ['Pendiente', 'En Preparación', 'Entregado']))
                                    <button wire:click="cancelOrder({{ $order->id_pedido }})" class="p-2 text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/30 rounded-xl transition-all" title="Cancelar">
                                        <flux:icon.x-mark class="size-5" />
                                    </button>

                                    @php
                                        $actionConfig = match($order->estado->nombre_estado) {
                                            'Pendiente' => ['title' => 'Aceptar', 'icon' => 'check', 'color' => 'bg-emerald-600 hover:bg-emerald-700 shadow-emerald-500/20'],
                                            'En Preparación' => ['title' => 'Entregar', 'icon' => 'truck', 'color' => 'bg-blue-600 hover:bg-blue-700 shadow-blue-500/20'],
                                            'Entregado' => ['title' => 'Completar', 'icon' => 'check-circle', 'color' => 'bg-zinc-600 hover:bg-zinc-700 shadow-zinc-500/20'],
                                            default => ['title' => 'Avanzar', 'icon' => 'arrow-right', 'color' => 'bg-emerald-600']
                                        };
                                    @endphp
                                    <button wire:click="advanceStatus({{ $order->id_pedido }})" title="{{ $actionConfig['title'] }}" class="px-4 py-2 {{ $actionConfig['color'] }} text-white text-[10px] font-black uppercase tracking-widest rounded-xl shadow-lg transition-all flex items-center gap-2">
                                        @if($actionConfig['icon'] === 'check') <flux:icon.check class="size-5" /> @endif
                                        @if($actionConfig['icon'] === 'truck') <flux:icon.truck class="size-5" /> @endif
                                        @if($actionConfig['icon'] === 'check-circle') <flux:icon.check-circle class="size-5" /> @endif
                                    </button>
                                @elseif(!in_array($order->estado->nombre_estado, ['Completado', 'Cancelado']))
                                     {{-- Fallback for unmatched states --}}
                                     <button wire:click="advanceStatus({{ $order->id_pedido }})" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-black uppercase tracking-widest rounded-xl shadow-lg shadow-emerald-500/20 transition-all flex items-center gap-2">
                                        <flux:icon.check class="size-5" /> Avanzar
                                    </button>
                                @else
                                    <a href="{{ route('orders.details', $order->id_pedido) }}" wire:navigate class="px-4 py-2 bg-zinc-100 dark:bg-zinc-800 text-zinc-500 dark:text-zinc-400 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all">
                                        Ver Detalles
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </div>
</div>