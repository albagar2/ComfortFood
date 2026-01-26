<x-layouts.customer_mockup title="Detalles del pedido">
    <div class="flex items-center gap-4 py-6">
         <div class="relative w-full max-w-sm">
             <input type="text" placeholder="Buscar" class="w-full pl-8 pr-4 py-2 border border-zinc-200 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 shadow-sm">
             <svg class="w-4 h-4 absolute left-2.5 top-3 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
    </div>
    
    <div class="mb-4">
        <h2 class="text-sm font-medium text-zinc-500">Detalles del pedido</h2>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-zinc-200 p-8">
        <div class="flex items-center mb-6">
             <a href="{{ route('customer.orders.history') }}" class="text-zinc-500 hover:text-zinc-700 mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
            </a>
            <h1 class="text-xl font-bold">Restaurante</h1>
            <div class="flex-1"></div>
            <div class="text-sm text-zinc-500">10 Feb 2023, 08:28 PM (hace 3 minutos)</div>
            <span class="ml-4 bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full flex items-center gap-1">
                 <span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span> Pendiente
            </span>
        </div>

        <div class="flex flex-col md:flex-row gap-8">
            <div class="flex-1 flex flex-col items-center justify-center text-center p-8 bg-zinc-50 rounded-lg">
                <div class="size-24 bg-zinc-200 rounded-xl mb-4 flex items-center justify-center">
                    <svg class="w-10 h-10 text-zinc-400" fill="currentColor" viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
                </div>
                <h3 class="text-lg font-bold mb-2">Datos del Restaurante</h3>
                <h3 class="text-lg font-bold mb-2">Descripcion del Menu</h3>
                <h3 class="text-lg font-bold">Propiedades Nutricionales</h3>
            </div>

            <div class="w-full md:w-1/2 space-y-6">
                 <!-- Item 1 -->
                <div class="flex gap-4">
                    <div class="size-10 bg-zinc-200 rounded-md flex-shrink-0 flex items-center justify-center">
                        <svg class="w-5 h-5 text-zinc-400" fill="currentColor" viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-sm">Verduras Mix</h4>
                         <p class="text-xs text-zinc-500">Versión frita con huevo</p>
                        <div class="flex justify-between items-center mt-1">
                            <span class="font-bold text-sm">5.50€</span>
                            <span class="text-xs text-zinc-500">cantidad: 1</span>
                        </div>
                    </div>
                </div>
                 <!-- Item 2 -->
                <div class="flex gap-4">
                    <div class="size-10 bg-zinc-200 rounded-md flex-shrink-0 flex items-center justify-center">
                        <svg class="w-5 h-5 text-zinc-400" fill="currentColor" viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-sm">Noodles</h4>
                         <p class="text-xs text-zinc-500">Noodles con pimientos frescos</p>
                        <div class="flex justify-between items-center mt-1">
                            <span class="font-bold text-sm">4.50€</span>
                            <span class="text-xs text-zinc-500">cantidad: 1</span>
                        </div>
                    </div>
                </div>
                 <!-- Item 3 -->
                <div class="flex gap-4">
                    <div class="size-10 bg-zinc-200 rounded-md flex-shrink-0 flex items-center justify-center">
                         <svg class="w-5 h-5 text-zinc-400" fill="currentColor" viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-sm">Pasta</h4>
                         <p class="text-xs text-zinc-500">Pasta carbonara</p>
                        <div class="flex justify-between items-center mt-1">
                            <span class="font-bold text-sm">5.80€</span>
                            <span class="text-xs text-zinc-500">cantidad: 2</span>
                        </div>
                    </div>
                </div>
                 <!-- Item 4 -->
                <div class="flex gap-4">
                    <div class="size-10 bg-zinc-200 rounded-md flex-shrink-0 flex items-center justify-center">
                        <svg class="w-5 h-5 text-zinc-400" fill="currentColor" viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-sm">Postre</h4>
                         <p class="text-xs text-zinc-500">Tarta mascarpone</p>
                        <div class="flex justify-between items-center mt-1">
                            <span class="font-bold text-sm">5.80€</span>
                            <span class="text-xs text-zinc-500">cantidad: 1</span>
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-zinc-200 flex justify-between items-center">
                    <span class="font-bold text-lg">Total: 19.80€</span>
                    <span class="text-sm text-zinc-500">cantidad: 5</span>
                </div>
            </div>
        </div>

         <div class="flex justify-center gap-4 mt-8">
            <button class="size-12 flex items-center justify-center rounded-lg border border-zinc-300 text-zinc-400 hover:bg-zinc-50 hover:text-red-500">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
            <button class="size-12 flex items-center justify-center rounded-lg border border-zinc-300 text-zinc-400 hover:bg-zinc-50 hover:text-green-500">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                </svg>
            </button>
        </div>
    </div>
</x-layouts.customer_mockup>
