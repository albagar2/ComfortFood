<x-layouts.mockup title="Añadir o Editar Menú">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('menu.index') }}" class="text-zinc-500 hover:text-zinc-700">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
        </a>
        <div class="flex-1"></div>
        <button class="px-4 py-2 text-sm font-medium text-zinc-600 bg-white border border-zinc-200 rounded-md">Vista restaurante</button>
        <button class="px-4 py-2 text-sm font-medium text-white bg-orange-500 rounded-md hover:bg-orange-600">Encuestas</button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-zinc-200 p-8 max-w-2xl mx-auto">
        <h2 class="text-center text-xl font-bold mb-8">Añadir nombre menú</h2>

        <form>
            <div class="flex flex-col md:flex-row gap-8">
                <div class="flex-1 space-y-4">
                    <div>
                        <input type="text" placeholder="Añadir nombre menú" class="w-full px-4 py-2 border border-zinc-200 rounded-lg text-sm bg-zinc-50 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                    <div>
                         <input type="text" placeholder="Añadir segundo plato" class="w-full px-4 py-2 border border-zinc-200 rounded-lg text-sm bg-zinc-50 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                     <div>
                         <input type="text" placeholder="Añadir postre" class="w-full px-4 py-2 border border-zinc-200 rounded-lg text-sm bg-zinc-50 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                     <div>
                         <input type="text" placeholder="Añadir bebida" class="w-full px-4 py-2 border border-zinc-200 rounded-lg text-sm bg-zinc-50 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                     <div>
                         <textarea placeholder="Añadir descripción" rows="3" class="w-full px-4 py-2 border border-zinc-200 rounded-lg text-sm bg-zinc-50 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
                    </div>
                    <div>
                         <textarea placeholder="Añadir Propiedades Nutricionales" rows="3" class="w-full px-4 py-2 border border-zinc-200 rounded-lg text-sm bg-zinc-50 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
                    </div>
                    <div class="flex items-center gap-4">
                        <label class="text-sm font-medium text-zinc-700 whitespace-nowrap">Añadir precio</label>
                        <input type="text" placeholder="0.00€" class="w-24 px-4 py-2 border border-zinc-200 rounded-lg text-sm bg-zinc-50 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                </div>
                <div class="w-full md:w-1/3 flex flex-col items-center">
                    <div class="size-24 bg-orange-100 border-2 border-dashed border-orange-300 rounded-lg mb-2 flex items-center justify-center cursor-pointer hover:bg-orange-50">
                        <svg class="size-10 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    </div>
                    <span class="text-xs text-zinc-500 font-medium">Añadir Imagen</span>
                </div>
            </div>

            <div class="flex justify-end gap-2 mt-8">
                 <button type="button" class="size-10 flex items-center justify-center text-red-500 border border-zinc-200 rounded-lg hover:bg-red-50">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                 </button>
                 <button type="submit" class="size-10 flex items-center justify-center text-zinc-600 border border-zinc-200 rounded-lg hover:bg-zinc-50">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" /></svg>
                 </button>
            </div>
        </form>
    </div>
</x-layouts.mockup>
