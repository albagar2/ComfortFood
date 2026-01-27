<x-layouts::auth>
    <div class="flex w-full h-screen overflow-hidden bg-white dark:bg-[#121212]">

        <div class="w-full md:w-1/2 h-full overflow-y-auto flex flex-col items-center px-8 md:px-16 lg:px-24 py-12">
            <div class="w-full max-w-[450px] flex flex-col gap-4">

                <img src="{{ asset('app-touch-icon.png') }}" alt="ComfortFood" class="h-20 w-auto mx-auto mb-2">

                <x-auth-header
                    class="text-center"
                    :title="__('Inicio de sesión')"
                    :description="__('Ingrese sus credenciales para acceder a su cuenta')" />

                <x-auth-session-status class="text-center mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
                    @csrf

                    <flux:input
                        name="email"
                        :label="__('Correo electrónico')"
                        :value="old('email')"
                        type="email"
                        required
                        autofocus
                        autocomplete="email"
                        placeholder="email@example.com" />

                    <div class="relative">
                        <flux:input
                            name="password"
                            :label="__('Contraseña')"
                            type="password"
                            required
                            autocomplete="current-password"
                            :placeholder="__('Contraseña')"
                            viewable />

                        @if (Route::has('password.request'))
                            <flux:link class="absolute top-0 text-xs end-0" :href="route('password.request')" wire:navigate>
                                {{ __('¿Has olvidado tu contraseña?') }}
                            </flux:link>
                        @endif
                    </div>

                    <flux:checkbox name="remember" :label="__('Recuérdame')" :checked="old('remember')" />

                    <flux:button variant="primary" type="submit" class="w-full py-3" data-test="login-button">
                        {{ __('Iniciar sesión') }}
                    </flux:button>
                </form>

                @if (Route::has('register'))
                    <div class="mt-4 text-sm text-center text-zinc-600 dark:text-zinc-400">
                        <span>{{ __('¿No tienes una cuenta?') }}</span>
                        <div x-data="{ open: false }" class="relative inline-block">

                            <button
                                @click="open = !open"
                                class="font-bold text-blue-600">
                                Regístrate
                            </button>

                            <div
                                x-show="open"
                                @click.outside="open = false"
                                class="absolute mt-2 w-48 bg-white dark:bg-[#1a1a1a] rounded-lg shadow-lg p-2 z-50">

                                <a
                                    href="{{ route('register', ['rol' => 'cliente']) }}"
                                    class="block px-4 py-2 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded">
                                    👤 Cliente
                                </a>

                                <a
                                    href="{{ route('register', ['rol' => 'restaurante']) }}"
                                    class="block px-4 py-2 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded">
                                    🏪 Restaurante
                                </a>

                            </div>
                        </div>

                    </div>
                @endif
            </div>
        </div>

        <div class="hidden md:block md:w-1/2 relative">
            <img
                src="{{ asset('img.png') }}"
                alt="Imagen Login"
                class="absolute inset-0 w-full h-full object-cover">
            <div class="absolute inset-0 bg-black/5 dark:bg-black/20"></div>
        </div>

    </div>
</x-layouts::auth>
