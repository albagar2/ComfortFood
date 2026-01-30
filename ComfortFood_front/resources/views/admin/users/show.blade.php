<x-layouts::app :title="__('Detalle de usuario')">
    <div class="p-6">
        <div class="flex items-center gap-4 mb-6 text-zinc-900 dark:text-zinc-100">
            <a href="{{ route('admin.users.index') }}"
                class="text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
            </a>
            <h1 class="text-xl font-bold">Detalle de usuario</h1>
        </div>

        <div
            class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 overflow-hidden max-w-4xl mx-auto">
            <div class="flex items-center justify-between px-8 py-6 border-b border-zinc-100 dark:border-zinc-800">
                @if($user->es_activo)
                    <span
                        class="bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 text-xs font-medium px-3 py-1 rounded-full">Activo</span>
                @else
                    <span
                        class="bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 text-xs font-medium px-3 py-1 rounded-full">Inactivo</span>
                @endif

                <div class="text-center flex-1 flex flex-col items-center gap-2">
                    <flux:avatar :src="$user->profile_photo_url" :name="$user->nombre_completo" size="xl" />
                    <h2 class="text-lg font-bold text-zinc-900 dark:text-white">{{ $user->nombre_completo }}</h2>
                </div>
                <a href="{{ route('admin.users.edit', $user) }}"
                    class="bg-blue-600 text-white px-4 py-1.5 rounded-md text-sm hover:bg-blue-700 flex items-center gap-2">
                    <flux:icon.pencil-square variant="mini" class="size-4" />
                    {{ __('editar') }}
                </a>
            </div>

            <div class="p-8 space-y-6">
                @if($user->isRestaurante())
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-b border-zinc-50 dark:border-zinc-800 pb-4">
                        <div class="text-sm font-medium text-purple-600 dark:text-purple-400">Propietario:</div>
                        <div class="md:col-span-2 text-sm text-zinc-900 dark:text-zinc-300">{{ $user->nombre_completo }}
                        </div>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-b border-zinc-50 dark:border-zinc-800 pb-4">
                        <div class="text-sm font-medium text-blue-500">Nombre completo:</div>
                        <div class="md:col-span-2 text-sm text-zinc-900 dark:text-zinc-300">{{ $user->nombre_completo }}
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-b border-zinc-50 dark:border-zinc-800 pb-4">
                    <div
                        class="text-sm font-medium {{ $user->isRestaurante() ? 'text-purple-600 dark:text-purple-400' : 'text-blue-500' }}">
                        {{ $user->isRestaurante() ? 'NIF:' : 'DNI:' }}</div>
                    <div class="md:col-span-2 text-sm text-zinc-900 dark:text-zinc-300">
                        @if($user->isRestaurante())
                            {{ $user->restaurante->NIF ?? '-' }}
                        @else
                            {{ $user->cliente->DNI ?? '-' }}
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-b border-zinc-50 dark:border-zinc-800 pb-4">
                    <div
                        class="text-sm font-medium {{ $user->isRestaurante() ? 'text-purple-600 dark:text-purple-400' : 'text-blue-500' }}">
                        Dirección:</div>
                    <div class="md:col-span-2 text-sm text-zinc-900 dark:text-zinc-300">
                        @if($user->isRestaurante())
                            {{ $user->restaurante->direccion ?? '-' }}
                        @else
                            {{ $user->cliente->direccion ?? '-' }}
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-b border-zinc-50 dark:border-zinc-800 pb-4">
                    <div
                        class="text-sm font-medium {{ $user->isRestaurante() ? 'text-purple-600 dark:text-purple-400' : 'text-blue-500' }}">
                        Teléfono:</div>
                    <div class="md:col-span-2 text-sm text-zinc-900 dark:text-zinc-300">
                        @if($user->isRestaurante())
                            {{ $user->restaurante->telefono ?? '-' }}
                        @else
                            {{ $user->cliente->telefono ?? '-' }}
                        @endif
                    </div>
                </div>

                @if($user->isRestaurante())
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-b border-zinc-50 dark:border-zinc-800 pb-4">
                        <div class="text-sm font-medium text-purple-600 dark:text-purple-400">Tipo de cocina:</div>
                        <div class="md:col-span-2 text-sm text-zinc-900 dark:text-zinc-300">
                            {{ $user->restaurante->tipo_cocina ?? '-' }}</div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-b border-zinc-50 dark:border-zinc-800 pb-4">
                        <div class="text-sm font-medium text-purple-600 dark:text-purple-400">Descripción:</div>
                        <div class="md:col-span-2 text-sm text-zinc-900 dark:text-zinc-300">
                            {{ $user->restaurante->descripcion ?? '-' }}
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-b border-zinc-50 dark:border-zinc-800 pb-4">
                    <div
                        class="text-sm font-medium {{ $user->isRestaurante() ? 'text-purple-600 dark:text-purple-400' : 'text-blue-500' }}">
                        Tipo de usuario:</div>
                    <div class="md:col-span-2 text-sm text-zinc-900 dark:text-zinc-300">{{ $user->rol->nombre_rol }}
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-b border-zinc-50 dark:border-zinc-800 pb-4">
                    <div
                        class="text-sm font-medium {{ $user->isRestaurante() ? 'text-purple-600 dark:text-purple-400' : 'text-blue-500' }}">
                        Fecha de registro:</div>
                    <div class="md:col-span-2 text-sm text-zinc-900 dark:text-zinc-300">
                        {{ $user->created_at->format('d-m-Y') }}</div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-b border-zinc-50 dark:border-zinc-800 pb-4">
                    <div
                        class="text-sm font-medium {{ $user->isRestaurante() ? 'text-purple-600 dark:text-purple-400' : 'text-blue-500' }}">
                        Email:</div>
                    <div class="md:col-span-2 text-sm text-zinc-900 dark:text-zinc-300">{{ $user->email }}</div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pb-4">
                    <div
                        class="text-sm font-medium {{ $user->isRestaurante() ? 'text-purple-600 dark:text-purple-400' : 'text-blue-500' }}">
                        Contraseña:</div>
                    <div class="md:col-span-2 text-sm text-zinc-900 dark:text-zinc-300">******************</div>
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>