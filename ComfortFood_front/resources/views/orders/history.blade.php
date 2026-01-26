<x-layouts.mockup title="Historial de pedidos">
    <div class="flex items-center gap-4 mb-6">
        <button class="p-2 hover:bg-zinc-100 rounded-md">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
        </button>
        <h1 class="text-xl font-bold">Historial de pedidos</h1>
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
                        <th class="px-6 py-3">Cliente</th>
                        <th class="px-6 py-3">Total</th>
                        <th class="px-6 py-3">Estado</th>
                        <th class="px-6 py-3">Dirección</th>
                        <th class="px-6 py-3">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Row 1 -->
                    @foreach(range(1, 8) as $i)
                    <tr class="bg-white border-b hover:bg-zinc-50">
                        <td class="px-6 py-4">
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        </td>
                        <td class="px-6 py-4 font-medium text-blue-600">
                            #98080
                        </td>
                        <td class="px-6 py-4">
                            <div>Mar 24, 2025</div>
                            <div class="text-xs text-zinc-400">03:45 PM</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="size-8 rounded-full bg-zinc-200"></div> <!-- Placeholder avatar -->
                                <div class="font-medium text-zinc-900">Patrick Townsend</div>
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
                            46 Coast Rd KIRKTON OF
                        </td>
                        <td class="px-6 py-4">
                            <button class="text-zinc-400 hover:text-zinc-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM18.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-zinc-200 flex items-center justify-between">
            <span class="text-sm text-zinc-500">Mostrar 10 filas</span>
            <div class="flex items-center gap-2">
                <button class="size-8 flex items-center justify-center rounded-md border border-zinc-300 hover:bg-zinc-50">&lt;</button>
                <button class="size-8 flex items-center justify-center rounded-md bg-blue-600 text-white">1</button>
                <button class="size-8 flex items-center justify-center rounded-md border border-zinc-300 hover:bg-zinc-50">2</button>
                <button class="size-8 flex items-center justify-center rounded-md border border-zinc-300 hover:bg-zinc-50">3</button>
                <span class="px-2">...</span>
                <button class="size-8 flex items-center justify-center rounded-md border border-zinc-300 hover:bg-zinc-50">&gt;</button>
            </div>
            <span class="text-sm text-zinc-500">1-10 de 100 filas</span>
        </div>
    </div>
</x-layouts.mockup>
