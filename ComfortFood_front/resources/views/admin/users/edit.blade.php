<x-layouts::app :title="__('Editar usuario')">
    <div class="p-6">
        <div class="flex items-center gap-4 mb-6 text-zinc-900 dark:text-zinc-100">
             <a href="{{ route('admin.users.show', $user) }}" class="text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
            </a>
            <h1 class="text-xl font-bold">Editar usuario</h1>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 overflow-hidden max-w-4xl mx-auto">
            <div class="p-8">
                <form class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <flux:input label="{{ $user->isRestaurante() ? 'Nombre Restaurante' : 'Nombre completo' }}" value="{{ $user->nombre_completo }}" />
                        
                        @if($user->isRestaurante())
                             <flux:input label="Propietario" value="{{ $user->nombre_completo }}" />
                        @endif

                        <flux:input label="{{ $user->isRestaurante() ? 'NIF' : 'DNI' }}" value="{{ $user->isRestaurante() ? $user->restaurante->NIF : $user->cliente->DNI }}" />
                        <flux:input label="Dirección" value="{{ $user->isRestaurante() ? $user->restaurante->direccion : $user->cliente->direccion }}" class="md:col-span-2" />
                        <flux:input label="Teléfono" value="{{ $user->isRestaurante() ? $user->restaurante->telefono : $user->cliente->telefono }}" />
                        <flux:input label="Email" value="{{ $user->email }}" />
                        
                        @if($user->isRestaurante())
                            <flux:input label="Tipo de cocina" value="{{ $user->restaurante->tipo_cocina }}" />
                            <flux:textarea label="Descripción" class="md:col-span-2">{{ $user->restaurante->descripcion }}</flux:textarea>
                        @endif

                        <flux:select label="Tipo de usuario" value="{{ $user->id_rol }}">
                            <flux:select.option value="2">Cliente</flux:select.option>
                            <flux:select.option value="3">Restaurante</flux:select.option>
                        </flux:select>

                        <flux:select label="Estado" value="{{ $user->es_activo ? 'activo' : 'inactivo' }}">
                            <flux:select.option value="activo">Activo</flux:select.option>
                            <flux:select.option value="inactivo">Inactivo</flux:select.option>
                        </flux:select>
                    </div>

                    <div class="flex items-center justify-end gap-4 pt-6 border-t border-zinc-100 dark:border-zinc-800">
                        <flux:button variant="ghost" href="{{ route('admin.users.show', $user) }}">
                            Cancelar
                        </flux:button>
                        <flux:button type="submit" variant="primary">
                            Guardar cambios
                        </flux:button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts::app>
