@php
    // Para demostración, asumimos que es un restaurante si el nombre es 'Las Acacias'
    $isRestaurant = true; // Cambiar a false para ver vista de cliente
    $userName = $isRestaurant ? 'Las Acacias' : 'Patrick Townsend';
@endphp

<x-layouts::app title="Detalle de usuario">
    <div class="p-6">
        <div class="flex items-center gap-4 mb-6 text-zinc-900 dark:text-zinc-100">
             <a href="{{ route('admin.users.index') }}" class="text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
            </a>
            <h1 class="text-xl font-bold">Detalle de usuario</h1>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 overflow-hidden max-w-4xl mx-auto">
            <div class="flex items-center justify-between px-8 py-6 border-b border-zinc-100 dark:border-zinc-800">
                 <span class="bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 text-xs font-medium px-3 py-1 rounded-full">Activo</span>
                 <div class="text-center flex-1">
                     <h2 class="text-lg font-bold text-zinc-900 dark:text-white">{{ $userName }}</h2>
                 </div>
                 <a href="{{ route('admin.users.edit') }}" class="bg-blue-600 text-white px-4 py-1.5 rounded-md text-sm hover:bg-blue-700 flex items-center gap-2">
                     <flux:icon.pencil-square variant="mini" class="size-4" />
                     {{ __('editar') }}
                 </a>
            </div>

            <div class="p-8 space-y-6">
                @if($isRestaurant)
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-b border-zinc-50 dark:border-zinc-800 pb-4">
                        <div class="text-sm font-medium text-purple-600 dark:text-purple-400">Propietario:</div>
                        <div class="md:col-span-2 text-sm text-zinc-900 dark:text-zinc-300">Patrick Townsend</div>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-b border-zinc-50 dark:border-zinc-800 pb-4">
                        <div class="text-sm font-medium text-blue-500">Nombre completo:</div>
                        <div class="md:col-span-2 text-sm text-zinc-900 dark:text-zinc-300">Patrick Townsend</div>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-b border-zinc-50 dark:border-zinc-800 pb-4">
                    <div class="text-sm font-medium {{ $isRestaurant ? 'text-purple-600 dark:text-purple-400' : 'text-blue-500' }}">{{ $isRestaurant ? 'NIF:' : 'DNI:' }}</div>
                    <div class="md:col-span-2 text-sm text-zinc-900 dark:text-zinc-300">{{ $isRestaurant ? '12345678X' : '50877893 M' }}</div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-b border-zinc-50 dark:border-zinc-800 pb-4">
                    <div class="text-sm font-medium {{ $isRestaurant ? 'text-purple-600 dark:text-purple-400' : 'text-blue-500' }}">Dirección:</div>
                    <div class="md:col-span-2 text-sm text-zinc-900 dark:text-zinc-300">{{ $isRestaurant ? 'Calle Santa Mónica' : 'Cuesta Molino nº70' }}</div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-b border-zinc-50 dark:border-zinc-800 pb-4">
                    <div class="text-sm font-medium {{ $isRestaurant ? 'text-purple-600 dark:text-purple-400' : 'text-blue-500' }}">Teléfono:</div>
                    <div class="md:col-span-2 text-sm text-zinc-900 dark:text-zinc-300">65 55 55 555</div>
                </div>

                @if($isRestaurant)
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-b border-zinc-50 dark:border-zinc-800 pb-4">
                        <div class="text-sm font-medium text-purple-600 dark:text-purple-400">Tipo de cocina:</div>
                        <div class="md:col-span-2 text-sm text-zinc-900 dark:text-zinc-300">Mediterránea</div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-b border-zinc-50 dark:border-zinc-800 pb-4">
                        <div class="text-sm font-medium text-purple-600 dark:text-purple-400">Horario:</div>
                        <div class="md:col-span-2 text-sm text-zinc-900 dark:text-zinc-300">Lunes a Sábado: 12:00-16:00</div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-b border-zinc-50 dark:border-zinc-800 pb-4">
                        <div class="text-sm font-medium text-purple-600 dark:text-purple-400">Descripción:</div>
                        <div class="md:col-span-2 text-sm text-zinc-900 dark:text-zinc-300">
                            Restaurante familiar especializado en cocina mediterránea. Ofrecemos menús diarios elaborados con productos frescos de temporada.
                        </div>
                    </div>
                @endif

                 <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-b border-zinc-50 dark:border-zinc-800 pb-4">
                    <div class="text-sm font-medium {{ $isRestaurant ? 'text-purple-600 dark:text-purple-400' : 'text-blue-500' }}">Tipo de usuario:</div>
                    <div class="md:col-span-2 text-sm text-zinc-900 dark:text-zinc-300">{{ $isRestaurant ? 'Restaurante' : 'Cliente' }}</div>
                </div>
                 <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-b border-zinc-50 dark:border-zinc-800 pb-4">
                    <div class="text-sm font-medium {{ $isRestaurant ? 'text-purple-600 dark:text-purple-400' : 'text-blue-500' }}">Fecha de registro:</div>
                    <div class="md:col-span-2 text-sm text-zinc-900 dark:text-zinc-300">12-03-2021</div>
                </div>
                 <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-b border-zinc-50 dark:border-zinc-800 pb-4">
                    <div class="text-sm font-medium {{ $isRestaurant ? 'text-purple-600 dark:text-purple-400' : 'text-blue-500' }}">Email:</div>
                    <div class="md:col-span-2 text-sm text-zinc-900 dark:text-zinc-300">townsend@gmail.com</div>
                </div>
                 <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pb-4">
                    <div class="text-sm font-medium {{ $isRestaurant ? 'text-purple-600 dark:text-purple-400' : 'text-blue-500' }}">Contraseña:</div>
                    <div class="md:col-span-2 text-sm text-zinc-900 dark:text-zinc-300">******************</div>
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>
