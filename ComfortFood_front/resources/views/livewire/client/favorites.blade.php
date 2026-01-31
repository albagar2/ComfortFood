<div class="p-6">
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8">
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Mis Favoritos</h1>
    </div>

    <!-- Empty State -->
    @if($menus->isEmpty())
        <div
            class="text-center py-12 bg-white dark:bg-zinc-900 rounded-xl border border-dashed border-zinc-200 dark:border-zinc-800">
            <flux:icon.heart class="size-12 text-zinc-300 mx-auto mb-4" />
            <h3 class="text-lg font-bold text-zinc-900 dark:text-white">Aún no tienes favoritos</h3>
            <p class="text-zinc-500">Explora menús y dales al corazón para guardarlos aquí.</p>
            <flux:button href="{{ route('dashboard') }}" wire:navigate variant="primary" class="mt-6">Explorar Menús
            </flux:button>
        </div>
    @else
        <div class="grid grid-cols-3  2xl:grid-cols-4 gap-6">
            @foreach($menus as $menu)
                <div
                    class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl p-4 shadow-sm flex flex-col gap-4 relative group hover:border-zinc-300 dark:hover:border-zinc-700 transition-colors">
                    <div class="flex justify-between items-start">
                        <div>
                            <a href="{{ route('restaurant.show', $menu->restaurante->id_restaurante) }}" wire:navigate
                                title="Ver restaurante"
                                class="text-sm font-semibold text-zinc-800 dark:text-zinc-200 hover:underline hover:text-blue-600">
                                {{ $menu->restaurante->user->nombre_completo ?? 'Restaurante' }}
                            </a>
                        </div>
                        <button wire:click="toggleFavorite({{ $menu->id_menu }})"
                            class="text-red-500 hover:text-red-700 transition-colors">
                            <flux:icon.heart variant="solid" class="size-5" />
                        </button>
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
                        <p class="text-sm font-bold text-zinc-900 dark:text-white">{{ number_format($menu->precio, 2) }} €</p>
                    </div>

                    <!-- Footer -->
                    <div class="flex items-center justify-between pt-4 border-t border-zinc-100 dark:border-zinc-800">
                        <a href="{{ route('menu.show', $menu->id_menu) }}" class="w-full block">
                            <flux:button size="sm" variant="primary" class="w-full cursor-pointer">
                                Ver detalles
                            </flux:button>
                        </a>

                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>