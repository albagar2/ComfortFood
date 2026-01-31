<div class="flex flex-col gap-8">
    <!-- Header & Search/Filters -->
    <div class="flex flex-col gap-6">
        <div class="flex items-center gap-4">
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
                                    <span class="font-semibold text-zinc-950 dark:text-zinc-100">{{ $pedido->created_at->format('d/m/Y') }}</span>
                                    <span class="text-[11px] text-zinc-600">{{ $pedido->created_at->format('H:i') }}</span>
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
                            <td class="px-6 py-5 text-center flex items-center justify-center gap-2">
                                @if($pedido->estado->nombre_estado === 'Pendiente')
                                    <flux:button variant="ghost" size="sm" icon="x-mark" wire:click="confirmCancel({{ $pedido->id_pedido }})" class="text-rose-400 hover:text-rose-600" />
                                @endif
                                
                                <flux:button variant="ghost" size="sm" icon="eye" href="{{ route('orders.details', $pedido->id_pedido) }}" wire:navigate class="text-zinc-400 hover:text-blue-600" />

                                @if($pedido->estado->nombre_estado === 'Completado' && !$isRestaurant)
                                    @if($pedido->resena)
                                        <div class="flex items-center gap-0.5 text-yellow-500 bg-yellow-50 dark:bg-yellow-900/20 px-2 py-1 rounded-lg">
                                            <flux:icon.star variant="solid" class="size-3" />
                                            <span class="text-xs font-bold">{{ $pedido->resena->puntuacion }}</span>
                                        </div>
                                    @else
                                        <flux:button variant="filled" size="sm" wire:click="openReviewModal({{ $pedido->id_pedido }})" class="!bg-pastel-orange !text-white !rounded-xl text-[10px] font-bold py-1">
                                            Valorar
                                        </flux:button>
                                    @endif
                                @endif
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

    <!-- Review Modal -->
    <flux:modal wire:model="showReviewModal" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Valorar pedido #{{ $selectedOrderId }}</flux:heading>
                <flux:subheading>Tu opinión nos ayuda a mejorar. Es opcional pero muy recomendada.</flux:subheading>
            </div>

            <div class="space-y-4">
                <!-- Stars -->
                <div class="flex flex-col items-center gap-2">
                    <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Puntuación</span>
                    <div class="flex gap-1">
                        @for($i = 1; $i <= 5; $i++)
                            <button wire:click="$set('rating', {{ $i }})" class="focus:outline-none transition-transform active:scale-90">
                                <flux:icon.star 
                                    variant="{{ $rating >= $i ? 'solid' : 'outline' }}" 
                                    class="size-8 {{ $rating >= $i ? 'text-yellow-400' : 'text-zinc-300' }}" 
                                />
                            </button>
                        @endfor
                    </div>
                </div>

                <flux:textarea 
                    wire:model="comment" 
                    label="Comentario (opcional)" 
                    placeholder="Cuéntanos qué te pareció el pedido..."
                    rows="4"
                />
            </div>

            <div class="flex gap-2 justify-end">
                <flux:button variant="ghost" @click="$wire.showReviewModal = false">Cancelar</flux:button>
                <flux:button variant="filled" wire:click="saveReview" class="!bg-pastel-orange !text-white">Enviar Valoración</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
