<div class="min-h-screen px-4 py-8 md:px-8">
    <div class="max-w-7xl mx-auto space-y-12">
        <!-- Header / Navigation -->
        <div class="flex items-center justify-between">
            <a href="javascript:history.back()"
                class="p-2 hover:bg-zinc-100 dark:hover:bg-zinc-900 rounded-full transition-colors text-zinc-600 dark:text-zinc-400">
                <flux:icon.arrow-left class="size-6" />
            </a>
            <div class="flex gap-3" title="compartir restaurante">
                <flux:dropdown align="end">
                    <flux:button variant="ghost" icon="share" class="!rounded-xl" />

                    <flux:menu class="min-w-48">
                        <flux:menu.item icon="link" x-data x-on:click="
                                navigator.clipboard.writeText(window.location.href);
                                $flux.toast({
                                    variant: 'success',
                                    heading: 'Enlace copiado',
                                    text: 'El enlace se ha copiado al portapapeles.',
                                });
                            ">
                            Copiar enlace
                        </flux:menu.item>

                        <flux:menu.separator />

                        <flux:menu.item as="a"
                            href="https://wa.me/?text={{ urlencode('¡Echa un vistazo al restaurante ' . $restaurante->user->nombre_completo . ' en ComfortFood! ' . url()->current()) }}"
                            target="_blank" icon="chat-bubble-left-right">
                            WhatsApp
                        </flux:menu.item>

                        <flux:menu.item as="a"
                            href="https://twitter.com/intent/tweet?text={{ urlencode('¡Mira este restaurante en @ComfortFood! ' . url()->current()) }}"
                            target="_blank" icon="hashtag">
                            X (Twitter)
                        </flux:menu.item>

                        <flux:menu.item as="a"
                            href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                            target="_blank" icon="globe-alt">
                            Facebook
                        </flux:menu.item>
                    </flux:menu>
                </flux:dropdown>
            </div>
        </div>

        <!-- Restaurant Profile Hero -->
        <div class="relative">
            <!-- Banner / Cover Placeholder -->
            <div class="h-64 md:h-80 w-full bg-zinc-200 dark:bg-zinc-900 rounded-3xl overflow-hidden relative group">
                @if($restaurante->url_imagen_perfil)
                    <img src="{{ $restaurante->url_imagen_perfil }}"
                        class="w-full h-full object-cover opacity-80 group-hover:scale-105 transition-transform duration-700">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-zinc-100 dark:bg-zinc-900/50">
                        <flux:icon.photo class="size-20 text-zinc-300 dark:text-zinc-700" />
                    </div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-zinc-950/60 to-transparent"></div>

                @if(auth()->check() && auth()->user()->id_usuario === $restaurante->id_usuario)
                    <!-- Edit Image Button -->
                    <div class="absolute top-6 right-6 z-10">
                        <label for="restaurant-photo" class="cursor-pointer group/upload">
                            <div
                                class="flex items-center gap-2 bg-white/10 backdrop-blur-md border border-white/20 hover:bg-white/20 px-4 py-2 rounded-2xl text-white text-sm font-bold transition-all">
                                <flux:icon.camera class="size-4" />
                                <span>Cambiar imagen</span>
                                <div wire:loading wire:target="photo" class="ml-2">
                                    <flux:icon.arrow-path class="size-4 animate-spin" />
                                </div>
                            </div>
                            <input type="file" id="restaurant-photo" wire:model="photo" class="hidden" accept="image/*">
                        </label>
                    </div>
                @endif

                <!-- Floating Info Card -->
                <div
                    class="absolute bottom-8 left-8 right-8 flex flex-col md:flex-row items-end md:items-center justify-between gap-6">
                    <div class="flex items-center gap-6">
                        <!-- Avatar -->
                        <div class="text-white space-y-2">
                            <h1 class="text-3xl md:text-4xl font-black tracking-tight">
                                {{ $restaurante->user->nombre_completo }}
                            </h1>
                            <div class="flex items-center gap-3">
                                <span
                                    class="bg-teal-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">{{ $restaurante->tipo_cocina }}</span>
                                <div class="flex items-center gap-1 text-yellow-400">
                                    <flux:icon.star class="size-4 fill-current" />
                                    <span class="text-sm font-bold text-white">4.8</span>
                                    <span class="text-xs text-white/70 font-medium">(120+)</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="md:flex gap-3">
                        <div class="backdrop-blur-md border px-4 py-2 rounded-2xl text-sm font-medium {{ $this->currentStatus['class'] }}">
                             {{ $this->currentStatus['text'] }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Details Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Info Column -->
            <div class="lg:col-span-2 space-y-8">
                <section class="space-y-4">
                    <h2 class="text-xl font-bold text-zinc-900 dark:text-white">Sobre nosotros</h2>
                    <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed text-lg">
                        {{ $restaurante->descripcion ?? 'Experiencia culinaria única enfocada en productos de proximidad y recetas tradicionales con un toque contemporáneo. Nuestro compromiso es la calidad y el sabor en cada bocado.' }}
                    </p>
                </section>

                <!-- Menus Section -->
                <section class="space-y-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <h2 class="text-2xl font-black text-zinc-900 dark:text-white uppercase tracking-tight">Nuestra
                            Carta</h2>

                        <div class="flex items-center gap-2">
                            <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass"
                                placeholder="Buscar plato..." size="sm" class="w-full sm:w-64" />

                            <flux:dropdown>
                                <flux:button variant="subtle" size="sm" icon="adjustments-horizontal">Filtros
                                </flux:button>

                                <flux:menu class="min-w-48">
                                    <flux:menu.radio.group wire:model.live="sort">
                                        <flux:menu.radio value="latest">Más recientes</flux:menu.radio>
                                        <flux:menu.radio value="price_asc">Precio: Menor a mayor</flux:menu.radio>
                                        <flux:menu.radio value="price_desc">Precio: Mayor a menor</flux:menu.radio>
                                    </flux:menu.radio.group>
                                </flux:menu>
                            </flux:dropdown>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full">
                        @forelse($this->menus as $menu)
                            <div
                                class="group bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-3xl p-5 hover:shadow-2xl hover:shadow-zinc-200/50 dark:hover:shadow-none transition-all duration-500 relative overflow-hidden flex flex-col h-full">
                                <div class="flex gap-5 flex-1">
                                    <!-- Dish Image -->
                                    <a href="{{ route('menu.show', $menu->id_menu) }}" wire:navigate
                                        class="size-24 rounded-2xl overflow-hidden bg-zinc-100 dark:bg-zinc-800 flex-shrink-0 relative">
                                        @if($menu->url_foto)
                                            <img src="{{ $menu->url_foto }}" alt="{{ $menu->nombre_menu }}"
                                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <flux:icon.photo class="size-8 text-zinc-300" />
                                            </div>
                                        @endif
                                        <div class="absolute top-1 right-1">
                                            <span
                                                class="bg-white/90 dark:bg-zinc-950/90 backdrop-blur-sm text-[10px] font-bold px-2 py-1 rounded-lg shadow-sm">{{ number_format($menu->precio, 2) }}€</span>
                                        </div>
                                    </a>

                                    <div class="flex-1 space-y-2 min-w-0 flex flex-col">
                                        <div class="flex justify-between items-start">
                                            <h3
                                                class="font-bold text-zinc-950 dark:text-white group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors truncate pr-2">
                                                {{ $menu->nombre_menu }}
                                            </h3>
                                        </div>
                                        <p
                                            class="text-sm text-zinc-500 dark:text-zinc-400 line-clamp-2 leading-snug flex-1">
                                            {{ $menu->descripcion_menu }}
                                        </p>

                                        <div class="flex items-center gap-2 mt-auto">
                                            <span
                                                class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">{{ $menu->updated_at->format('d M') }}</span>
                                            <span class="size-1 rounded-full bg-zinc-300 dark:bg-zinc-700"></span>
                                            <span
                                                class="text-xs text-zinc-500 dark:text-zinc-400 truncate">{{ Str::limit($menu->propiedades_nutricionales, 20) }}</span>
                                        </div>
                                    </div>
                                </div>

                                @if(auth()->check() && auth()->user()->id_usuario === $restaurante->id_usuario)
                                    <!-- Owner Actions -->
                                    <!-- Owner Actions -->
                                    <div class="mt-4 flex gap-2">
                                        <a href="{{ route('menu.show', $menu->id_menu) }}" wire:navigate class="flex-1 bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-900 dark:text-white py-2 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2">
                                            <flux:icon.eye class="size-4" />
                                            Ver
                                        </a>
                                        <a href="{{ route('menu.edit', $menu->id_menu) }}" wire:navigate class="flex-1 bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-900 dark:text-white py-2 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2">
                                            <flux:icon.pencil-square class="size-4" />
                                            Editar
                                        </a>
                                        <button wire:click="confirmDeleteMenu({{ $menu->id_menu }})" class="flex-1 bg-rose-50 dark:bg-rose-900/20 hover:bg-rose-100 dark:hover:bg-rose-900/40 text-rose-600 dark:text-rose-400 py-2 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2">
                                            <flux:icon.trash class="size-4" />
                                            Eliminar
                                        </button>
                                    </div>
                                @elseif(auth()->check() && auth()->user()->isCliente())
                                    <!-- Client Actions -->
                                    <button wire:click="addToCart({{ $menu->id_menu }})"
                                        @if($menu->stock <= 0) disabled @endif
                                        class="mt-4 w-full {{ $menu->stock > 0 ? 'bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-900 dark:group-hover:bg-white text-zinc-900 dark:text-white group-hover:text-white dark:group-hover:text-zinc-950' : 'bg-zinc-200 text-zinc-400 cursor-not-allowed' }} py-2 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2">
                                        <flux:icon.shopping-cart class="size-4" />
                                        {{ $menu->stock > 0 ? 'Añadir al carrito' : 'Agotado' }}
                                    </button>
                                @endif
                            </div>
                        @empty
                            <div
                                class="col-span-full py-12 text-center bg-white dark:bg-zinc-900 border border-dashed border-zinc-200 dark:border-zinc-800 rounded-3xl">
                                <flux:icon.magnifying-glass class="size-12 text-zinc-300 mx-auto mb-4" />
                                <h3 class="text-lg font-bold text-zinc-900 dark:text-white">No se encontraron platos</h3>
                                <p class="text-zinc-500">Intenta buscar con otros términos.</p>
                            </div>
                        @endforelse
                    </div>
                </section>
            </div>

            <!-- Right Sidebar Column -->
            <div class="space-y-6">
                <!-- Location Card -->
                <div
                    class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-3xl p-6 shadow-sm space-y-6">
                    <div class="flex items-center gap-4">
                        <div
                            class="size-12 bg-teal-50 dark:bg-teal-900/30 rounded-2xl flex items-center justify-center text-teal-600">
                            <flux:icon.map-pin class="size-6" />
                        </div>
                        <div>
                            <h4 class="font-bold text-zinc-900 dark:text-white">Ubicación</h4>
                            <p class="text-sm text-zinc-500">{{ $restaurante->direccion }}</p>
                        </div>
                    </div>

                    <!-- Contact Details -->
                    <div class="space-y-4 pt-4 border-t border-zinc-100 dark:border-zinc-800">
                        <div class="flex items-center gap-3 text-sm">
                            <flux:icon.envelope class="size-4 text-zinc-400" />
                            <span class="text-zinc-600 dark:text-zinc-400">{{ $restaurante->user->email }}</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm">
                            <flux:icon.phone class="size-4 text-zinc-400" />
                            <span class="text-zinc-600 dark:text-zinc-400">{{ $restaurante->telefono }}</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm">
                            <flux:icon.globe-alt class="size-4 text-zinc-400" />
                            <a href="{{ $restaurante->redes_sociales }}" target="_blank" class="text-teal-600 hover:underline">Sitio web
                                / Redes</a>
                        </div>
                    </div>
                </div>

                <!-- Schedule Application Check -->
                @if(auth()->check() && auth()->user()->id_usuario === $restaurante->id_usuario)
                    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-3xl p-6 shadow-sm space-y-6">
                        <div class="flex items-center gap-4">
                            <div class="size-12 bg-indigo-50 dark:bg-indigo-900/30 rounded-2xl flex items-center justify-center text-indigo-600">
                                <flux:icon.calendar class="size-6" />
                            </div>
                            <div>
                                <h4 class="font-bold text-zinc-900 dark:text-white">Gestionar Horario</h4>
                                <p class="text-sm text-zinc-500">Define tus horas de apertura</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            @foreach($schedule as $index => $day)
                                <div class="flex items-center gap-3 p-3 rounded-xl transition-colors {{ $day['esta_abierto'] ? 'bg-green-50 dark:bg-green-900/10 border border-green-100 dark:border-green-800' : 'bg-zinc-50 dark:bg-zinc-800 border border-zinc-100 dark:border-zinc-700' }}">
                                    <!-- Toggle -->
                                    <div class="flex items-center h-5">
                                        <input wire:model.live="schedule.{{ $index }}.esta_abierto" type="checkbox" class="w-4 h-4 text-emerald-600 bg-zinc-100 border-zinc-300 rounded focus:ring-emerald-500 dark:focus:ring-emerald-600 dark:ring-offset-zinc-800 focus:ring-2 dark:bg-zinc-700 dark:border-zinc-600">
                                    </div>
                                    
                                    <div class="flex-1 grid grid-cols-12 gap-2 items-center">
                                        <span class="col-span-4 text-xs font-bold uppercase tracking-wider {{ $day['esta_abierto'] ? 'text-green-700 dark:text-green-400' : 'text-zinc-400' }}">
                                            {{ $day['nombre_dia'] }}
                                        </span>

                                        @if($day['esta_abierto'])
                                            <input type="time" wire:model="schedule.{{ $index }}.hora_apertura" class="col-span-4 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 text-zinc-900 dark:text-white text-xs rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block w-full p-1.5" required>
                                            <input type="time" wire:model="schedule.{{ $index }}.hora_cierre" class="col-span-4 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 text-zinc-900 dark:text-white text-xs rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block w-full p-1.5" required>
                                        @else
                                            <span class="col-span-8 text-xs text-zinc-400 italic text-center">Cerrado</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                            <flux:button wire:click="updateSchedule" variant="primary" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white">
                                Guardar Horario
                            </flux:button>
                        </div>
                    </div>
                @else
                    <!-- Public Schedule Display -->
                    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-3xl p-6 shadow-sm space-y-4">
                        <div class="flex items-center gap-4 mb-2">
                            <div class="size-10 bg-zinc-100 dark:bg-zinc-800 rounded-xl flex items-center justify-center text-zinc-500">
                                <flux:icon.clock class="size-5" />
                            </div>
                            <h4 class="font-bold text-zinc-900 dark:text-white">Horario</h4>
                        </div>
                        
                        <div class="space-y-2">
                            @foreach($schedule as $day)
                                <div class="flex justify-between text-sm">
                                    <span class="{{ $day['esta_abierto'] ? 'text-zinc-700 dark:text-zinc-300' : 'text-zinc-400' }}">{{ $day['nombre_dia'] }}</span>
                                    @if($day['esta_abierto'])
                                        <span class="font-medium text-zinc-900 dark:text-white">{{ $day['hora_apertura'] }} - {{ $day['hora_cierre'] }}</span>
                                    @else
                                        <span class="text-zinc-400 italic">Cerrado</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Delivery Stats -->
                <div class="bg-zinc-900 dark:bg-white rounded-3xl p-6 text-white dark:text-zinc-900 space-y-4">
                    <h4 class="font-bold flex items-center gap-2 uppercase text-xs tracking-widest opacity-70">
                        <flux:icon.truck class="size-4" />
                        Información de entrega
                    </h4>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span>Tiempo estimado</span>
                            <span class="font-bold">25-35 min</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span>Envío gratis desde</span>
                            <span class="font-bold text-teal-400 dark:text-teal-600">20.00€</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>