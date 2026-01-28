<div class="flex items-center gap-4 mb-6">
    <h1 class="text-xl font-bold dark:text-white">Lista de usuarios</h1>
    <div class="flex-1"></div>
</div>

<div class="bg-white dark:bg-zinc-900 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-zinc-500 dark:text-zinc-400">
            <thead class="text-xs text-zinc-700 dark:text-zinc-300 uppercase bg-zinc-50 dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700">
                <tr>
                    <th class="px-6 py-3">
                        <input type="checkbox" class="rounded border-gray-300 dark:border-zinc-600 dark:bg-zinc-800 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
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
                <tr class="bg-white dark:bg-zinc-900 border-b dark:border-zinc-800 hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                     <td class="px-6 py-4">
                        <input type="checkbox" class="rounded border-gray-300 dark:border-zinc-600 dark:bg-zinc-800 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    </td>
                    <td class="px-6 py-4 font-medium text-blue-600">
                        #9809{{ $i }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $i % 2 == 0 ? 'Restaurante' : 'Cliente' }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <flux:avatar size="xs" :name="'User '.$i" />
                            <div class="font-medium text-zinc-900 dark:text-zinc-100">Usuario Ejemplo {{ $i }}</div>
                        </div>
                    </td>
                     <td class="px-6 py-4">
                        email{{ $i }}@gmail.com
                    </td>
                    <td class="px-6 py-4">
                        @if($i % 3 == 0)
                            <span class="bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 text-xs font-medium px-2.5 py-0.5 rounded-md">inactivo</span>
                        @else
                            <span class="bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 text-xs font-medium px-2.5 py-0.5 rounded-md">activo</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        Calle de Ejemplo, {{ $i }}
                    </td>
                    <td class="px-6 py-4">
                         <div class="flex items-center gap-2">
                            <button class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300">
                                <flux:icon.ellipsis-horizontal variant="mini" />
                            </button>
                            <a href="{{ route('admin.users.show') }}" class="text-blue-500 hover:underline text-xs">Ver</a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-800 flex items-center justify-between">
        <span class="text-sm text-zinc-500 dark:text-zinc-400">Mostrar 10 filas</span>
         <div class="flex items-center gap-2">
            <button class="size-8 flex items-center justify-center rounded-md border border-zinc-300 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-800">&lt;</button>
            <button class="size-8 flex items-center justify-center rounded-md bg-blue-600 text-white">1</button>
            <button class="size-8 flex items-center justify-center rounded-md border border-zinc-300 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-800">2</button>
            <button class="size-8 flex items-center justify-center rounded-md border border-zinc-300 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-800">3</button>
             <span class="px-2 dark:text-zinc-400">...</span>
            <button class="size-8 flex items-center justify-center rounded-md border border-zinc-300 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-800">&gt;</button>
        </div>
        <span class="text-sm text-zinc-500 dark:text-zinc-400">1-10 de 100 filas</span>
    </div>
</div>
