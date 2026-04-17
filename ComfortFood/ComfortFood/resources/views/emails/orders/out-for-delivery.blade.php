@component('mail::message')

<div style="text-align: center;">
    <img src="{{ $message->embed(public_path('images/logo.png')) }}" alt="Logo" width="50" height="50">
</div>

# ¡Tu pedido está en camino!

Hola {{ $order->cliente->user->nombre_completo ?? 'Cliente' }},

Tu pedido **#{{ $order->id_pedido }}** ha salido del restaurante y está siendo repartido. ¡Pronto podrás disfrutarlo!

@component('mail::panel')
**Dirección de entrega:** {{ $order->direccion_entrega }}
@endcomponent

Prepara tu mesa, ¡ya casi llegamos!

Gracias por confiar en ComfortFood
@endcomponent