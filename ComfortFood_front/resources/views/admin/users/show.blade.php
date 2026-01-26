<x-layouts.mockup title="Detalle de usuario">
    <div class="flex items-center gap-4 mb-6">
         <a href="{{ route('admin.users.index') }}" class="text-zinc-500 hover:text-zinc-700">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
        </a>
        <h1 class="text-xl font-bold">Detalle de usuario</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-zinc-200 overflow-hidden max-w-4xl mx-auto">
        <div class="flex items-center justify-between px-8 py-6 border-b border-zinc-100">
             <span class="bg-green-100 text-green-800 text-xs font-medium px-3 py-1 rounded-full">Activo</span>
             <div class="text-center flex-1">
                 <h2 class="text-lg font-bold">Patrick Townsend</h2>
             </div>
             <button class="bg-blue-600 text-white px-4 py-1.5 rounded-md text-sm hover:bg-blue-700 flex items-center gap-2">
                 <span class="w-1.5 h-1.5 rounded-full bg-white"></span> editar
             </button>
        </div>

        <div class="p-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-b border-zinc-50 pb-4">
                <div class="text-sm font-medium text-blue-500">Nombre completo:</div>
                <div class="md:col-span-2 text-sm text-zinc-900">Patrick Townsend</div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-b border-zinc-50 pb-4">
                <div class="text-sm font-medium text-blue-500">DNI:</div>
                <div class="md:col-span-2 text-sm text-zinc-900">50877893 M</div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-b border-zinc-50 pb-4">
                <div class="text-sm font-medium text-blue-500">Dirección:</div>
                <div class="md:col-span-2 text-sm text-zinc-900">Cuesta Molino nº70</div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-b border-zinc-50 pb-4">
                <div class="text-sm font-medium text-blue-500">Teléfono:</div>
                <div class="md:col-span-2 text-sm text-zinc-900">65 55 55 555</div>
            </div>
             <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-b border-zinc-50 pb-4">
                <div class="text-sm font-medium text-blue-500">Tipo de usuario</div>
                <div class="md:col-span-2 text-sm text-zinc-900">Cliente</div>
            </div>
             <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-b border-zinc-50 pb-4">
                <div class="text-sm font-medium text-blue-500">Fecha de registro</div>
                <div class="md:col-span-2 text-sm text-zinc-900">12-03-2021</div>
            </div>
             <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-b border-zinc-50 pb-4">
                <div class="text-sm font-medium text-blue-500">Email:</div>
                <div class="md:col-span-2 text-sm text-zinc-900">townsend@gmail.com</div>
            </div>
             <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pb-4">
                <div class="text-sm font-medium text-blue-500">Contraseña:</div>
                <div class="md:col-span-2 text-sm text-zinc-900">******************</div>
            </div>
        </div>
    </div>
</x-layouts.mockup>
