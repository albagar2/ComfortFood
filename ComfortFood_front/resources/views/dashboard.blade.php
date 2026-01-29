<x-layouts::app :title="__('ComfortFood')">
    @if(auth()->user()->isAdmin())
        <div class="p-6">
            <livewire:admin.user-list />
        </div>
    @elseif(auth()->user()->isRestaurante())
        <livewire:restaurant-dashboard />
    @else
        <livewire:client-dashboard />
    @endif
</x-layouts::app>