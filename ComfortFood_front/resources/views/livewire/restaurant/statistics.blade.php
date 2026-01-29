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

        <!-- Chart Section -->
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h3 class="font-bold text-zinc-900 dark:text-white">Evolución de ingresos</h3>
                </div>
                <flux:select class="!w-40" placeholder="Último año">
                    <flux:select.option>Último año</flux:select.option>
                    <flux:select.option>2024</flux:select.option>
                </flux:select>
            </div>

            <div class="h-[400px] w-full" x-data="{
                init() {
                    const ctx = document.getElementById('earningsChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: @js(array_keys($monthlyEarnings)),
                            datasets: [{
                                label: 'Ingresos (€)',
                                data: @js(array_values($monthlyEarnings)),
                                backgroundColor: '#71717a',
                                borderRadius: 8,
                                borderSkipped: false,
                                hoverBackgroundColor: '#3f3f46',
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    backgroundColor: '#18181b',
                                    titleFont: { size: 13, weight: 'bold' },
                                    bodyFont: { size: 12 },
                                    padding: 12,
                                    usePointStyle: true,
                                    callbacks: {
                                        label: (context) => ` ${context.parsed.y}€`
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: { color: '#f4f4f5', drawBorder: false },
                                    ticks: { color: '#a1a1aa', font: { size: 11 } }
                                },
                                x: {
                                    grid: { display: false },
                                    ticks: { color: '#a1a1aa', font: { size: 11 } }
                                }
                            }
                        }
                    });
                }
            }">
                <canvas id="earningsChart"></canvas>
            </div>
        </div>

        <!-- Satisfaction Section -->
        <div class="space-y-6">
            <h2 class="text-xl font-bold text-zinc-900 dark:text-white">Satisfacción del cliente</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Stats Card -->
                <div
                    class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-8 shadow-sm text-center flex flex-col items-center justify-center space-y-4">
                    <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Reseñas en <span
                            class="font-bold text-zinc-900 dark:text-white">{{ $satisfactionStats['year'] }}</span></p>

                    <div class="space-y-1">
                        <p class="text-lg font-medium text-zinc-900 dark:text-white">Promedio:
                            {{ $satisfactionStats['promedio'] }} / 5 ⭐
                        </p>
                        <p class="text-sm text-zinc-500">{{ $satisfactionStats['diff'] }} respecto al año anterior</p>
                    </div>

                    <div class="flex gap-6 mt-2">
                        <div class="flex items-center gap-2">
                            <span class="size-3 rounded-full bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.5)]"></span>
                            <span
                                class="text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ $satisfactionStats['positivas_pct'] }}%
                                positivas</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="size-3 rounded-full bg-red-500 shadow-[0_0_8px_rgba(239,68,68,0.5)]"></span>
                            <span
                                class="text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ $satisfactionStats['negativas_pct'] }}%
                                negativas</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script Chart.js -->
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endpush
</div>