<div wire:poll.10s>
    @if($count > 0)
        <span
            class="ml-20 absolute inline-flex items-center justify-center px-3 py-0.5 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">
            {{ $count }}
        </span>
    @endif
</div>