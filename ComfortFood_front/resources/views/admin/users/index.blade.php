<x-layouts.mockup title="Panel de administrador">
    <div class="flex items-center gap-4 mb-6">
        <a href="#" class="text-zinc-500 hover:text-zinc-700">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
        </a>
        <h1 class="text-xl font-bold">Lista de usuarios</h1>
        <div class="flex-1"></div>
        <div class="relative">
             <input type="text" placeholder="Buscar" class="pl-8 pr-4 py-2 border border-zinc-300 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
             <svg class="w-4 h-4 absolute left-2.5 top-3 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
        <select class="border border-zinc-300 rounded-md text-sm px-3 py-2 bg-white">
            <option>Tipo</option>
        </select>
         <select class="border border-zinc-300 rounded-md text-sm px-3 py-2 bg-white">
            <option>Estado</option>
        </select>
        <div class="size-10 bg-blue-100 rounded-md flex items-center justify-center text-blue-600">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
            </svg>
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
                         <th class="px-6 py-3">DNI/NIF</th>
                        <th class="px-6 py-3">Tipo</th>
                        <th class="px-6 py-3">Nombre</th>
                        <th class="px-6 py-3">Email</th>
                        <th class="px-6 py-3">Estado</th>
                        <th class="px-6 py-3">Dirección</th>
                        <th class="px-6 py-3">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(range(1, 8) as $i)
                    <tr class="bg-white border-b hover:bg-zinc-50">
                         <td class="px-6 py-4">
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        </td>
                        <td class="px-6 py-4 font-medium text-blue-600">
                            #98090
                        </td>
                        <td class="px-6 py-4">
                            Restaurante
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-zinc-900">Las Acacias</div>
                        </td>
                         <td class="px-6 py-4">
                            email@gmail.com
                        </td>
                        <td class="px-6 py-4">
                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-md">activo</span>
                        </td>
                        <td class="px-6 py-4">
                            46 Coast Rd KIRKTON OF
                        </td>
                        <td class="px-6 py-4">
                             <div class="flex items-center gap-2">
                                <button class="text-zinc-400 hover:text-zinc-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM18.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                    </svg>
                                </button>
                                <a href="{{ route('admin.restaurants.show') }}" class="text-blue-500 hover:underline text-xs">Ver</a>
                            </div>
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
