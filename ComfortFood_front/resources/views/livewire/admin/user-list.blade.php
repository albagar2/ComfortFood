<div class="flex flex-col gap-6">
    <div class="flex items-center gap-4">
        <h1 class="text-xl font-bold dark:text-white">Lista de usuarios</h1>
        <div class="flex-1"></div>
    </div>

    <div class="bg-white dark:bg-zinc-900 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-zinc-500 dark:text-zinc-400">
                <thead class="text-xs text-zinc-700 dark:text-zinc-300 uppercase bg-zinc-50 dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700">
                    <tr>
                         <th class="px-6 py-3">DNI/NIF</th>
                        <th class="px-6 py-3">Tipo</th>
                        <th class="px-6 py-3">Nombre</th>
                        <th class="px-6 py-3">Email</th>
                        <th class="px-6 py-3">Estado</th>
                        <th class="px-6 py-3">Dirección</th>
                        <th class="px-6 py-3">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                    @forelse($users as $user)
                        <tr class="bg-white dark:bg-zinc-900 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                            <td class="px-6 py-4 font-medium text-blue-600 dark:text-blue-400">
                                @if($user->isRestaurante())
                                    {{ $user->restaurante->NIF ?? '-' }}
                                @else
                                    {{ $user->cliente->DNI ?? '-' }}
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded-md text-xs font-medium {{ $user->isRestaurante() ? 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' }}">
                                    {{ $user->rol->nombre_rol }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <flux:avatar size="xs" :src="$user->profile_photo_url" :name="$user->nombre_completo" />
                                    <div class="font-medium text-zinc-900 dark:text-zinc-100">{{ $user->nombre_completo }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                @if($user->es_activo)
                                    <span class="bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 text-xs font-medium px-2.5 py-0.5 rounded-md">activo</span>
                                @else
                                    <span class="bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 text-xs font-medium px-2.5 py-0.5 rounded-md">inactivo</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($user->isRestaurante())
                                    {{ $user->restaurante->direccion ?? '-' }}
                                @else
                                    {{ $user->cliente->direccion ?? '-' }}
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.users.show', $user) }}" class="text-blue-500 hover:underline text-xs" wire:navigate>Ver</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-zinc-500 dark:text-zinc-400">
                                No se encontraron usuarios con los criterios de búsqueda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-800">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
