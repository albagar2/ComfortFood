<div class="p-6">
    <!-- Header with Back Arrow and Buttons -->
    <div class="flex items-center justify-between mb-6">
        <a href="javascript:history.back()" class="text-zinc-900 hover:text-zinc-600">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="size-8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
        </a>
    </div>

    <!-- Main Card -->
    <div
        class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 p-5 md:p-12 w-full max-w-4xl mx-auto shadow-sm">
        <h2 class="text-center text-xl font-bold mb-10 text-zinc-900 dark:text-white">
            {{ $menu && $menu->exists ? 'Editar menú' : 'Añadir menú' }}
        </h2>

        <form wire:submit="save">
            <div class="flex flex-col md:flex-row gap-12">
                <!-- Left Column: Inputs -->
                <div class="flex-1 space-y-5">
                    <div>
                        <input type="text" wire:model="nombre_menu" placeholder="Añadir nombre menú"
                            class="w-full px-4 py-2.5 border border-zinc-200 rounded-full text-sm placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-300" />
                        @error('nombre_menu') <span class="text-sm text-red-500 mt-1 ml-2">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <input type="text" wire:model="plato_principal" placeholder="Añadir plato principal"
                            class="w-full px-4 py-2.5 border border-zinc-200 rounded-full text-sm placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-300" />
                        @error('plato_principal') <span class="text-sm text-red-500 mt-1 ml-2">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <input type="text" wire:model="segundo_plato" placeholder="Añadir segundo plato"
                            class="w-full px-4 py-2.5 border border-zinc-200 rounded-full text-sm placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-300" />
                        @error('segundo_plato') <span class="text-sm text-red-500 mt-1 ml-2">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <input type="text" wire:model="postre" placeholder="Añadir postre"
                            class="w-full px-4 py-2.5 border border-zinc-200 rounded-full text-sm placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-300" />
                    </div>
                    <div>
                        <input type="text" wire:model="bebida" placeholder="Añadir bebida"
                            class="w-full px-4 py-2.5 border border-zinc-200 rounded-full text-sm placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-300" />
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-medium text-zinc-700 ml-1">Añadir Descripción:</label>
                        <textarea wire:model="descripcion_menu" rows="4"
                            class="w-full px-4 py-3 border border-zinc-300 rounded-2xl text-sm placeholder-zinc-300 resize-none focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-300"></textarea>
                        @error('descripcion_menu')
                            <span class="text-sm text-red-500 mt-1 ml-2">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-medium text-zinc-700 ml-1">Añadir Propiedades Nutricionales:</label>
                        <textarea wire:model="propiedades_nutricionales" rows="4"
                            class="w-full px-4 py-3 border border-zinc-300 rounded-2xl text-sm placeholder-zinc-300 resize-none focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-300"></textarea>
                    </div>

                    <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between pt-2 gap-4">
                        <div
                            class="flex-1 flex justify-between items-center bg-zinc-50 dark:bg-zinc-800 px-4 py-2 rounded-xl border border-zinc-200">
                            <span class="text-sm font-bold text-zinc-700 dark:text-zinc-300">Stock</span>
                            <input type="number" wire:model="stock" placeholder="0" min="0"
                                class="w-20 text-right font-medium text-zinc-700 dark:text-white border-none bg-transparent focus:ring-0 p-0" />
                        </div>

                        <div
                            class="flex-1 flex justify-between items-center bg-zinc-50 dark:bg-zinc-800 px-4 py-2 rounded-xl border border-zinc-200">
                            <span class="text-sm font-bold text-zinc-700 dark:text-zinc-300">Precio</span>
                            <div class="flex items-center gap-1">
                                <input type="text" wire:model="precio" placeholder="0.00"
                                    class="w-20 text-right font-medium text-zinc-700 dark:text-white border-none bg-transparent focus:ring-0 p-0" />
                                <span class="text-zinc-700 dark:text-white font-bold">€</span>
                            </div>
                        </div>
                    </div>
                    @error('stock') <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                    @error('precio') <span class="text-sm text-red-500 mt-1 text-right block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Right Column: Image Upload -->
                <div class="w-full md:w-48 lg:w-56 flex flex-col items-center" x-data="{
                        async handleFileSelect(event) {
                            const file = event.target.files[0];
                            if (!file) return;

                            try {
                                const versions = await ImageOptimizer.processMenu(file);
                                
                                // Upload both versions to Livewire
                                @this.upload('foto', versions.original);
                                @this.upload('foto_card', versions.card);
                            } catch (error) {
                                console.error('Error processing image:', error);
                                // Fallback: try to upload the original file if compression fails
                                @this.upload('foto', file);
                            }
                        }
                    }">
                    <label
                        class="w-28 h-28 bg-yellow-400 border-4 border-yellow-500/50 rounded-lg mb-2 flex items-center justify-center cursor-pointer overflow-hidden shadow-inner relative group">
                        <input type="file" x-on:change="handleFileSelect" class="hidden" accept="image/*">

                        <!-- Frame Effect -->
                        <div class="absolute inset-0 border-[6px] border-yellow-600/20 z-10 pointer-events-none"></div>

                        @if ($foto)
                            <img src="{{ $foto->temporaryUrl() }}" class="w-full h-full object-cover">
                        @elseif($current_foto)
                            <img src="{{ $current_foto }}" class="w-full h-full object-cover">
                        @else
                            <!-- Placeholder Landscape Icon logic -->
                            <div class="text-white text-opacity-80">
                                <svg class="size-16" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z" />
                                </svg>
                            </div>
                        @endif

                        <!-- Loading -->
                        <div wire:loading wire:target="foto"
                            class="absolute inset-0 bg-black/20 flex items-center justify-center z-20">
                            <flux:icon.arrow-path class="size-6 animate-spin text-white" />
                        </div>
                    </label>
                    <span class="text-xs text-zinc-900 font-medium">Añadir Imagen</span>
                    @error('foto') <span class="text-sm text-red-500 mt-1 text-center">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-6 mt-12 pr-4">
                <!--Cancel -->
                <button type="button" wire:click="$dispatch('cancel')" onclick="history.back()"
                    class="size-12 flex items-center justify-center bg-white border-2 border-red-400 rounded-lg hover:bg-red-50 transition-colors shadow-sm group">
                    <svg class="size-6 text-zinc-400 group-hover:text-red-500" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Save -->
                <button type="submit"
                    class="size-12 flex items-center justify-center bg-white border-2 border-green-200/50 rounded-lg hover:bg-green-50 transition-colors shadow-sm group">
                    <!-- Floppy Disk Icon -->
                    <svg class="size-6 text-zinc-500 group-hover:text-green-600" fill="currentColor"
                        viewBox="0 0 24 24">
                        <path
                            d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z" />
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>