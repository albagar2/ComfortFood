<x-layouts::auth>
    <div class="flex flex-col items-center text-center mx-auto min-h-[60vh] justify-center">
        <!-- Icon/Decoration -->
        <div class="mb-8 p-4 bg-orange-100 dark:bg-orange-900/30 rounded-3xl">
            <flux:icon.envelope class="size-16 text-pastel-orange shadow-sm" />
        </div>

        <h2 class="text-3xl font-extrabold text-[#2d365e] dark:text-white mb-2 tracking-tight">
            {{ __('¡Casi listo!') }}
        </h2>

        <flux:text class="text-lg text-zinc-600 dark:text-zinc-400 mb-8 max-w-sm leading-relaxed font-medium">
            {{ __('Gracias por unirte a ComfortFood. Para empezar, por favor verifica tu correo haciendo clic en el enlace que te acabamos de enviar.') }}
        </flux:text>

        @if (session('status') == 'verification-link-sent')
            <div
                class="mb-8 w-full p-4 bg-green-50 dark:bg-green-900/20 border border-green-100 dark:border-green-900/30 rounded-2xl">
                <flux:text class="text-sm font-bold text-green-600 dark:text-green-400">
                    {{ __('Se ha enviado un nuevo enlace de verificación a tu dirección de correo.') }}
                </flux:text>
            </div>
        @endif

        <div class="flex flex-col w-full gap-4">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <flux:button type="submit" variant="primary"
                    class="!bg-[#3b4a81] !h-14 !text-lg !font-bold !rounded-2xl !shadow-xl !shadow-blue-900/20 hover:!bg-[#2d365e] hover:!-translate-y-0.5 transition-all w-full">
                    {{ __('Reenviar correo de verificación') }}
                </flux:button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <flux:button variant="ghost" type="submit"
                    class="w-full text-zinc-500 font-bold hover:text-navy-dark mt-2">
                    {{ __('Cerrar sesión') }}
                </flux:button>
            </form>
        </div>
    </div>
</x-layouts::auth>