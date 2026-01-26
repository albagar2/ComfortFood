<x-layouts.mockup title="Detalles del pedido">
    <div class="flex items-center gap-4 mb-6">
        <h1 class="text-xl font-bold">Lista pedidos</h1>
        <div class="flex-1"></div>
        <div class="relative">
             <input type="text" placeholder="Buscar" class="pl-8 pr-4 py-2 border border-zinc-300 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
             <svg class="w-4 h-4 absolute left-2.5 top-3 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
    </div>

    <!-- Filter chips -->
    <div class="flex gap-2 mb-6 overflow-x-auto pb-2">
        @foreach(['#345', '#346', '#347', '#348', '#349', '#354', '#355', '#352', '#353', '#354'] as $tag)
            <span class="px-3 py-1 rounded-full border border-zinc-200 text-xs text-zinc-600 bg-white whitespace-nowrap">{{ $tag }} <span class="ml-1 text-zinc-400 cursor-pointer">×</span></span>
        @endforeach
    </div>

    <div class="flex items-center gap-2 mb-6">
        <a href="{{ route('orders.history') }}" class="text-zinc-500 hover:text-zinc-700">
             <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
        </a>
        <h2 class="text-lg font-bold">Detalles del pedido</h2>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-zinc-200 p-8 max-w-4xl mx-auto relative">
        <div class="flex justify-between items-start mb-8">
            <h3 class="text-xl font-bold">Pedido #348</h3>
            <div class="flex flex-col items-end">
                <span class="text-sm text-zinc-500">03 Feb 2023, 08:28 PM (hace 3 minutos)</span>
                <span class="mt-1 bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-md flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span> Pendiente
                </span>
            </div>
        </div>

        <div class="flex flex-col md:flex-row gap-12">
            <!-- Left Column: Info -->
            <div class="w-full md:w-1/3 flex flex-col items-center text-center">
                 <div class="size-16 bg-zinc-200 rounded-lg mb-4 flex items-center justify-center">
                    <svg class="w-8 h-8 text-zinc-400" fill="currentColor" viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
                 </div>
                 <p class="text-sm text-zinc-500 mb-1">realizado por Maria J.</p>
                 <p class="text-lg font-bold mb-4">65 59 77 789</p>

                 <div class="w-full text-left space-y-4 text-sm">
                     <div class="border-t border-zinc-100 pt-4">
                         <p class="text-zinc-500 mb-1">Entrega a domicilio : Calle Isaac Peral 4</p>
                     </div>
                     <div class="border-t border-zinc-100 pt-4">
                         <p class="text-zinc-500 mb-1">Tiempo de entrega estimado : 30 minutos</p>
                     </div>
                     <div class="border-t border-zinc-100 pt-4">
                         <p class="text-zinc-500 mb-1">Método de pago : Tarjeta</p>
                     </div>
                     <div class="border-t border-zinc-100 pt-4">
                         <p class="text-zinc-500 mb-1">Observación cliente: sin cebolla, extra salsa</p>
                     </div>
                 </div>

                 <div class="mt-8 border border-zinc-200 rounded-lg p-4 w-full">
                     <div class="flex justify-center mb-2">
                         <div class="flex text-yellow-400">
                             ★★★★★
                         </div>
                     </div>
                     <p class="text-center font-bold mb-1">Comentario opcional</p>
                     <p class="text-center text-xs text-zinc-400">Fecha - Nombre cliente</p>
                 </div>
            </div>

            <!-- Right Column: Items -->
            <div class="w-full md:w-2/3 border-l border-zinc-100 md:pl-12">
                <div class="space-y-6">
                    <!-- Item 1 -->
                    <div class="flex gap-4">
                        <div class="size-12 bg-zinc-200 rounded-md flex-shrink-0 flex items-center justify-center">
                            <svg class="w-6 h-6 text-zinc-400" fill="currentColor" viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
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
                        <div class="size-12 bg-zinc-200 rounded-md flex-shrink-0 flex items-center justify-center">
                            <svg class="w-6 h-6 text-zinc-400" fill="currentColor" viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
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
                        <div class="size-12 bg-zinc-200 rounded-md flex-shrink-0 flex items-center justify-center">
                            <svg class="w-6 h-6 text-zinc-400" fill="currentColor" viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
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

                    <div class="text-right pt-4">
                        <a href="#" class="text-xs text-zinc-400 underline">+2 artículos, ver todo</a>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-zinc-200 flex justify-between items-center">
                    <span class="font-bold text-lg">Total: 21.60€</span>
                    <span class="text-sm text-zinc-500">cantidad: 4</span>
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
</x-layouts.mockup>
