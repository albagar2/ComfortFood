<x-layouts.mockup title="Vista restaurante">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('menu.index') }}" class="text-zinc-500 hover:text-zinc-700">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
        </a>
        <div class="flex-1"></div>
        <button class="px-4 py-2 text-sm font-medium text-white bg-orange-500 rounded-md hover:bg-orange-600">Vista restaurante</button>
        <button class="px-4 py-2 text-sm font-medium text-zinc-600 bg-white border border-zinc-200 rounded-md">Encuestas</button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-zinc-200 p-8">
        <div class="flex flex-col md:flex-row gap-8">
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-2">
                    <h1 class="text-2xl font-bold">Restaurante Las Acacias</h1>
                    <span class="text-green-500 text-sm font-medium">- Abierto hasta las 23:00</span>
                </div>
                <div class="flex items-center gap-1 text-yellow-400 text-sm mb-4">
                    <span>★</span><span>4.8</span> <span class="text-zinc-400">(120 reseñas)</span>
                </div>
                <p class="text-zinc-500 text-sm mb-6 max-w-2xl">
                    Restaurante familiar especializado en cocina mediterránea. Ofrecemos menús diarios elaborados con productos frescos de temporada. Ambiente acogedor, servicio rápido y opciones para llevar.
                </p>

                <div class="space-y-2 text-sm text-zinc-600">
                    <p class="flex items-center gap-2">
                        <span class="text-blue-500">lasacacias@gmail.com</span> | <span class="font-medium">955 678 123</span>
                    </p>
                    <p class="text-blue-500">https://www.instagram.com/lasacacias</p>
                    <p class="flex items-center gap-2 mt-4">
                        <svg class="size-4 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        Calle Mayor, 25 - Madrid 28013
                    </p>
                    <p class="text-zinc-500">Lunes a Sábado 12:00 - 23:00 (Horario continuo)</p>
                    <p class="mt-4 text-zinc-500 text-xs">Envío gratis desde 20€</p>
                </div>
            </div>
            <div class="w-full md:w-1/3 flex flex-col items-center">
                 <div class="w-full aspect-square bg-zinc-100 rounded-xl flex items-center justify-center mb-4">
                      <svg class="size-24 text-zinc-300" fill="currentColor" viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
                </div>
                <div class="self-start">
                    <span class="text-sm font-medium text-zinc-700">Tipo de comida: Mediterránea</span>
                </div>
            </div>
        </div>

        <div class="mt-8 flex justify-end">
             <button class="text-xs text-zinc-500 border border-zinc-200 px-3 py-1 rounded-md">ver más</button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
            @foreach(range(1, 3) as $i)
            <div class="bg-white rounded-lg border border-zinc-100 shadow-sm p-4 flex gap-4">
                <div class="flex-1">
                    <span class="text-xs text-zinc-400 block mb-1">03 Feb 2023, 08:28 PM</span>
                    <div class="aspect-video bg-zinc-100 rounded-md mb-2 flex items-center justify-center">
                        <svg class="size-8 text-zinc-300" fill="currentColor" viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
                    </div>
                    <h4 class="font-bold text-sm">Menú Día</h4>
                    <p class="text-xs text-zinc-500">Descripción</p>
                    <p class="text-xs text-zinc-500 mb-2">Propiedades nutricionales</p>
                    <span class="font-bold text-sm">Precio: 5.50€</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</x-layouts.mockup>
