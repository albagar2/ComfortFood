<div class="p-6">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Gestión de Menús</h1>
        @if($menus->isNotEmpty())
            <flux:button variant="primary" icon="plus" href="{{ route('menu.edit') }}" wire:navigate>Añadir Menú
            </flux:button>
        @endif
    </div>

    @if (session()->has('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-200 text-green-700 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-6 p-4 bg-red-100 border border-red-200 text-red-700 rounded-xl">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-6">
        @forelse($menus as $menu)
            @php
                $hasActiveOrders = $menu->hasActiveOrders();
            @endphp
            <div
                class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl p-4 shadow-sm flex flex-col gap-4 relative">
                <!-- Status Badge -->
                <div class="absolute top-4 right-4">
                    @if($menu->esta_activo)
                        <span
                            class="inline-flex items-center gap-1 bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full font-medium">
                            Activo
                            <flux:icon.check class="size-3" />
                        </span>
                    @else
                        <span
                            class="inline-flex items-center gap-1 bg-red-100 text-red-700 text-xs px-2 py-1 rounded-full font-medium">
                            No disponible <flux:icon.x-mark class="size-3" />
                        </span>
                    @endif
                </div>
                <!-- Header -->
                <div class="mt-2">
                    <span class="text-xs font-bold text-zinc-500 uppercase tracking-wider">Menú</span>
                </div>

                <!-- Image -->
                <a href="{{ route('menu.show', $menu->id_menu) }}" wire:navigate
                    class="aspect-square bg-zinc-100 dark:bg-zinc-800 rounded-lg flex items-center justify-center overflow-hidden cursor-pointer hover:opacity-90 transition-opacity">
                    @if($menu->url_foto)
                        <img src="{{ $menu->url_foto }}" alt="{{ $menu->nombre_menu }}" class="w-full h-full object-cover">
                    @else
                        <flux:icon.photo class="size-16 text-zinc-300" />
                    @endif
                </a>

                <!-- Details -->
                <div class="flex-1">
                    <a href="{{ route('menu.show', $menu->id_menu) }}" wire:navigate
                        class="font-bold text-lg text-zinc-900 dark:text-white mb-1 hover:text-blue-600 hover:underline">{{ $menu->nombre_menu }}</a>
                    <p class="text-xs text-zinc-500 line-clamp-2 mb-1">{{ $menu->descripcion_menu }}</p>
                    <p class="text-xs text-zinc-400 line-clamp-1">Propiedades: {{ $menu->propiedades_nutricionales }}</p>
                </div>

                <!-- Footer / Actions -->
                <div class="flex items-center justify-between pt-4 border-t border-zinc-100 dark:border-zinc-800">
                    <div class="flex flex-col">
                        <span
                            class="font-bold text-xs md:text-base text-zinc-900 dark:text-white">{{ number_format($menu->precio, 2) }}€</span>
                        <span class="text-[10px] md:text-xs text-zinc-500">
                            Stock: {{ $menu->stock }}
                        </span>
                    </div>

                    <div class="flex gap-2">
                        @if($hasActiveOrders)
                            <button disabled
                                title="No puedes eliminar este menú mientras haya pedidos en curso. Espera a que todos los pedidos asociados estén completados o cancelados."
                                class="size-9 flex items-center justify-center rounded-lg border border-zinc-200 text-zinc-300 cursor-not-allowed opacity-50 bg-zinc-50">
                                <flux:icon.trash class="size-4" />
                            </button>
                        @else
                            <button wire:click="deleteMenu({{ $menu->id_menu }})"
                                wire:confirm="¿Estás seguro de que quieres eliminar este menú?" title="Eliminar menú"
                                class="size-9 flex items-center justify-center rounded-lg border border-red-200 text-red-500 hover:bg-red-50 transition-colors">
                                <flux:icon.trash class="size-4" />
                            </button>
                        @endif

                        <a href="{{ route('menu.edit', ['menu' => $menu->id_menu]) }}" wire:navigate title="Editar menú"
                            class="size-9 flex items-center justify-center rounded-lg border border-yellow-200 text-yellow-500 hover:bg-yellow-50 transition-colors">
                            <flux:icon.pencil class="size-4" />
                        </a>

                        <button wire:click="toggleStatus({{ $menu->id_menu }})" title="Cambiar estado"
                            class="size-9 flex items-center justify-center rounded-lg border border-zinc-200 text-zinc-500 hover:bg-zinc-50 transition-colors">
                            <flux:icon.arrow-path class="size-4" />
                            <!-- Using arrow-path as toggle/switch icon alternative, or eye -->
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div
                class="col-span-full py-20 flex flex-col items-center justify-center text-center bg-white dark:bg-zinc-900 border border-dashed border-zinc-300 dark:border-zinc-700 rounded-2xl">
                <div
                    class="size-20 bg-blue-50 dark:bg-blue-900/20 rounded-full flex items-center justify-center mb-6 text-blue-600 dark:text-blue-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-10">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H22m-12.939-9.21a.502.502 0 0 1 0-.74l4.94-4.94a.502.502 0 0 1 .74 0l4.94 4.94a.502.502 0 0 1 0 .74l-4.94 4.94a.502.502 0 0 1-.74 0l-4.94-4.94ZM2.36 21a2.36 2.36 0 0 1-2.36-2.36V7.48C0 6.132 1.1 5.03 2.44 5.03h1.16a2.44 2.44 0 0 1 2.44 2.44v11.171c0 1.303-1.057 2.36-2.36 2.36Zm19.28 0a2.36 2.36 0 0 0 2.36-2.36V7.48c0-1.348-1.1-2.45-2.44-2.45h-1.16a2.44 2.44 0 0 0-2.44 2.44v11.171c0 1.303 1.057 2.36 2.36 2.36Z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-zinc-900 dark:text-white mb-2">No tienes menús registrados todavía</h3>
                <p class="text-zinc-500 dark:text-zinc-400 max-w-sm mb-8">
                    Crea tu primer menú para que los clientes puedan empezar a descubrir tus platos y realizar pedidos.
                </p>
                <flux:button variant="primary" icon="plus" href="{{ route('menu.edit') }}" wire:navigate>
                    Añadir mi primer menú
                </flux:button>
            </div>
        @endforelse
    </div>
</div>