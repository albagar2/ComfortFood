<div class="p-6">
    <!-- Header / Navigation -->
    <div class="flex items-center justify-between mb-8">
        <a href="javascript:history.back()" class="text-zinc-900 hover:text-zinc-600">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-8">
              <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
        </a>
    </div>

    <!-- Restaurant Info Header -->
    <div class="flex flex-col lg:flex-row gap-12 mb-12">
        <!-- Info Column -->
        <div class="flex-1 space-y-4">
            <div class="flex items-baseline gap-4">
                <h1 class="text-3xl font-bold text-zinc-900 dark:text-white">{{ $restaurante->user->nombre_completo ?? 'Nombre Restaurante' }}</h1>
                <!-- Mock Open Status -->
                <span class="text-sm font-medium text-green-500 bg-green-50 px-2 py-1 rounded-md">Abierto hasta las 23:00</span>
            </div>

            <!-- Rating -->
            <div class="flex items-center gap-1">
                 <div class="flex text-yellow-400">
                     @for($i=0; $i<5; $i++) <flux:icon.star class="size-5 fill-current" /> @endfor
                 </div>
                 <span class="text-zinc-500 font-medium ml-1">4.6 (120 reseñas)</span>
            </div>

            <p class="text-zinc-500 max-w-2xl leading-relaxed">
                {{ $restaurante->descripcion ?? 'Restaurante familiar especializado en cocina mediterránea. Ofrecemos menús diarios elaborados con productos frescos de temporada. Ambiente acogedor, servicio rápido y opciones para llevar.' }}
            </p>
            
            <div class="flex flex-col gap-1 text-zinc-500 font-medium pt-2">
                 <p>{{ $restaurante->user->email ?? 'correo@ejemplo.com' }} | {{ $restaurante->telefono ?? '956 678 124' }}</p>
                 <p class="text-blue-500 hover:underline">{{ $restaurante->redes_sociales ?? 'https://www.instagram.com/restaurante' }}</p>
            </div>

            <div class="border border-zinc-200 dark:border-zinc-800 rounded-lg p-4 bg-zinc-50 dark:bg-zinc-800/50 flex items-start gap-4">
                <flux:icon.map-pin class="size-5 text-red-500 mt-1 flex-shrink-0" />
                <div class="text-sm text-zinc-600 dark:text-zinc-400">
                    <p class="font-medium text-zinc-900 dark:text-white">{{ $restaurante->direccion ?? 'Calle Mayor, 25 - Puente Genil' }}</p>
                    <p>Lunes a Sábado 12:00 - 23:00 (horario continuo)</p>
                    <p class="font-bold mt-2">Envío gratis desde 20€</p>
                </div>
            </div>
        </div>

        <!-- Image Column -->
        <div class="w-full lg:w-96 flex flex-col items-center">
             <div class="aspect-square w-full bg-zinc-200 dark:bg-zinc-800 rounded-3xl overflow-hidden flex items-center justify-center relative">
                  @if($restaurante->url_imagen_perfil)
                        <img src="{{ $restaurante->url_imagen_perfil }}" class="w-full h-full object-cover">
                  @else
                        <flux:icon.photo class="size-24 text-zinc-400" />
                  @endif
             </div>
             <div class="mt-4 text-center">
                 <span class="text-teal-600 font-bold border-b-2 border-teal-600 pb-0.5">Tipo de comida: {{ $restaurante->tipo_cocina ?? 'Mediterránea' }}</span>
             </div>
        </div>
    </div>

    <div class="flex justify-end mb-6">
        <button class="text-blue-500 hover:text-blue-700 font-medium text-sm">ver más</button>
    </div>

    <!-- Menus Grid -->
     <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($restaurante->menus as $menu)
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl p-6 shadow-sm flex flex-col">
             <div class="flex justify-between items-start mb-4">
                 <div>
                     <span class="text-xs uppercase font-bold text-zinc-900 dark:text-white">Plato</span>
                     <span class="block text-xs text-zinc-400">{{ $menu->updated_at->format('d M Y, h:i A') }}</span>
                 </div>
             </div>
             
             <a href="{{ route('menu.show', $menu->id_menu) }}" wire:navigate class="aspect-square bg-zinc-100 dark:bg-zinc-800 rounded-lg mb-4 flex items-center justify-center overflow-hidden w-24 mx-auto border-4 border-yellow-400 cursor-pointer hover:scale-105 transition-transform">
                @if($menu->url_foto)
                    <img src="{{ $menu->url_foto }}" alt="{{ $menu->nombre_menu }}" class="w-full h-full object-cover">
                @else
                    <flux:icon.photo class="size-10 text-zinc-300" />
                @endif
             </a>

             <div class="mb-6 flex-1">
                 <h3 class="font-bold text-zinc-900 dark:text-white mb-1">{{ $menu->nombre_menu }}</h3>
                 <p class="text-xs text-zinc-500 line-clamp-2">{{ $menu->descripcion_menu }}</p>
                 <p class="text-xs text-zinc-400 line-clamp-1 mt-1">Propiedades: {{ $menu->propiedades_nutricionales }}</p>
             </div>
            
             <div class="flex justify-between items-center pt-4 border-t border-zinc-100 dark:border-zinc-800">
                 <span class="font-bold text-zinc-900 dark:text-white">Precio <span class="ml-2">{{ number_format($menu->precio, 2) }}€</span></span>
             </div>
        </div>
        @endforeach
     </div>
</div>
