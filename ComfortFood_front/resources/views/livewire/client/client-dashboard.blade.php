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

    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8">
        <h1 class="text-xl md:text-2xl font-bold text-zinc-900 dark:text-white">Explorar Menús</h1>
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
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 2xl:grid-cols-5 gap-4 md:gap-6">
            @foreach($menus as $menu)
                @php
                    $isDeactivated = in_array($menu->id_menu, $deactivatedIds);
                    $isOpen = $menu->restaurante->isOpen();
                    $cardClasses = '';

                    if ($isDeactivated) {
                        $cardClasses = 'opacity-40 scale-95 grayscale-[50%]';
                    } elseif (!$isOpen) {
                        $cardClasses = 'opacity-60 grayscale bg-zinc-50 dark:bg-zinc-900/50';
                    }
                @endphp

                @if($menu->stock <= 0 || !$isOpen)
                    @continue
                @endif
                <div
                    class="dark:bg-zinc-900 bg-white border-2 border-zinc-200 dark:border-zinc-800 rounded-xl p-3 md:p-4 shadow-sm flex flex-col gap-3 md:gap-4 relative group hover:border-zinc-300 dark:hover:border-zinc-700 transition-all duration-500 {{ $cardClasses }}">
                    <div class="flex flex-col sm:flex-row justify-between items-start gap-2">
                        <div class="min-w-0">
                            <div class="flex items-center gap-2">
                                <flux:avatar :src="$menu->restaurante->user->profile_photo_url"
                                    :name="$menu->restaurante->user->nombre_completo" size="xs" />
                                <a href="{{ route('restaurant.show', $menu->restaurante->id_restaurante) }}" wire:navigate
                                    title="Ver restaurante"
                                    class="text-[10px] md:text-sm font-semibold text-zinc-800 dark:text-zinc-200 hover:underline hover:text-blue-600 truncate block max-w-[80px] sm:max-w-none">
                                    {{ $menu->restaurante->user->nombre_completo ?? 'Restaurante' }}
                                </a>
                                @if($menu->restaurante->resenas_avg_puntuacion)
                                    <div class="flex items-center gap-0.5 text-yellow-400 ml-1">
                                        <flux:icon.star variant="solid" class="size-3" />
                                        <span
                                            class="text-[10px] font-bold">{{ number_format($menu->restaurante->resenas_avg_puntuacion, 1) }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-1 md:gap-2 absolute top-3 right-3 sm:static">
                            <button wire:click="toggleFavorite({{ $menu->id_menu }})"
                                class="{{ $menu->favoritos->count() > 0 ? 'text-red-500' : 'text-zinc-400' }} hover:text-red-600 transition-colors">
                                <flux:icon.heart variant="{{ $menu->favoritos->count() > 0 ? 'solid' : 'outline' }}"
                                    class="size-4 md:size-5" />
                            </button>
                            @if(!$isDeactivated)
                                <button wire:click="moveCardToBottom({{ $menu->id_menu }})" title="{{ __('Mover al final') }}"
                                    class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 transition-colors">
                                    <flux:icon.x-mark class="size-4 md:size-5" />
                                </button>
                            @else
                                <button wire:click="enableCard({{ $menu->id_menu }})" title="{{ __('Rehabilitar') }}"
                                    class="text-teal-500 hover:text-teal-700 transition-colors">
                                    <flux:icon.arrow-up-circle class="size-4 md:size-5" />
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Image -->
                    <a href="{{ $isOpen ? route('menu.show', $menu->id_menu) : route('restaurant.show', $menu->restaurante->id_restaurante) }}"
                        wire:navigate title="{{ $isOpen ? 'Ver menú' : 'Restaurante Cerrado' }}"
                        class="aspect-square bg-zinc-100 dark:bg-zinc-800 rounded-lg flex items-center justify-center overflow-hidden cursor-pointer hover:opacity-90 transition-opacity mt-2 sm:mt-0">
                        @if($menu->url_foto)
                            <img src="{{ $menu->url_foto }}" alt="{{ $menu->nombre_menu }}" class="w-full h-full object-cover">
                        @else
                            <flux:icon.photo class="size-10 md:size-16 text-zinc-300" />
                        @endif
                    </a>

                    <!-- Details -->
                    <div class="flex-1 min-w-0">
                        <a href="{{ $isOpen ? route('menu.show', $menu->id_menu) : route('restaurant.show', $menu->restaurante->id_restaurante) }}"
                            wire:navigate
                            class="block group-hover:text-zinc-600 dark:group-hover:text-zinc-300 transition-colors">
                            <h3 class="font-bold text-sm md:text-lg text-zinc-900 dark:text-white mb-1 truncate">
                                {{ $menu->nombre_menu }}
                            </h3>
                        </a>
                        <p class="text-[10px] md:text-xs text-zinc-500 line-clamp-2 mb-1 leading-tight">
                            {{ $menu->descripcion_menu }}
                        </p>
                    </div>

                    <!-- Footer -->
                    <div class="flex items-center justify-between pt-2 md:pt-4 border-t border-zinc-100 dark:border-zinc-800">
                        <div class="flex flex-col">
                            <span
                                class="font-bold text-xs md:text-base text-zinc-900 dark:text-white">{{ number_format($menu->precio, 2) }}€</span>
                            <span class="text-[10px] md:text-xs text-zinc-500">
                                Stock: {{ $menu->stock }}
                            </span>
                        </div>

                        @if($isOpen)
                            <button wire:click="addToCart({{ $menu->id_menu }})" @if($menu->stock <= 0) disabled @endif
                                class="px-3 py-1.5 md:px-4 md:py-2 flex items-center gap-1 md:gap-2 rounded-lg {{ $menu->stock > 0 ? 'bg-pastel-orange text-white hover:bg-orange-600' : 'bg-zinc-300 text-zinc-500 cursor-not-allowed' }} transition-colors text-xs md:text-sm font-semibold">
                                <flux:icon.shopping-cart class="size-3 md:size-4" />
                                <span class="hidden sm:inline">Añadir</span>
                            </button>
                        @else
                            <a href="{{ route('restaurant.show', $menu->restaurante->id_restaurante) }}" wire:navigate
                                class="px-3 py-1.5 md:px-4 md:py-2 flex items-center gap-1 text-zinc-500 bg-zinc-100 dark:bg-zinc-800 rounded-lg text-xs md:text-sm font-semibold cursor-pointer hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors">
                                <flux:icon.eye class="size-3 md:size-4" />
                                <span class="hidden sm:inline">Ver Perfil</span>
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>