<div class="min-h-screen px-4 py-8 md:px-8">
    <div class="max-w-4xl mx-auto space-y-8">
        <!-- Navigation -->
        <div class="flex items-center justify-between">
            <a href="{{ auth()->user()->isRestaurante() ? route('dashboard') : route('orders.history') }}" wire:navigate 
               class="flex items-center gap-2 text-zinc-600 dark:text-zinc-400 font-bold hover:text-zinc-900 dark:hover:text-white transition-colors group">
                <div class="p-2 bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm group-hover:bg-zinc-100 dark:group-hover:bg-zinc-800 transition-all">
                    <flux:icon.arrow-left class="size-4" />
                </div>
                Volver al historial
            </a>
            
            @php
                $status = $order->estado->nombre_estado;
                $statusClasses = match ($status) {
                    'En espera', 'Pendiente' => 'bg-amber-100 text-amber-700 border-amber-300 dark:bg-amber-900/40 dark:text-amber-300 dark:border-amber-700',
                    'En preparación' => 'bg-blue-100 text-blue-700 border-blue-300 dark:bg-blue-900/40 dark:text-blue-300 dark:border-blue-700',
                    'Enviado' => 'bg-indigo-100 text-indigo-700 border-indigo-300 dark:bg-indigo-900/40 dark:text-indigo-300 dark:border-indigo-700',
                    'Completado' => 'bg-emerald-500 text-white border-emerald-600 shadow-emerald-200/50 dark:border-emerald-400',
                    'Cancelado' => 'bg-rose-500 text-white border-rose-600 shadow-rose-200/50 dark:border-rose-400',
                    default => 'bg-zinc-100 text-zinc-600 border-zinc-200 dark:bg-zinc-800 dark:text-zinc-400 dark:border-zinc-700'
                };
            @endphp
            <div class="px-5 py-2 rounded-full text-[11px] font-black uppercase tracking-[0.2em] border shadow-sm transition-all duration-300 {{ $statusClasses }}">
                {{ $status }}
            </div>
        </div>

        <!-- Order Card -->
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-3xl overflow-hidden shadow-xl shadow-zinc-200/20 dark:shadow-none">
            <!-- Card Header -->
            <div class="p-8 md:p-12 border-b border-zinc-100 dark:border-zinc-800 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div class="space-y-1">
                    <h1 class="text-4xl font-black text-zinc-950 dark:text-white tracking-tight">Pedido #{{ $order->id_pedido }}</h1>
                    <p class="text-zinc-500 font-medium">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                </div>
                
                @if(auth()->user()->isRestaurante())
                    <div class="flex items-center gap-4 bg-zinc-50 dark:bg-zinc-800/50 p-4 rounded-2xl border border-zinc-100 dark:border-zinc-800">
                        <flux:avatar :src="$order->cliente->user->profile_photo_url" :name="$order->cliente->user->nombre_completo" size="lg" class="!rounded-xl" />
                        <div class="text-left">
                            <p class="text-xs font-bold text-zinc-400 uppercase tracking-widest">Cliente</p>
                            <p class="font-bold text-zinc-950 dark:text-white">{{ $order->cliente->user->nombre_completo }}</p>
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-4 bg-zinc-50 dark:bg-zinc-800/50 p-4 rounded-2xl border border-zinc-100 dark:border-zinc-800">
                        <flux:avatar :src="$order->restaurante->user->profile_photo_url" :name="$order->restaurante->user->nombre_completo" size="lg" class="!rounded-xl" />
                        <div class="text-left">
                            <p class="text-xs font-bold text-zinc-400 uppercase tracking-widest">Restaurante</p>
                            <p class="font-bold text-zinc-950 dark:text-white">{{ $order->restaurante->user->nombre_completo }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Card Content -->
            <div class="grid grid-cols-1 md:grid-cols-5 h-full">
                <!-- Delivery Info -->
                <div class="md:col-span-2 p-8 md:p-12 bg-zinc-50/50 dark:bg-zinc-900/50 space-y-8 border-r border-zinc-100 dark:border-zinc-800">
                    <section class="space-y-4">
                        <h4 class="text-xs font-black text-zinc-400 uppercase tracking-[0.2em]">Detalles de Entrega</h4>
                        <div class="space-y-6">
                            <div class="flex gap-4">
                                <flux:icon.map-pin class="size-5 text-zinc-400" />
                                <div class="text-sm font-medium text-zinc-800 dark:text-zinc-200">
                                    {{ $order->direccion_entrega }}
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <flux:icon.phone class="size-5 text-rose-500" />
                                <div class="text-lg font-black text-rose-600">
                                    {{ $order->cliente->telefono ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                    </section>
                    
                    <section class="space-y-4">
                        <div class="flex items-center gap-2">
                             <h4 class="text-xs font-black text-zinc-400 uppercase tracking-[0.2em]">Observaciones</h4>
                             <span class="px-1.5 py-0.5 rounded bg-zinc-100 dark:bg-zinc-800 text-[10px] font-bold text-zinc-500">{{ $order->detalles->filter(fn($d) => !empty($d->observaciones))->count() }}</span>
                        </div>
                        
                        @php
                            $itemsWithOkbservations = $order->detalles->filter(fn($d) => !empty($d->observaciones));
                        @endphp

                        @if($itemsWithOkbservations->isNotEmpty())
                            <div class="space-y-3">
                                @foreach($itemsWithOkbservations as $detalle)
                                    <div class="p-4 bg-white dark:bg-zinc-800 rounded-2xl border border-zinc-100 dark:border-zinc-700 text-sm">
                                        <p class="font-bold text-zinc-900 dark:text-white mb-1">{{ $detalle->menu->nombre_menu }}</p>
                                        <div class="flex gap-2 text-zinc-600 dark:text-zinc-400 italic bg-zinc-50 dark:bg-zinc-900/50 p-2 rounded-lg">
                                            <flux:icon.pencil-square class="size-4 flex-shrink-0 mt-0.5 text-zinc-400" />
                                            {{ $detalle->observaciones }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="p-4 bg-zinc-50 dark:bg-zinc-800/50 rounded-2xl border border-dashed border-zinc-200 dark:border-zinc-700 text-sm text-zinc-400 text-center italic">
                                No hay observaciones
                            </div>
                        @endif
                    </section>



                    <!-- Review Section (Dynamic) -->
                    @if($order->estado->nombre_estado === 'Completado')
                        <section class="space-y-4 pt-8 border-t border-zinc-100 dark:border-zinc-800">
                            <h4 class="text-xs font-black text-zinc-400 uppercase tracking-[0.2em]">Tu Valoración</h4>
                            
                            @if($order->resena)
                                <div class="bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-900/50 p-6 rounded-3xl space-y-3">
                                    <div class="flex text-amber-400 gap-1">
                                        @for($i = 0; $i < $order->resena->puntuacion; $i++)
                                            <flux:icon.star class="size-4 fill-current" />
                                        @endfor
                                    </div>
                                    <p class="text-sm text-zinc-700 dark:text-zinc-300 font-medium">"{{ $order->resena->comentario }}"</p>
                                    <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest">{{ $order->resena->created_at->format('d/m/Y') }}</p>
                                </div>
                            @elseif(auth()->user()->isCliente())
                                <div class="space-y-4 bg-white dark:bg-zinc-800 p-6 rounded-3xl border border-dashed border-indigo-200 dark:border-indigo-800">
                                    <div class="flex flex-col items-center gap-3">
                                        <p class="text-sm font-bold text-zinc-800 dark:text-zinc-200">¿Qué te ha parecido?</p>
                                        <div class="flex gap-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                <button wire:click="$set('rating', {{ $i }})" class="p-1 transition-transform hover:scale-125">
                                                    <flux:icon.star class="size-6 {{ $rating >= $i ? 'text-amber-400 fill-current' : 'text-zinc-200 dark:text-zinc-700' }}" />
                                                </button>
                                            @endfor
                                        </div>
                                    </div>
                                    <textarea wire:model="comment" placeholder="Escribe tu comentario aquí..." 
                                              class="w-full bg-zinc-50 dark:bg-zinc-900 border-none rounded-2xl p-4 text-sm focus:ring-2 focus:ring-indigo-500 dark:text-white transition-all min-h-[100px]"></textarea>
                                    <flux:button wire:click="saveReview" variant="primary" class="w-full !rounded-xl">Enviar reseña</flux:button>
                                </div>
                            @else
                                <div class="text-center py-6 text-zinc-400 italic text-sm">
                                    Esperando valoración del cliente...
                                </div>
                            @endif
                        </section>
                    @endif
                </div>

                <!-- Items List -->
                <div class="md:col-span-3 p-8 md:p-12 space-y-10">
                    <h4 class="text-xs font-black text-zinc-400 uppercase tracking-[0.2em]">Artículos del Pedido</h4>
                    
                    <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                        @foreach($order->detalles as $detalle)
                            <div class="py-6 first:pt-0 flex gap-6 group">
                                <div class="size-20 bg-zinc-100 dark:bg-zinc-800 rounded-2xl overflow-hidden flex-shrink-0 group-hover:scale-105 transition-transform duration-500">
                                    @if($detalle->menu && $detalle->menu->url_foto)
                                        <img src="{{ $detalle->menu->url_foto }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <flux:icon.photo class="size-8 text-zinc-300" />
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 space-y-1">
                                    <div class="flex justify-between items-start">
                                        <h4 class="font-black text-zinc-950 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                            {{ $detalle->menu->nombre_menu ?? 'Item' }}
                                        </h4>
                                        <span class="text-sm font-bold text-zinc-950 dark:text-white">{{ number_format($detalle->precio_unitario * $detalle->cantidad, 2) }}€</span>
                                    </div>
                                    <p class="text-sm text-zinc-500 dark:text-zinc-400 line-clamp-1 pr-12">{{ $detalle->menu->descripcion_menu ?? '' }}</p>

                                    <div class="flex items-center gap-3 pt-2">
                                        <span class="px-2 py-0.5 bg-zinc-100 dark:bg-zinc-800 rounded text-[10px] font-black text-zinc-500 dark:text-zinc-400 uppercase tracking-widest">x{{ $detalle->cantidad }}</span>
                                        <span class="text-[10px] text-zinc-400 font-bold uppercase">{{ number_format($detalle->precio_unitario, 2) }}€ / ud.</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Summary -->
                    <div class="pt-8 border-t-2 border-zinc-100 dark:border-zinc-800 flex justify-between items-end">
                        <div class="space-y-1">
                            <p class="text-xs font-black text-zinc-400 uppercase tracking-widest">Total del Pedido</p>
                            <p class="text-5xl font-black text-zinc-950 dark:text-white tracking-tighter">{{ number_format($order->precio_total, 2) }}<span class="text-2xl ml-1">€</span></p>
                        </div>
                        <div class="text-right">
                             <p class="text-xs font-black text-zinc-400 uppercase tracking-widest">Artículos</p>
                             <p class="text-xl font-black text-zinc-950 dark:text-white">{{ $order->detalles->sum('cantidad') }}</p>
                        </div>
                    </div>

                    <!-- Actions for Restaurant -->
                    @if(auth()->user()->isRestaurante() && !in_array($order->estado->nombre_estado, ['Completado', 'Cancelado']))
                        <div class="pt-12 flex gap-4">
                            @if($order->estado->nombre_estado === 'Pendiente')
                                <button wire:click="confirmCancel" class="flex-1 py-4 bg-zinc-50 hover:bg-rose-50 dark:bg-zinc-800 dark:hover:bg-rose-900/30 text-rose-600 font-black rounded-2xl border border-rose-100 dark:border-rose-900/50 transition-all uppercase tracking-widest text-xs flex items-center justify-center gap-2">
                                    <flux:icon.x-mark class="size-5" /> Cancelar
                                </button>
                            @endif
                            
                            @php
                                $actionConfig = match($order->estado->nombre_estado) {
                                    'Pendiente' => ['text' => 'Aceptar Pedido', 'icon' => 'check', 'color' => 'bg-emerald-600 hover:bg-emerald-700 shadow-emerald-500/30'],
                                    'En Preparación' => ['text' => 'Marcar Enviado/Entregado', 'icon' => 'truck', 'color' => 'bg-blue-600 hover:bg-blue-700 shadow-blue-500/30'],
                                    'Entregado' => ['text' => 'Completar Pedido', 'icon' => 'check-circle', 'color' => 'bg-zinc-600 hover:bg-zinc-700 shadow-zinc-500/30'],
                                    default => ['text' => 'Avanzar Estado', 'icon' => 'arrow-right', 'color' => 'bg-emerald-600']
                                };
                            @endphp

                            <button wire:click="advanceStatus" class="flex-[2] py-4 {{ $actionConfig['color'] }} text-white font-bold rounded-2xl shadow-lg transition-all uppercase tracking-widest text-xs flex items-center justify-center gap-2">
                                @if($actionConfig['icon'] === 'check') <flux:icon.check class="size-5" /> @endif
                                @if($actionConfig['icon'] === 'truck') <flux:icon.truck class="size-5" /> @endif
                                @if($actionConfig['icon'] === 'check-circle') <flux:icon.check-circle class="size-5" /> @endif
                                {{ $actionConfig['text'] }}
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>