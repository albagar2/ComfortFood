<div wire:poll.15s class="relative inline-flex group/badge">
    @php $total = $cancelledCount + $completedCount; @endphp

    @if($total > 0)
        <!-- Single Grey Badge -->
        <span
            class="absolute -top-7.5 -right-23 flex h-5 w-5 items-center justify-center rounded-full bg-emerald-500 text-[10px] font-bold text-white ring-1 ring-navy-light border border-white/10 z-20 cursor-help">
            {{ $total > 9 ? '9+' : $total }}
        </span>

        <!-- Accessible Tooltip on Hover/Tap -->
        <div
            class="absolute top-0 -right-24 mt-2 px-3 py-2 bg-zinc-900 text-white text-[11px] rounded-xl opacity-0 group-hover/badge:opacity-100 transition-opacity whitespace-nowrap z-50 pointer-events-none border border-white/10 shadow-2xl flex flex-col gap-1 min-w-[120px]">
            @if($cancelledCount > 0)
                <div class="flex items-center gap-2">
                    <div class="size-1.5 rounded-full bg-rose-500"></div>
                    <span>Cancelados: <strong class="text-rose-400">{{ $cancelledCount }}</strong></span>
                </div>
            @endif

            @if($completedCount > 0)
                <div class="flex items-center gap-2">
                    <div class="size-1.5 rounded-full bg-amber-500"></div>
                    <span>Valora al restaurante: <strong class="text-amber-400">{{  $completedCount }}</strong></span>
                </div>
            @endif

            <!-- Tooltip Arrow -->
            <div
                class="absolute bottom-full right-4 border-l-4 border-r-4 border-b-4 border-l-transparent border-r-transparent border-b-zinc-900">
            </div>
        </div>
    @endif
</div>