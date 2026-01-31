<div class="p-6">
    <!-- Header with Back Arrow and Buttons -->
    <div class="flex items-center justify-between mb-6">
        <a href="javascript:history.back()" class="text-zinc-900 hover:text-zinc-600">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-8">
              <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
        </a>

        @if(auth()->check() && auth()->user()->isRestaurante() && auth()->user()->id_usuario == $menu->restaurante->id_usuario)
        <div class="flex gap-4">
             <a href="{{ route('menu.edit', $menu->id_menu) }}" wire:navigate class="px-6 py-2.5 text-sm font-bold text-white bg-orange-400 border border-orange-400 rounded-lg hover:bg-orange-500 transition-colors uppercase shadow-sm">
                EDITAR MENÚ
             </a>
        </div>
        @endif
    </div>

    <!-- Main Card -->
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-12 max-w-4xl mx-auto shadow-sm">
        <h2 class="text-center text-xl font-bold mb-10 text-zinc-900 dark:text-white">
            {{ $menu->nombre_menu }}
        </h2>

        <div class="flex flex-col md:flex-row gap-12">
            <!-- Left Column: Details -->
            <div class="flex-1 space-y-6">
                <!-- Dishes List -->
                <div class="space-y-4">
                     <!-- Plato Principal -->
                     <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 font-bold text-xs">1</span>
                        <div class="flex-1 px-4 py-3 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-full text-sm text-zinc-700 dark:text-zinc-300">
                            {{ $menu->plato_principal ?? 'Plato principal no especificado' }}
                        </div>
                     </div>
                     
                     <!-- Segundo Plato -->
                     <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 font-bold text-xs">2</span>
                        <div class="flex-1 px-4 py-3 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-full text-sm text-zinc-700 dark:text-zinc-300">
                             {{ $menu->segundo_plato ?? 'Segundo plato no especificado' }}
                        </div>
                     </div>

                     <!-- Postre -->
                     <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 font-bold text-xs">3</span>
                        <div class="flex-1 px-4 py-3 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-full text-sm text-zinc-700 dark:text-zinc-300">
                             {{ $menu->postre ?? 'Postre no especificado' }}
                        </div>
                     </div>

                     <!-- Bebida -->
                     <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 font-bold text-xs">4</span>
                        <div class="flex-1 px-4 py-3 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-full text-sm text-zinc-700 dark:text-zinc-300">
                             {{ $menu->bebida ?? 'Bebida no especificada' }}
                        </div>
                     </div>
                </div>
                
                <div class="space-y-2">
                    <label class="text-sm font-bold text-zinc-900 dark:text-white ml-1">Descripción:</label>
                    <div class="w-full px-6 py-4 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-2xl text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                        {{ $menu->descripcion_menu ?: 'Sin descripción.' }}
                    </div>
                </div>
                
                <div class="space-y-2">
                    <label class="text-sm font-bold text-zinc-900 dark:text-white ml-1">Propiedades Nutricionales:</label>
                     <div class="w-full px-6 py-4 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-2xl text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                        {{ $menu->propiedades_nutricionales ?: 'Sin información nutricional.' }}
                    </div>
                </div>

                <div class="flex items-center justify-between pt-4">
                    <span class="text-lg font-bold text-zinc-900 dark:text-white">Precio</span>
                    <div class="flex items-center gap-1">
                        <span class="text-2xl font-bold text-zinc-900 dark:text-white">{{ number_format($menu->precio, 2) }}</span>
                        <span class="text-xl font-bold text-zinc-900 dark:text-white">€</span>
                    </div>
                </div>
            </div>

            <!-- Right Column: Image -->
            <div class="w-full md:w-48 lg:w-56 flex flex-col items-center">
                <div class="w-48 h-48 bg-yellow-400 border-4 border-yellow-500/50 rounded-lg mb-2 flex items-center justify-center overflow-hidden shadow-inner relative group rotate-1 hover:rotate-0 transition-transform duration-300">
                    
                    <!-- Frame Effect -->
                    <div class="absolute inset-0 border-[6px] border-yellow-600/20 z-10 pointer-events-none"></div>

                    @if($menu->url_foto)
                         <img src="{{ $menu->url_foto }}" class="w-full h-full object-cover">
                    @else
                         <!-- Placeholder Landscape Icon logic -->
                         <div class="text-white text-opacity-80">
                            <svg class="size-20" fill="currentColor" viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
                         </div>
                    @endif
                </div>
                
                @if(auth()->check() && auth()->user()->isRestaurante() && auth()->user()->id_usuario == $menu->restaurante->id_usuario)
                    <span class="text-xs text-zinc-500 font-medium mt-2">Vista previa de imagen</span>
                @else
                    <button wire:click="addToCart({{ $menu->id_menu }})" 
                        @if($menu->stock <= 0) disabled @endif
                        class="mt-6 w-full py-3 {{ $menu->stock > 0 ? 'bg-zinc-900 hover:bg-zinc-800' : 'bg-zinc-300 cursor-not-allowed' }} text-white font-bold rounded-xl shadow-lg shadow-zinc-200 transition-all active:scale-95 disabled:opacity-50">
                        {{ $menu->stock > 0 ? 'Añadir al carrito' : 'Agotado' }}
                    </button>
                    <!-- Restaurant Name Link -->
                    <div class="mt-4 text-center">
                        <a href="{{ route('restaurant.show', $menu->restaurante->id_restaurante) }}" wire:navigate class="text-sm font-medium text-zinc-500 hover:text-blue-600 hover:underline">
                            {{ $menu->restaurante->user->nombre_completo }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
