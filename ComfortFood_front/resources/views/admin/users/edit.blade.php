@php
    // Para demostración, asumimos que es un restaurante si el nombre es 'Las Acacias'
    $isRestaurant = true; // Cambiar a false para ver vista de cliente
@endphp

<x-layouts::app title="Editar usuario">
    <div class="p-6">
        <div class="flex items-center gap-4 mb-6 text-zinc-900 dark:text-zinc-100">
             <a href="{{ route('admin.users.show') }}" class="text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300">
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
                        <flux:input label="{{ $isRestaurant ? 'Nombre Restaurante' : 'Nombre completo' }}" value="{{ $isRestaurant ? 'Las Acacias' : 'Patrick Townsend' }}" />
                        
                        @if($isRestaurant)
                             <flux:input label="Propietario" value="Patrick Townsend" />
                        @endif

                        <flux:input label="{{ $isRestaurant ? 'NIF' : 'DNI' }}" value="{{ $isRestaurant ? '12345678X' : '50877893 M' }}" />
                        <flux:input label="Dirección" value="{{ $isRestaurant ? 'Calle Santa Mónica' : 'Cuesta Molino nº70' }}" class="md:col-span-2" />
                        <flux:input label="Teléfono" value="65 55 55 555" />
                        <flux:input label="Email" value="townsend@gmail.com" />
                        
                        @if($isRestaurant)
                            <flux:input label="Tipo de cocina" value="Mediterránea" />
                            <flux:input label="Horario" value="Lunes a Sábado: 12:00-16:00" />
                            <flux:textarea label="Descripción" class="md:col-span-2">Restaurante familiar especializado en cocina mediterránea. Ofrecemos menús diarios elaborados con productos frescos de temporada.</flux:textarea>
                        @endif

                        <flux:select label="Tipo de usuario" value="{{ $isRestaurant ? 'restaurante' : 'cliente' }}">
                            <flux:select.option value="cliente">Cliente</flux:select.option>
                            <flux:select.option value="restaurante">Restaurante</flux:select.option>
                        </flux:select>

                        <flux:select label="Estado" value="activo">
                            <flux:select.option value="activo">Activo</flux:select.option>
                            <flux:select.option value="inactivo">Inactivo</flux:select.option>
                        </flux:select>
                    </div>

                    <div class="flex items-center justify-end gap-4 pt-6 border-t border-zinc-100 dark:border-zinc-800">
                        <flux:button variant="ghost" href="{{ route('admin.users.show') }}">
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
