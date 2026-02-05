<x-layouts::auth>
    <div class="flex w-full h-screen overflow-hidden bg-app-bg dark:bg-zinc-950"
        x-data="{ rol: @js(request('rol') ?? 'cliente'), loading: false }">

        <!-- Loading Overlay -->
        <div x-show="loading" x-transition.opacity
            class="fixed inset-0 z-50 flex items-center justify-center bg-white/80 dark:bg-zinc-950/80 backdrop-blur-sm">
            <div class="flex flex-col items-center gap-4">
                <div
                    class="size-12 rounded-full border-4 border-zinc-200 dark:border-zinc-700 border-t-pastel-orange animate-spin">
                </div>
                <p class="text-sm font-bold text-zinc-600 dark:text-zinc-400 animate-pulse">
                    Preparando tu espacio...
                </p>
            </div>
        </div>

        <!-- Form Column -->
        <div
            class="w-full md:w-1/2 h-full overflow-y-auto flex flex-col items-center px-6 md:px-12 lg:px-20 py-12 relative z-10">
            <div
                class="w-full max-w-[520px] bg-white dark:bg-zinc-900/50 backdrop-blur-sm p-8 md:p-12 rounded-[2.5rem] border border-zinc-200/50 dark:border-zinc-800/50 shadow-2xl shadow-zinc-200/20 dark:shadow-none transition-all duration-500">
                <div class="flex flex-col gap-10">
                    <!-- Logo & Header -->
                    <div class="space-y-6 text-center">
                        <a href="{{ route('home') }}"
                            class="inline-block transition-transform hover:scale-105 active:scale-95 duration-300">
                            <img src="{{ asset('images/logo.png') }}" alt="ComfortFood"
                                class="h-20 w-auto drop-shadow-sm">
                        </a>

                        <div class="space-y-2">
                            <h1 class="text-3xl font-black text-zinc-950 dark:text-white tracking-tight"
                                x-text="rol === 'restaurante' ? 'Registra tu Restaurante' : 'Crea tu cuenta de Cliente'">
                            </h1>
                            <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Estás a pocos pasos de
                                unirte a la mayor comunidad gastronómica local.</p>
                        </div>
                    </div>

                    <x-auth-session-status class="text-center" :status="session('status')" />

                    <!-- Form -->
                    <form x-on:submit="loading = true" method="POST" action="{{ route('register') }}"
                        class="flex flex-col gap-8">
                        @csrf
                        <input type="hidden" name="rol" x-bind:value="rol">

                        <!-- Section: Identity -->
                        <section class="space-y-5">
                            <div class="flex items-center gap-3">
                                <div class="size-1 bg-pastel-orange rounded-full"></div>
                                <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-zinc-400">Datos
                                    Principales</h3>
                            </div>

                            <div class="grid grid-cols-1 gap-5">
                                <flux:input name="nombre_completo"
                                    x-bind:label="rol === 'restaurante' ? 'Nombre del Restaurante' : 'Nombre completo'"
                                    :value="old('nombre_completo')" type="text" required autofocus
                                    x-bind:placeholder="rol === 'restaurante' ? 'Ej. El Rincón del Sabor' : 'Ej. Juan Pérez'"
                                    class="!rounded-2xl" />

                                <div class="grid grid-cols-1 gap-5">
                                    <flux:input name="email" :label="__('Correo electrónico')" :value="old('email')"
                                        type="email" required autocomplete="email" placeholder="email@example.com"
                                        class="!rounded-2xl" />
                                </div>
                            </div>
                        </section>

                        <!-- Section: Security -->
                        <section class="space-y-5">
                            <div class="flex items-center gap-3">
                                <div class="size-1 bg-pastel-orange rounded-full"></div>
                                <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-zinc-400">Seguridad
                                </h3>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <flux:input name="password" :label="__('Contraseña')" type="password" required viewable
                                    placeholder="••••••••" class="!rounded-2xl" />
                                <flux:input name="password_confirmation" :label="__('Confirmar contraseña')"
                                    type="password" required viewable placeholder="••••••••" class="!rounded-2xl" />
                            </div>
                        </section>

                        <!-- Section: Client Only -->
                        <section x-show="rol === 'cliente'" x-transition class="space-y-5">
                            <div class="flex items-center gap-3">
                                <div class="size-1 bg-pastel-orange rounded-full"></div>
                                <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-zinc-400">Datos
                                    de contacto</h3>
                            </div>
                            <flux:input name="telefono_cliente" :label="__('Teléfono')" :value="old('telefono_cliente')"
                                type="tel" placeholder="+34 600 000 000" class="!rounded-2xl" />

                            <flux:input name="direccion_cliente" :label="__('Dirección física')"
                                :value="old('direccion_cliente')" type="text" placeholder="Calle, Número, Ciudad..."
                                class="!rounded-2xl" />
                        </section>

                        <!-- Section: Restaurant Only -->
                        <section x-show="rol === 'restaurante'" x-transition class="space-y-5">
                            <div class="flex items-center gap-3">
                                <div class="size-1 bg-pastel-orange rounded-full"></div>
                                <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-zinc-400">Datos
                                    de contacto</h3>
                            </div>
                            <flux:input name="telefono" :label="__('Teléfono')" :value="old('telefono')" type="tel"
                                x-bind:required="rol === 'restaurante'" placeholder="+34 600 000 000"
                                class="!rounded-2xl" />

                            <flux:input name="direccion" :label="__('Dirección física')" :value="old('direccion')"
                                type="text" x-bind:required="rol === 'restaurante'"
                                placeholder="Calle, Número, Ciudad..." class="!rounded-2xl" />

                            <div class="flex items-center gap-3">
                                <div class="size-1 bg-emerald-500 rounded-full"></div>
                                <h3
                                    class="text-[10px] font-black uppercase tracking-[0.2em] text-emerald-600 dark:text-emerald-400">
                                    Detalles del Negocio</h3>
                            </div>

                            <div class="grid grid-cols-1 gap-5">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <flux:input name="tipo_cocina" :label="__('Tipo de cocina')"
                                        :value="old('tipo_cocina')" type="text" x-bind:required="rol === 'restaurante'"
                                        placeholder="Ej. Mediterránea" class="!rounded-2xl" />
                                    <flux:input name="NIF" :label="__('NIF')" :value="old('NIF')" type="text"
                                        x-bind:required="rol === 'restaurante'" placeholder="00000000X"
                                        class="!rounded-2xl" />
                                </div>
                                <flux:textarea name="descripcion" :label="__('Descripción')" :value="old('descripcion')"
                                    x-bind:required="rol === 'restaurante'"
                                    placeholder="Cuéntanos un poco sobre tu pasión por la cocina..."
                                    class="!rounded-2xl" />
                                <flux:error name="descripcion" />
                            </div>
                        </section>

                        <div class="pt-4">
                            <flux:button type="submit" variant="primary"
                                class="w-full py-4 !rounded-2xl !bg-zinc-950 hover:!bg-zinc-800 dark:!bg-white dark:!text-black dark:hover:!bg-zinc-200 font-black uppercase tracking-[0.15em] text-xs shadow-xl shadow-zinc-950/10 transition-all active:scale-[0.98]">
                                {{ __('Completar Registro') }}
                            </flux:button>
                        </div>
                    </form>

                    <!-- Login Link -->
                    <div class="pt-6 border-t border-zinc-100 dark:border-zinc-800 text-center">
                        <p class="text-xs font-bold text-zinc-500 uppercase tracking-widest">
                            {{ __('¿Ya eres parte de ComfortFood?') }}
                            <flux:link :href="route('login')" wire:navigate
                                class="!text-indigo-600 dark:!text-indigo-400 font-black ml-1">
                                {{ __('Inicia sesión aquí') }}
                            </flux:link>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Image Column -->
        <div class="hidden md:block md:w-1/2 relative h-full">
            <img src="{{ asset('images/img.png') }}" alt="Imagen Registro"
                class="absolute inset-0 w-full h-full object-cover">
            <!-- Decorative overlay -->
            <div
                class="absolute inset-0 bg-gradient-to-r from-app-bg/80 via-transparent to-transparent dark:from-zinc-950/80">
            </div>
        </div>
    </div>
</x-layouts::auth>