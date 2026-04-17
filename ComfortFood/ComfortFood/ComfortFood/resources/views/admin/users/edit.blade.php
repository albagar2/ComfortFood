<x-layouts::app :title="__('Editar usuario')">
    <div class="p-6">
        <div class="flex items-center gap-4 mb-6 text-zinc-900 dark:text-zinc-100">
            <a href="{{ route('admin.users.show', $user) }}"
                class="text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
            </a>
            <h1 class="text-xl font-bold">Editar usuario</h1>
        </div>

        <div
            class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 overflow-hidden max-w-4xl mx-auto">
            <div class="p-8">
                <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <flux:input name="nombre_completo"
                            label="{{ $user->isRestaurante() ? 'Nombre Restaurante' : 'Nombre completo' }}"
                            value="{{ old('nombre_completo', $user->nombre_completo) }}" />

                        @if($user->isRestaurante())
                        <flux:input name="propietario_mock" label="Propietario" value="{{ $user->nombre_completo }}"
                            disabled />
                        @endif

                        <flux:input name="{{ $user->isRestaurante() ? 'NIF' : 'DNI' }}"
                            label="{{ $user->isRestaurante() ? 'NIF' : 'DNI' }}"
                            value="{{ old($user->isRestaurante() ? 'NIF' : 'DNI', $user->isRestaurante() ? $user->restaurante->NIF : $user->cliente->DNI) }}" />
                        <flux:input name="direccion" label="Dirección"
                            value="{{ old('direccion', $user->isRestaurante() ? $user->restaurante->direccion : $user->cliente->direccion) }}"
                            class="md:col-span-2" />
                        <flux:input name="telefono" label="Teléfono"
                            value="{{ old('telefono', $user->isRestaurante() ? $user->restaurante->telefono : $user->cliente->telefono) }}" />
                        <flux:input name="email" label="Email" value="{{ old('email', $user->email) }}" />

                        @if($user->isRestaurante())
                        <flux:input name="tipo_cocina" label="Tipo de cocina"
                            value="{{ old('tipo_cocina', $user->restaurante->tipo_cocina) }}" />
                        <flux:textarea name="descripcion" label="Descripción" class="md:col-span-2">
                            {{ old('descripcion', $user->restaurante->descripcion) }}
                        </flux:textarea>
                        @endif
                        <flux:input
                            label="Tipo de usuario"
                            value="{{ $user->id_rol == 2 ? 'Cliente' : 'Restaurante' }}"
                            disabled />

                        <flux:select name="es_activo" label="Estado">
                            <flux:select.option value="1" :selected="old('es_activo', $user->es_activo) == '1'">Activo
                            </flux:select.option>
                            <flux:select.option value="0" :selected="old('es_activo', $user->es_activo) == '0'">Inactivo
                            </flux:select.option>
                        </flux:select>
                    </div>

                    @if ($errors->any())
                    <div
                        class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 p-4 rounded-lg text-sm">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

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