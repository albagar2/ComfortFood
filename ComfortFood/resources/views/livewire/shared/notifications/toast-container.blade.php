<div id="toast-container" class="fixed top-5 right-5 z-[100] flex flex-col gap-3 w-full max-w-sm pointer-events-none">
    @foreach ($toasts as $toast)
        <div x-data="{ show: false }"
            x-init="setTimeout(() => show = true, 50); if (!{{ $toast['sticky'] ? 'true' : 'false' }}) { setTimeout(() => { show = false; setTimeout(() => $wire.removeToast('{{ $toast['id'] }}'), 500) }, 5000) }"
            x-show="show" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="translate-x-full opacity-0" x-transition:enter-end="translate-x-0 opacity-100"
            x-transition:leave="transition ease-in duration-300" x-transition:leave-start="translate-x-0 opacity-100"
            x-transition:leave-end="translate-x-full opacity-0" class="pointer-events-auto flex items-center p-4 rounded-2xl shadow-2xl backdrop-blur-md border 
                        @if($toast['type'] === 'success') bg-emerald-500/90 border-emerald-400/50 text-white 
                        @elseif($toast['type'] === 'error') bg-rose-500/90 border-rose-400/50 text-white 
                        @else bg-navy-dark/90 border-slate-600/50 text-white @endif">
            <div
                class="flex-shrink-0 p-2 rounded-xl @if($toast['type'] === 'success') bg-white/20 @elseif($toast['type'] === 'error') bg-white/20 @else bg-white/10 @endif">
                <flux:icon :name="$toast['icon']" size="m" />
            </div>
            <div class="ms-3 text-sm font-medium leading-relaxed">
                {{ $toast['message'] }}
            </div>
            <button type="button" @click="show = false; setTimeout(() => $wire.removeToast('{{ $toast['id'] }}'), 500)"
                class="ms-auto -mx-1.5 -my-1.5 p-1.5 inline-flex items-center justify-center h-8 w-8 text-white/70 hover:text-white hover:bg-white/10 rounded-lg transition-colors">
                <flux:icon name="x-mark" size="s" />
            </button>
        </div>
    @endforeach
</div>