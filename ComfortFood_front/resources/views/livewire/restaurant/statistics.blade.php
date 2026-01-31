<div class="p-6">
    <div class="max-w-7xl mx-auto space-y-8">
        <!-- Header with Back Button -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="flex items-center gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Estadísticas y Reseñas</h1>
                    <p class="text-sm text-zinc-500">Consulta el rendimiento de tu restaurante y la valoración de tus
                        clientes</p>
                </div>
            </div>
            <flux:button variant="outline" icon="arrow-down-tray" onclick="window.print()">
                Exportar datos PDF
            </flux:button>
        </div>

        <!-- Actionable Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div
                class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm">
                <p class="text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-2">Pedidos (Hoy)</p>
                <div class="flex items-end justify-between">
                    <h3 class="text-3xl font-bold text-zinc-900 dark:text-white">
                        {{ $dailyOrders[now()->format('Y-m-d')] ?? 0 }}
                    </h3>
                    <div class="flex items-center text-green-500 text-xs font-bold">
                        <flux:icon.arrow-trending-up class="size-3 mr-1" />
                        +12%
                    </div>
                </div>
            </div>

            <div
                class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm">
                <p class="text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-2">Día de mayor demanda</p>
                @php $topDay = array_key_first($peakDays); @endphp
                <h3 class="text-xl font-bold text-zinc-900 dark:text-white">{{ $topDay ?: 'N/A' }}</h3>
                <p class="text-xs text-zinc-500 mt-1">Promedio de {{ $peakDays[$topDay] ?? 0 }} pedidos</p>
            </div>

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

            <div
                class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm font-['Outfit']">
                <p class="text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-2">Total Reseñas</p>
                <h3 class="text-3xl font-bold text-zinc-900 dark:text-white">{{ $satisfactionStats['total'] }}</h3>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Best Rated Menus -->
            <div class="lg:col-span-1 space-y-6">
                <h2 class="text-xl font-bold text-zinc-900 dark:text-white">Menús mejor valorados</h2>
                <div
                    class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl overflow-hidden shadow-sm">
                    <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                        @forelse($topMenus as $menu)
                            <div class="p-4 flex items-center justify-between">
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

                <!-- Chart Section (Simplified) -->
                <div
                    class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm">
                    <h3
                        class="font-bold text-sm text-zinc-900 dark:text-white mb-4 uppercase tracking-widest text-[10px]">
                        Evolución de ingresos</h3>
                    <div class="h-[200px] w-full">
                        <canvas id="earningsChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Reviews List -->
            <div class="lg:col-span-2 space-y-6" id="reviews-section">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <h2 class="text-xl font-bold text-zinc-900 dark:text-white">Listado de Reseñas</h2>

                    <div class="flex flex-wrap gap-2 w-full md:w-auto">
                        <flux:input wire:model.live.debounce.500ms="search" placeholder="Buscar..."
                            icon="magnifying-glass" class="!w-full md:!w-40" />
                        <flux:select wire:model.live="filterRating" class="!w-24">
                            <flux:select.option value="">⭐ Todas</flux:select.option>
                            <flux:select.option value="5">5 ⭐</flux:select.option>
                            <flux:select.option value="4">4 ⭐</flux:select.option>
                            <flux:select.option value="3">3 ⭐</flux:select.option>
                            <flux:select.option value="2">2 ⭐</flux:select.option>
                            <flux:select.option value="1">1 ⭐</flux:select.option>
                        </flux:select>
                        <flux:input type="date" wire:model.live="filterDate" class="!w-32" />
                    </div>
                </div>

                <div class="space-y-4">
                    @forelse($this->reviews as $review)
                        <div
                            class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm hover:border-pastel-orange/30 transition-colors">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-center gap-3">
                                    <flux:avatar size="sm" :src="$review->cliente->user->profile_photo_url"
                                        :name="$review->cliente->user->nombre_completo" />
                                    <div class="flex flex-col">
                                        <span
                                            class="font-bold text-zinc-900 dark:text-white">{{ $review->cliente->user->nombre_completo }}</span>
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
                                <p class="text-sm text-zinc-700 dark:text-zinc-300 italic">"{{ $review->comentario }}"</p>
                            @else
                                <p class="text-xs text-zinc-400 italic">Sin comentario.</p>
                            @endif

                            <div
                                class="mt-4 pt-4 border-t border-zinc-50 dark:border-zinc-800 flex justify-between items-center">
                                <span class="text-[10px] uppercase font-bold text-zinc-400 tracking-tighter">
                                    Fecha: {{ $review->created_at->format('d/m/Y H:i') }}
                                </span>
                                @if(!$review->visto)
                                    <span
                                        class="px-2 py-0.5 bg-pastel-orange text-white text-[9px] font-bold rounded-full uppercase">Nueva</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div
                            class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-12 text-center shadow-sm">
                            <flux:icon.star class="size-12 mx-auto text-zinc-200 mb-4" />
                            <p class="text-zinc-500">No se encontraron reseñas con los filtros seleccionados.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Script Chart.js -->
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('livewire:initialized', () => {
                const ctx = document.getElementById('earningsChart').getContext('2d');
                let chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: @js(array_keys($monthlyEarnings)),
                        datasets: [{
                            label: 'Ingresos (€)',
                            data: @js(array_values($monthlyEarnings)),
                            borderColor: '#FF8A5B',
                            backgroundColor: 'rgba(255, 138, 91, 0.1)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { display: false },
                            x: { display: false }
                        }
                    }
                });
            });
        </script>
    @endpush
</div>