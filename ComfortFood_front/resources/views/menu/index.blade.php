<x-layouts.mockup title="Editar Menú">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('orders.history') }}" class="text-zinc-500 hover:text-zinc-700">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
        </a>
        <button class="bg-transparent border border-zinc-300 text-zinc-700 px-3 py-1.5 rounded-md text-sm font-medium hover:bg-zinc-50 flex items-center gap-2">
            <span>+</span> Añadir Menú
        </button>
        <div class="flex-1"></div>
        <div class="flex gap-2">
            <button class="px-4 py-2 text-sm font-medium text-zinc-600 bg-white border border-zinc-200 rounded-md">Vista restaurante</button>
            <button class="px-4 py-2 text-sm font-medium text-white bg-orange-500 rounded-md hover:bg-orange-600">Encuestas</button>
        </div>
    </div>

    <!-- Filter chips for Restaurant status -->
    <div class="flex gap-4 mb-6">
        <div class="bg-white px-3 py-1 rounded-full border border-zinc-200 text-xs text-zinc-600 flex items-center gap-2">
            Restaurante <span class="bg-green-100 text-green-800 text-xs px-1.5 py-0.5 rounded-full">Activo</span>
        </div>
         <div class="bg-white px-3 py-1 rounded-full border border-zinc-200 text-xs text-zinc-600 flex items-center gap-2">
             Restaurante <span class="bg-red-100 text-red-800 text-xs px-1.5 py-0.5 rounded-full">No disponible</span>
        </div>
         <div class="bg-white px-3 py-1 rounded-full border border-zinc-200 text-xs text-zinc-600 flex items-center gap-2">
            Restaurante <span class="bg-green-100 text-green-800 text-xs px-1.5 py-0.5 rounded-full">Activo</span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach(range(1, 6) as $i)
        <div class="bg-white rounded-xl shadow-sm border border-zinc-200 p-4">
            <div class="aspect-square bg-zinc-100 rounded-lg mb-4 flex items-center justify-center relative">
                 <svg class="size-16 text-zinc-300" fill="currentColor" viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
            </div>
            <div class="mb-4">
                <h3 class="font-bold text-lg mb-1">Menú Día</h3>
                <p class="text-xs text-zinc-500">Descripción</p>
                <p class="text-xs text-zinc-500">Propiedades nutricionales</p>
            </div>
            <div class="flex items-center justify-between">
                <span class="font-bold text-zinc-900">Precio: 5.50€</span>
                <div class="flex gap-2">
                    <button class="p-1.5 text-red-500 hover:bg-red-50 rounded-md border border-red-200">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                    <button class="p-1.5 text-yellow-500 hover:bg-yellow-50 rounded-md border border-yellow-200">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                    </button>
                    <button class="p-1.5 text-zinc-500 hover:bg-zinc-50 rounded-md border border-zinc-200">
                         <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</x-layouts.mockup>
