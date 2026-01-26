<x-layouts.customer_mockup title="Historial de pedidos">
    <div class="flex items-center gap-4 py-6">
        <h1 class="text-xl font-bold flex items-center gap-2">
            <a href="#" class="text-zinc-500 hover:text-zinc-700">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
            </a>
            Historial de pedidos
        </h1>
        
        <div class="flex-1">
             <div class="relative w-full max-w-md mx-6">
                 <input type="text" placeholder="Buscar" class="w-full pl-8 pr-4 py-2 border border-zinc-200 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 shadow-sm">
                 <svg class="w-4 h-4 absolute left-2.5 top-3 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
        </div>
        
        <div class="flex gap-2">
             <select class="border border-zinc-200 rounded-md text-sm px-3 py-2 bg-white text-zinc-500">
                <option>Estado</option>
            </select>
             <select class="border border-zinc-200 rounded-md text-sm px-3 py-2 bg-white text-zinc-500">
                <option>Fecha</option>
            </select>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-zinc-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-zinc-500">
                <thead class="text-xs text-zinc-700 uppercase bg-zinc-50 border-b border-zinc-200">
                    <tr>
                         <th class="px-6 py-3">
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        </th>
                        <th class="px-6 py-3">Pedido</th>
                        <th class="px-6 py-3">Fecha/Hora</th>
                        <th class="px-6 py-3">Restaurante</th>
                        <th class="px-6 py-3">Total</th>
                        <th class="px-6 py-3">Estado</th>
                        <th class="px-6 py-3">Detalles del menú</th>
                        <th class="px-6 py-3">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(range(1, 10) as $i)
                    <tr class="bg-white border-b hover:bg-zinc-50">
                        <td class="px-6 py-4">
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        </td>
                        <td class="px-6 py-4 font-medium text-blue-600">
                            #98090
                        </td>
                        <td class="px-6 py-4">
                             <div>Mar 24, 2025</div>
                            <div class="text-xs text-zinc-400">03:45 PM</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <span class="size-6 bg-orange-100 rounded-md flex items-center justify-center text-xs">🍔</span>
                                Nombre Restaurante
                            </div>
                        </td>
                        <td class="px-6 py-4 font-semibold text-zinc-900">
                            240.80€
                        </td>
                        <td class="px-6 py-4">
                             @if($i % 3 == 0)
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-md">Completado</span>
                            @elseif($i % 3 == 1)
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-md">En preparación</span>
                            @else
                                <span class="bg-orange-100 text-orange-800 text-xs font-medium px-2.5 py-0.5 rounded-md">Cancelado</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            Descripción
                        </td>
                        <td class="px-6 py-4">
                            <button class="text-zinc-400 hover:text-zinc-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25 l-7.5 7.5 -7.5 -7.5" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-zinc-200 flex items-center justify-between">
            <div class="flex items-center gap-2 text-sm text-zinc-500">
                Mostrar 
                <select class="border border-zinc-200 rounded-md text-xs px-2 py-1">
                    <option>10</option>
                </select>
                filas
            </div>
            <div class="flex items-center gap-2">
                <button class="size-8 flex items-center justify-center rounded-md bg-blue-600 text-white">1</button>
                <button class="size-8 flex items-center justify-center rounded-md border border-zinc-200 hover:bg-zinc-50">2</button>
                <button class="size-8 flex items-center justify-center rounded-md border border-zinc-200 hover:bg-zinc-50">3</button>
                <button class="size-8 flex items-center justify-center rounded-md border border-zinc-200 hover:bg-zinc-50">4</button>
                <button class="size-8 flex items-center justify-center rounded-md border border-zinc-200 hover:bg-zinc-50">5</button>
                <button class="size-8 flex items-center justify-center rounded-md border border-zinc-200 hover:bg-zinc-50">&gt;</button>
            </div>
            <span class="text-sm text-zinc-500">1 - 10 de 100 filas</span>
        </div>
    </div>
</x-layouts.customer_mockup>
