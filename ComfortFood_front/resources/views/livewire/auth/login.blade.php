<x-layouts::auth>
    <div class="flex w-full h-screen overflow-hidden bg-app-bg dark:bg-zinc-950">

        <!-- Form Column -->
        <div class="w-full md:w-1/2 h-full overflow-y-auto flex flex-col items-center px-6 md:px-12 lg:px-20 py-12 relative z-10">
            <div class="w-full max-w-[520px] bg-white dark:bg-zinc-900/50 backdrop-blur-sm p-8 md:p-12 rounded-[2.5rem] border border-zinc-200/50 dark:border-zinc-800/50 shadow-2xl shadow-zinc-200/20 dark:shadow-none transition-all duration-500">
                <div class="flex flex-col gap-10">
                    <!-- Logo & Header -->
                    <div class="space-y-6 text-center">
                        <a href="{{ route('home') }}" class="inline-block transition-transform hover:scale-105 active:scale-95 duration-300">
                            <img src="{{ asset('images/logo.png') }}" alt="ComfortFood" class="h-20 w-auto drop-shadow-sm">
                        </a>

                        <div class="space-y-2">
                            <h1 class="text-3xl font-black text-zinc-950 dark:text-white tracking-tight">
                                {{ __('Inicia sesión') }}
                            </h1>
                            <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">
                                {{ __('Bienvenido de nuevo a nuestra comunidad gastronómica.') }}
                            </p>
                        </div>
                    </div>

                    <x-auth-session-status class="text-center" :status="session('status')" />

                    <!-- Form -->
                    <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-8">
                        @csrf

                        <div class="grid grid-cols-1 gap-5">
                            <flux:input
                                name="email"
                                :label="__('Correo electrónico')"
                                :value="old('email')"
                                type="email"
                                required
                                autofocus
                                autocomplete="email"
                                placeholder="email@example.com"
                                class="!rounded-2xl" />

                            <div class="relative">
                                <flux:input
                                    name="password"
                                    :label="__('Contraseña')"
                                    type="password"
                                    required
                                    autocomplete="current-password"
                                    :placeholder="__('Contraseña')"
                                    viewable
                                    class="!rounded-2xl" />

                                @if (Route::has('password.request'))
                                    <flux:link class="absolute top-0 text-xs end-0 font-bold !text-indigo-600 dark:!text-indigo-400" :href="route('password.request')" wire:navigate>
                                        {{ __('¿Olvidaste tu contraseña?') }}
                                    </flux:link>
                                @endif
                            </div>

                            <flux:checkbox name="remember" :label="__('Recordarme')" :checked="old('remember')" class="text-xs" />
                        </div>

                        <div class="pt-4">
                            <flux:button variant="primary" type="submit" class="w-full py-4 !rounded-2xl !bg-zinc-950 hover:!bg-zinc-800 dark:!bg-white dark:!text-black dark:hover:!bg-zinc-200 font-black uppercase tracking-[0.15em] text-xs shadow-xl shadow-zinc-950/10 transition-all active:scale-[0.98]" data-test="login-button">
                                {{ __('Iniciar sesión') }}
                            </flux:button>
                        </div>
                    </form>

                    <!-- Register Link -->
                    <div class="pt-6 border-t border-zinc-100 dark:border-zinc-800 text-center">
                        <div class="flex flex-col gap-4">
                            <p class="text-xs font-bold text-zinc-500 uppercase tracking-widest">
                                {{ __('¿Aún no tienes cuenta?') }}
                            </p>
                            
                            <div x-data="{ open: false }" class="relative inline-block">
                                <button
                                    @click="open = !open"
                                    class="px-8 py-3 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-2xl text-sm font-black text-zinc-950 dark:text-white shadow-sm hover:shadow-md transition-all active:scale-95 flex items-center justify-center gap-2 mx-auto">
                                    {{ __('Regístrate ahora') }}
                                    <flux:icon.chevron-down class="size-4" />
                                </button>

                                <div
                                    x-show="open"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                    x-transition:leave="transition ease-in duration-100"
                                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                    x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                                    @click.outside="open = false"
                                    class="absolute left-1/2 -translate-x-1/2 mt-3 w-64 bg-white dark:bg-zinc-900 rounded-[1.5rem] shadow-2xl border border-zinc-200/50 dark:border-zinc-800/50 p-2 z-50 backdrop-blur-xl">
                                    
                                    <a
                                        href="{{ route('register', ['rol' => 'cliente']) }}"
                                        class="flex items-center gap-3 px-4 py-3 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 rounded-xl transition-colors group">
                                        <div class="size-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 group-hover:scale-110 transition-transform">
                                            👤
                                        </div>
                                        <div class="text-left">
                                            <p class="text-sm font-black text-zinc-950 dark:text-white">Cliente</p>
                                            <p class="text-[10px] font-medium text-zinc-500">Busco comida local</p>
                                        </div>
                                    </a>

                                    <a
                                        href="{{ route('register', ['rol' => 'restaurante']) }}"
                                        class="flex items-center gap-3 px-4 py-3 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 rounded-xl transition-colors group">
                                        <div class="size-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400 group-hover:scale-110 transition-transform">
                                            🏪
                                        </div>
                                        <div class="text-left">
                                            <p class="text-sm font-black text-zinc-950 dark:text-white">Restaurante</p>
                                            <p class="text-[10px] font-medium text-zinc-500">Quiero ofrecer mi menú</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Image Column -->
        <div class="hidden md:block md:w-1/2 relative h-full">
            <img
                src="{{ asset('images/img.png') }}"
                alt="Imagen Login"
                class="absolute inset-0 w-full h-full object-cover">
            <!-- Decorative overlay -->
            <div class="absolute inset-0 bg-gradient-to-r from-app-bg/80 via-transparent to-transparent dark:from-zinc-950/80"></div>
        </div>

    </div>
</x-layouts::auth>