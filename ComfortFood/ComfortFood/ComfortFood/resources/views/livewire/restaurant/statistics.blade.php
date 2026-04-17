<div class="p-6">
    <div class="max-w-7xl mx-auto space-y-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <flux:heading size="xl" level="1">Estadísticas y Reseñas</flux:heading>
                <flux:subheading>Consulta el rendimiento de tu restaurante y las opiniones de tus clientes
                </flux:subheading>
            </div>
            <flux:button variant="outline" icon="arrow-down-tray" onclick="window.print()">
                Exportar datos PDF
            </flux:button>
        </div>

        <!-- Metric Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Orders Today -->
            <div
                class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm">
                <p class="text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-2">Pedidos (Hoy)</p>
                <div class="flex items-end justify-between">
                    <h3 class="text-3xl font-bold text-zinc-900 dark:text-white">{{ $stats['orders_today'] }}</h3>
                    <div class="flex items-center text-emerald-500 text-xs font-bold">
                        <flux:icon.bolt class="size-3 mr-1" />
                        Activos
                    </div>
                </div>
            </div>

            <!-- Orders Week -->
            <div
                class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm">
                <p class="text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-2">Pedidos (Esta Semana)</p>
                <h3 class="text-3xl font-bold text-zinc-900 dark:text-white">{{ $stats['orders_week'] }}</h3>
                <p class="text-[10px] text-zinc-500 mt-1 italic">Desde el lunes</p>
            </div>

            <!-- Avg Rating Global -->
            <div
                class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm">
                <p class="text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-2">Valoración Media</p>
                <div class="flex items-center gap-2">
                    <h3 class="text-3xl font-bold text-zinc-900 dark:text-white">{{ $satisfactionStats['promedio'] }}
                    </h3>
                    <div class="flex text-yellow-400">
                        @for($i = 1; $i <= 5; $i++)
                            <flux:icon.star variant="{{ $satisfactionStats['promedio'] >= $i ? 'solid' : 'outline' }}"
                                class="size-4" />
                        @endfor
                    </div>
                </div>
            </div>

            <!-- Total Reviews -->
            <div
                class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm">
                <p class="text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-2">Valoración (7 días)</p>
                <div class="flex items-center gap-2">
                    <h3 class="text-3xl font-bold text-zinc-900 dark:text-white">{{ $stats['rating_last_7_days'] }}</h3>
                    <div
                        class="px-2 py-0.5 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 text-[10px] font-bold rounded-full">
                        Reciente
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Menus and Peak Days -->
            <div class="lg:col-span-1 space-y-8">
                <!-- Best Rated Menus -->
                <section class="space-y-4">
                    <h2 class="text-lg font-bold text-zinc-900 dark:text-white flex items-center gap-2">
                        <flux:icon.trophy class="size-5 text-yellow-500" />
                        Menús mejor valorados
                    </h2>
                    <div
                        class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl overflow-hidden shadow-sm">
                        <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                            @forelse($topMenus as $menu)
                                <div
                                    class="p-4 flex items-center justify-between hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                                    <div class="flex flex-col">
                                        <span
                                            class="font-bold text-sm text-zinc-900 dark:text-white">{{ $menu->nombre_menu }}</span>
                                        <span class="text-[10px] text-zinc-500">{{ $menu->count }} valoraciones</span>
                                    </div>
                                    <div class="flex items-center gap-1 text-yellow-500 font-bold">
                                        <flux:icon.star variant="solid" class="size-3" />
                                        <span class="text-xs">{{ number_format($menu->avg_rating, 1) }}</span>
                                    </div>
                                </div>
                            @empty
                                <div class="p-8 text-center text-zinc-500 text-sm">No hay datos suficientes</div>
                            @endforelse
                        </div>
                    </div>
                </section>

                <!-- Peak Days -->
                <section class="space-y-4">
                    <h2 class="text-lg font-bold text-zinc-900 dark:text-white flex items-center gap-2">
                        <flux:icon.calendar-days class="size-5 text-pastel-orange" />
                        Días de mayor demanda
                    </h2>
                    <div
                        class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-4 shadow-sm space-y-3">
                        @forelse($peakDays as $day => $count)
                            <div class="flex items-center justify-between text-sm">
                                <span class="capitalize text-zinc-600 dark:text-zinc-400 font-medium">{{ $day }}</span>
                                <div class="flex items-center gap-3 flex-1 px-4">
                                    <div class="h-1.5 flex-1 bg-zinc-100 dark:bg-zinc-800 rounded-full overflow-hidden">
                                        <div class="h-full bg-pastel-orange rounded-full"
                                            style="width: {{ min(100, ($count / (max($peakDays) ?: 1)) * 100) }}%"></div>
                                    </div>
                                    <span
                                        class="font-bold text-zinc-900 dark:text-white min-w-[20px] text-right">{{ $count }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-zinc-500 text-sm py-4">Sin datos de pedidos</p>
                        @endforelse
                        <p class="text-[10px] text-zinc-400 italic pt-2">* Basado en los últimos 90 días</p>
                    </div>
                </section>
            </div>

            <!-- Right Column: Reviews List -->
            <div class="lg:col-span-2 space-y-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <h2 class="text-xl font-bold text-zinc-900 dark:text-white">Listado de Reseñas</h2>

                    <div class="flex flex-wrap gap-2 w-full sm:w-auto">
                        <flux:input wire:model.live.debounce.500ms="search" placeholder="Buscar..."
                            icon="magnifying-glass" class="!w-full sm:!w-40" />
                        <flux:select wire:model.live="filterRating" class="!w-30">
                            <flux:select.option value="">⭐ Todas</flux:select.option>
                            <flux:select.option value="5">5 ⭐</flux:select.option>
                            <flux:select.option value="4">4 ⭐</flux:select.option>
                            <flux:select.option value="3">3 ⭐</flux:select.option>
                            <flux:select.option value="2">2 ⭐</flux:select.option>
                            <flux:select.option value="1">1 ⭐</flux:select.option>
                        </flux:select>
                        <flux:input type="date" wire:model.live="filterDate" class="!w-32 text-xs" />
                    </div>
                </div>

                <div class="space-y-4">
                    @forelse($this->reviews as $review)
                        <div
                            class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm hover:border-pastel-orange/30 transition-shadow">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-center gap-3">
                                    <flux:avatar size="sm" :src="$review->cliente->user->profile_photo_url"
                                        :name="$review->cliente->user->nombre_completo" />
                                    <div class="flex flex-col">
                                        <span
                                            class="font-bold text-zinc-900 dark:text-white leading-tight">{{ $review->cliente->user->nombre_completo }}</span>
                                        <span class="text-[10px] text-zinc-500">{{ $review->created_at->diffForHumans() }} •
                                            Pedido #{{ $review->id_pedido }}</span>
                                    </div>
                                </div>
                                <div class="flex gap-0.5">
                                    @for($i = 1; $i <= 5; $i++)
                                        <flux:icon.star variant="{{ $review->puntuacion >= $i ? 'solid' : 'outline' }}"
                                            class="size-4 {{ $review->puntuacion >= $i ? 'text-yellow-400' : 'text-zinc-200' }}" />
                                    @endfor
                                </div>
                            </div>

                            @if($review->comentario)
                                <p
                                    class="text-sm text-zinc-700 dark:text-zinc-300 bg-zinc-50 dark:bg-zinc-800/50 p-4 rounded-xl italic">
                                    "{{ $review->comentario }}"
                                </p>
                            @else
                                <p class="text-xs text-zinc-400 italic pl-4 border-l-2 border-zinc-100 dark:border-zinc-800">
                                    Sin comentario escrito.
                                </p>
                            @endif

                            <div
                                class="mt-4 pt-4 border-t border-zinc-50 dark:border-zinc-800 flex justify-between items-center">
                                <span class="text-[10px] uppercase font-bold text-zinc-400 tracking-wider">
                                    {{ $review->created_at->format('d/m/Y H:i') }}
                                </span>
                                @if(!$review->visto)
                                    <span
                                        class="px-2 py-0.5 bg-pastel-orange text-white text-[9px] font-extrabold rounded-full uppercase tracking-tighter">Nueva</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div
                            class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-16 text-center shadow-sm">
                            <flux:icon.chat-bubble-bottom-center-text class="size-12 mx-auto text-zinc-200 mb-4" />
                            <p class="text-zinc-500 font-medium">No se encontraron reseñas</p>
                            <p class="text-xs text-zinc-400 mt-1">Prueba a cambiar los filtros de búsqueda</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>