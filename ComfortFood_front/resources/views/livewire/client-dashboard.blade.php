<div class="p-6">
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8">
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Explorar Menús</h1>
        <div class="w-full md:w-96">
            <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass"
                placeholder="Buscar por menú o restaurante" class="w-full" />
        </div>
    </div>

    <!-- Empty State -->
    @if($menus->isEmpty())
        <div class="text-center py-12">
            <flux:icon.magnifying-glass class="size-12 text-zinc-300 mx-auto mb-4" />
            <h3 class="text-lg font-bold text-zinc-900 dark:text-white">No se encontraron menús</h3>
            <p class="text-zinc-500">Intenta buscar con otros términos.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($menus as $menu)
                @php
                    $isDeactivated = in_array($menu->id_menu, $deactivatedIds);
                @endphp
                <div
                    class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl p-4 shadow-sm flex flex-col gap-4 relative group hover:border-zinc-300 dark:hover:border-zinc-700 transition-all duration-500 {{ $isDeactivated ? 'opacity-40 scale-95 grayscale-[50%]' : '' }}">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="flex items-center gap-2">
                                <flux:avatar :src="$menu->restaurante->user->profile_photo_url"
                                    :name="$menu->restaurante->user->nombre_completo" size="xs" />
                                <a href="{{ route('restaurant.show', $menu->restaurante->id_restaurante) }}" wire:navigate
                                    title="Ver restaurante"
                                    class="text-sm font-semibold text-zinc-800 dark:text-zinc-200 hover:underline hover:text-blue-600">
                                    {{ $menu->restaurante->user->nombre_completo ?? 'Restaurante' }}
                                </a>
                            </div>
                            <span class="text-xs text-zinc-400 block">{{ $menu->updated_at->format('d M Y, h:i A') }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <button wire:click="toggleFavorite({{ $menu->id_menu }})"
                                class="{{ $menu->favoritos->count() > 0 ? 'text-red-500' : 'text-zinc-400' }} hover:text-red-600 transition-colors">
                                <flux:icon.heart variant="{{ $menu->favoritos->count() > 0 ? 'solid' : 'outline' }}"
                                    class="size-5" />
                            </button>
                            @if(!$isDeactivated)
                                <button wire:click="moveCardToBottom({{ $menu->id_menu }})" title="{{ __('Mover al final') }}"
                                    class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 transition-colors">
                                    <flux:icon.x-mark class="size-5" />
                                </button>
                            @else
                                <button wire:click="enableCard({{ $menu->id_menu }})" title="{{ __('Rehabilitar') }}"
                                    class="text-teal-500 hover:text-teal-700 transition-colors">
                                    <flux:icon.arrow-up-circle class="size-5" />
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Image -->
                    <a href="{{ route('menu.show', $menu->id_menu) }}" wire:navigate title="Ver menú"
                        class="aspect-square bg-zinc-100 dark:bg-zinc-800 rounded-lg flex items-center justify-center overflow-hidden cursor-pointer hover:opacity-90 transition-opacity">
                        @if($menu->url_foto)
                            <img src="{{ $menu->url_foto }}" alt="{{ $menu->nombre_menu }}" class="w-full h-full object-cover">
                        @else
                            <flux:icon.photo class="size-16 text-zinc-300" />
                        @endif
                    </a>

                    <!-- Details -->
                    <div class="flex-1">
                        <h3 class="font-bold text-lg text-zinc-900 dark:text-white mb-1">{{ $menu->nombre_menu }}</h3>
                        <p class="text-xs text-zinc-500 line-clamp-2 mb-1">{{ $menu->descripcion_menu }}</p>
                        <p class="text-xs text-zinc-400 line-clamp-1">Propiedades: {{ $menu->propiedades_nutricionales }}</p>
                    </div>

                    <!-- Footer -->
                    <div class="flex items-center justify-between pt-4 border-t border-zinc-100 dark:border-zinc-800">
                        <span class="font-bold text-zinc-900 dark:text-white">{{ number_format($menu->precio, 2) }}€</span>

                        <div class="flex gap-2">
                            <button
                                class="size-9 flex items-center justify-center rounded-lg border border-green-200 text-green-500 hover:bg-green-50 transition-colors">
                                <flux:icon.check class="size-4" />
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>