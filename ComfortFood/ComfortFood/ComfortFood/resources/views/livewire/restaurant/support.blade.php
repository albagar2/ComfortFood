<div class="p-6">
    <div class="max-w-7xl mx-auto space-y-12">
        <!-- Header -->
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}" wire:navigate class="p-2 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-full transition-colors">
                <flux:icon.arrow-left class="size-6" />
            </a>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Ayuda y asistencia</h1>
        </div>

        <!-- Search Section -->
        <div class="flex flex-col items-center justify-center space-y-8 p-4 md:p-12 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-3xl shadow-sm">
            <h2 class="text-3xl font-medium text-zinc-900 dark:text-white">¿Cómo podemos ayudarte?</h2>
            <div class="w-full max-w-xl px-6">
                <flux:input 
                    wire:model.live.debounce.300ms="search" 
                    icon="magnifying-glass" 
                    placeholder="Buscar" 
                    class="w-full !rounded-xl !py-3"
                />
            </div>
        </div>

        <!-- FAQs Section -->
        <div class="space-y-6">
            <h3 class="text-xl font-bold text-zinc-900 dark:text-white">Preguntas más frecuentes</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach([
                    '¿Por qué mi restaurante no aparece en destacados o primeros resultados?',
                    '¿Cómo cambio o actualizo mi plan de membresía y métodos de pago?',
                    '¿Cómo puedo actualizar el horario o cerrar temporalmente el restaurante?',
                    '¿Qué hago si no puedo entrar o la app falla?',
                    '¿Cómo gestiono los pedidos nuevos o pendientes desde el panel?',
                    '¿Dónde puedo ver y responder las reseñas que dejan los clientes?'
                ] as $faq)
                    <button class="flex items-center justify-start text-left p-6 bg-white dark:bg-zinc-900 border border-zinc-100 dark:border-zinc-800 rounded-xl hover:border-zinc-300 dark:hover:border-zinc-700 hover:shadow-md transition-all group">
                        <span class="text-sm font-semibold text-zinc-800 dark:text-zinc-200 group-hover:text-zinc-950 dark:group-hover:text-white">{{ $faq }}</span>
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Contact Section -->
        <div class="space-y-6">
            <h3 class="text-xl font-bold text-zinc-900 dark:text-white">Contacto directo</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Email -->
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-8 flex flex-col items-center text-center space-y-4 shadow-sm">
                    <div class="text-sm font-bold text-zinc-900 dark:text-white">restaurantes@comfortfood.com</div>
                    <p class="text-sm text-zinc-500">Envíanos un email y te responderemos en menos de 24 h.</p>
                    <flux:button variant="outline" class="w-full mt-4 !rounded-xl">Enviar mensaje</flux:button>
                </div>

                <!-- Chat -->
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-8 flex flex-col items-center text-center space-y-4 shadow-sm">
                    <div class="text-sm font-bold text-zinc-900 dark:text-white">Chat en vivo</div>
                    <p class="text-sm text-zinc-500">Habla con un agente disponible</p>
                    <flux:button variant="outline" class="w-full mt-4 !rounded-xl">Iniciar chat</flux:button>
                </div>

                <!-- Phone -->
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-8 flex flex-col items-center text-center space-y-4 shadow-sm">
                    <div class="text-sm font-bold text-zinc-900 dark:text-white">957 60 00 00</div>
                    <p class="text-sm text-zinc-500">Lunes a viernes, 9:00-15:00.</p>
                    <flux:button variant="outline" class="w-full mt-4 !rounded-xl">Llamar</flux:button>
                </div>
            </div>
        </div>
    </div>
</div>
