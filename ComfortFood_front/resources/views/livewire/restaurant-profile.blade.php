<div class="min-h-screen bg-zinc-50 dark:bg-zinc-950 px-4 py-8 md:px-8">
    <div class="max-w-7xl mx-auto space-y-12">
        <!-- Header / Navigation -->
        <div class="flex items-center justify-between">
            <a href="javascript:history.back()"
                class="p-2 hover:bg-zinc-100 dark:hover:bg-zinc-900 rounded-full transition-colors text-zinc-600 dark:text-zinc-400">
                <flux:icon.arrow-left class="size-6" />
            </a>
            <div class="flex gap-3">
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
                        <div
                            class="bg-white/10 backdrop-blur-md border border-white/20 px-4 py-2 rounded-2xl text-white text-sm font-medium">
                            Abierto hasta 23:00
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
                    <div class="flex items-center justify-between">
                        <h2 class="text-2xl font-black text-zinc-900 dark:text-white uppercase tracking-tight">Nuestra
                            Carta</h2>
                        <flux:button variant="subtle" size="sm">Filtros</flux:button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full">
                        @foreach($restaurante->menus as $menu)
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

                                <button
                                    class="mt-4 w-full bg-zinc-100 dark:bg-zinc-800 group-hover:bg-zinc-900 dark:group-hover:bg-white text-zinc-900 dark:text-white group-hover:text-white dark:group-hover:text-zinc-950 py-2 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2">
                                    <flux:icon.plus class="size-4" />
                                    Añadir al pedido
                                </button>
                            </div>
                        @endforeach
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
                            <a href="{{ $restaurante->redes_sociales }}" class="text-teal-600 hover:underline">Sitio web
                                / Redes</a>
                        </div>
                    </div>
                </div>

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