<div>
    @if($count > 0)
        <span
            class="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-pastel-orange text-[10px] font-bold text-white ring-2 ring-navy-dark">
            {{ $count > 9 ? '9+' : $count }}
        </span>
    @endif
</div>