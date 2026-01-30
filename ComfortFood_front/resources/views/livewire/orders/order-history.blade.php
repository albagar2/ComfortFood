<div class="flex flex-col gap-8">
    <!-- Header & Search/Filters -->
    <div class="flex flex-col gap-6">
        <div class="flex items-center gap-4">
            <flux:button icon="arrow-left" variant="ghost" x-on:click="history.back()" />
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ __('Historial de pedidos') }}</h1>
        </div>

        <div class="flex flex-col md:flex-row gap-4 items-center">
            <div class="flex-1 w-full">
                <flux:input 
                    wire:model.live.debounce.500ms="search" 
                    icon="magnifying-glass" 
                    placeholder="{{ __('Buscar pedido por ID, ' . ($isRestaurant ? 'cliente' : 'restaurante') . '...') }}" 
                    class="w-full"
                />
            </div>
            
            <div class="flex gap-4 w-full md:w-auto">
                <flux:select wire:model.live="status" class="min-w-[150px]">
                    <flux:select.option value="">{{ __('Todos los estados') }}</flux:select.option>
                    <flux:select.option value="En espera">{{ __('En espera') }}</flux:select.option>
                    <flux:select.option value="En preparación">{{ __('En preparación') }}</flux:select.option>
                    <flux:select.option value="Completado">{{ __('Completado') }}</flux:select.option>
                    <flux:select.option value="Cancelado">{{ __('Cancelado') }}</flux:select.option>
                </flux:select>

                <flux:input type="date" wire:model.live="date" class="min-w-[180px]" />
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-zinc-900 rounded-3xl shadow-sm border border-zinc-200 dark:border-zinc-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-zinc-600 dark:text-zinc-400">
                <thead class="text-[11px] text-zinc-700 uppercase bg-zinc-50/50 dark:bg-zinc-800/50 border-b border-zinc-100 dark:border-zinc-800 font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-4">{{ __('Pedido') }}</th>
                        <th class="px-6 py-4">{{ __('Fecha/Hora') }}</th>
                        <th class="px-6 py-4">{{ $isRestaurant ? __('Cliente') : __('Restaurante') }}</th>
                        <th class="px-6 py-4">{{ __('Total') }}</th>
                        <th class="px-6 py-4">{{ __('Estado') }}</th>
                        <th class="px-6 py-4">{{ __('Dirección') }}</th>
                        <th class="px-6 py-4 text-center">{{ __('Acción') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    @forelse($orders as $pedido)
                        <tr class="group hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                            <td class="px-6 py-5">
                                <span class="font-bold text-blue-600 dark:text-blue-400">#{{ $pedido->id_pedido }}</span>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex flex-col">
                                    <span class="font-semibold text-zinc-950 dark:text-zinc-100">{{ $pedido->created_at->format('M d, Y') }}</span>
                                    <span class="text-[11px] text-zinc-600">{{ $pedido->created_at->format('h:i A') }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    @php
                                        $displayUser = $isRestaurant ? $pedido->cliente->user : $pedido->restaurante->user;
                                    @endphp
                                    <flux:avatar size="sm" :src="$displayUser->profile_photo_url" :name="$displayUser->nombre_completo" />
                                    <span class="font-semibold text-zinc-950 dark:text-zinc-100">{{ $displayUser->nombre_completo }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-5 font-bold text-zinc-950 dark:text-zinc-100">
                                {{ number_format($pedido->precio_total, 2) }}€
                            </td>
                            <td class="px-6 py-5">
                                @php
                                    $statusColor = match($pedido->estado->nombre_estado) {
                                        'En espera' => 'bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300',
                                        'En preparación' => 'bg-cyan-100 dark:bg-cyan-900/40 text-cyan-700 dark:text-cyan-300',
                                        'Completado' => 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300',
                                        'Cancelado' => 'bg-rose-100 dark:bg-rose-900/40 text-rose-700 dark:text-rose-300',
                                        default => 'bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400'
                                    };
                                @endphp
                                <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-tight {{ $statusColor }}">
                                    {{ $pedido->estado->nombre_estado }}
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <span class="text-zinc-700 dark:text-zinc-400 truncate max-w-[200px] block">
                                    {{ $pedido->direccion_entrega }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <flux:button variant="ghost" size="sm" icon="eye" href="{{ route('orders.details', $pedido->id_pedido) }}" wire:navigate class="text-zinc-400 hover:text-blue-600" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-zinc-500 dark:text-zinc-400">
                                <div class="flex flex-col items-center gap-2">
                                    <flux:icon.clipboard-document-list class="size-8 opacity-20" />
                                    <p>{{ __('No se encontraron pedidos.') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->count() > 0)
            <div class="px-6 py-4 border-t border-zinc-100 dark:border-zinc-800">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
