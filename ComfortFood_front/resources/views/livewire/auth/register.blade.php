<x-layouts::auth>
    <div class="flex w-full h-screen overflow-hidden bg-[#121212]"
         x-data="{ rol: @js(request('rol') ?? 'cliente') }">

        <div class="w-full md:w-1/2 h-full overflow-y-auto flex flex-col items-center px-8 md:px-16 lg:px-24 py-12">
            <div class="w-full max-w-[450px] flex flex-col gap-4">

                <img src="{{ asset('app-touch-icon.png') }}" alt="ComfortFood" class="h-16 w-auto mx-auto mb-2">

                <x-auth-header
                    :title="__('Crea tu cuenta')"
                    :description="__('Introduce tus datos para registrarte en Comfort Food')"
                    class="text-center"
                />

                <x-auth-session-status class="text-center mb-4" :status="session('status')"/>

                <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-6">
                    @csrf
                    <input type="hidden" name="rol" :value="rol">


                    <!-- Campos solo para Restaurante -->
                    <div x-show="rol === 'restaurante'" class="flex flex-col gap-4" x-transition>
                        <!-- Nombre completo -->
                        <flux:input
                            name="nombre_completo"
                            label="Nombre de la empresa"
                            :value="old('nombre_completo')"
                            type="text"
                            required
                            autofocus
                            placeholder="La Buena Mesa Italiana"
                        />


                        <!-- Dirección -->
                        <flux:input
                            name="direccion"
                            :label="__('Dirección')"
                            :value="old('direccion')"
                            type="text"
                            placeholder="Calle Ejemplo 123"
                        />
                        <!-- Teléfono -->
                        <flux:input
                            name="telefono"
                            :label="__('Teléfono')"
                            :value="old('telefono')"
                            type="tel"
                            placeholder="+34 600 123 456"
                        />
                    </div>

                    <!-- Campos solo para Cliente -->
                    <div x-show="rol === 'cliente'" class="flex flex-col gap-4" x-transition>
                        <!-- Nombre completo -->
                        <flux:input
                            name="nombre_completo"
                            label="Nombre completo"
                            :value="old('nombre_completo')"
                            type="text"
                            required
                            autofocus
                            placeholder="Juan Pérez"
                        />


                        <!-- Dirección -->
                        <flux:input
                            name="direccion"
                            :label="__('Dirección')"
                            :value="old('direccion')"
                            type="text"
                            placeholder="Calle Ejemplo 123"
                        />
                        <!-- Teléfono -->
                        <flux:input
                            name="telefono"
                            :label="__('Teléfono')"
                            :value="old('telefono')"
                            type="tel"
                            placeholder="+34 600 123 456"
                        />
                    </div>

                    <!-- Email -->
                    <flux:input
                        name="email"
                        :label="__('Correo electrónico')"
                        :value="old('email')"
                        type="email"
                        required
                        autocomplete="email"
                        placeholder="email@example.com"
                    />

                    <!-- Contraseña -->
                    <flux:input
                        name="password"
                        :label="__('Contraseña')"
                        type="password"
                        required
                        viewable
                        placeholder="••••••••"
                    />

                    <!-- Confirmar contraseña -->
                    <flux:input
                        name="password_confirmation"
                        :label="__('Confirmar contraseña')"
                        type="password"
                        required
                        viewable
                        placeholder="••••••••"
                    />

                    <!-- Campos solo para Restaurante -->
                    <div x-show="rol === 'restaurante'" class="flex flex-col gap-4" x-transition>
                        <flux:input
                            name="tipo_cocina"
                            :label="__('Tipo de cocina')"
                            :value="old('tipo_cocina')"
                            type="text"
                            required
                            placeholder="Italiana, Mexicana..."
                        />

                        <flux:input
                            name="NIF"
                            :label="__('NIF')"
                            :value="old('NIF')"
                            type="text"
                            required
                            placeholder="X1234567Y"
                        />

                        <flux:textarea
                            name="descripcion"
                            :label="__('Descripción del restaurante')"
                            :value="old('descripcion')"
                            placeholder="Breve descripción de tu restaurante"
                        />
                    </div>


                    <div class="pt-2">
                        <flux:button type="submit" variant="primary" class="w-full py-3">
                            {{ __('Crear cuenta') }}
                        </flux:button>
                    </div>
                </form>

                <div class="text-center text-sm text-zinc-400">
                    <span>{{ __('¿Ya tienes una cuenta?') }}</span>
                    <flux:link :href="route('login')" wire:navigate class="text-white font-semibold ml-1">
                        {{ __('Inicia sesión') }}
                    </flux:link>
                </div>
            </div>
        </div>

        <div class="hidden md:block md:w-1/2 relative">
            <img
                src="{{ asset('img.png') }}"
                alt="Imagen Registro"
                class="absolute inset-0 w-full h-full object-cover"
            >
            <div class="absolute inset-0 bg-black/20"></div>
        </div>

    </div>
</x-layouts::auth>
