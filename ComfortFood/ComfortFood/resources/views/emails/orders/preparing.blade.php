@component('mail::message')

<div style="text-align: center;">
    <img src="{{ $message->embed(public_path('images/logo.png')) }}" alt="Logo" width="50" height="50">
</div>

# Hola {{ $order->cliente->user->nombre_completo ?? 'Cliente' }}


Tu pedido **#{{ $order->id_pedido }}** ya esta en cocina y nuestro equipo esta preparando todo con cuidado.

@component('mail::panel')
**Tiempo estimado:** {{ $estimatedTime ?? '15-20 min' }}
@endcomponent

@component('mail::button', ['url' => $orderUrl ?? url('/orders/details/' . $order->id_pedido)])
Ver pedido
@endcomponent

Gracias por confiar en ComfortFood.
@endcomponent