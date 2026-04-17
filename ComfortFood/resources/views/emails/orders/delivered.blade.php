@component('mail::message')

<div style="text-align: center;">
    <img src="{{ $message->embed(public_path('images/logo.png')) }}" alt="Logo" width="50" height="50">
</div>

# ¡Pedido entregado!

Hola {{ $order->cliente->user->nombre_completo ?? 'Cliente' }},

Tu pedido **#{{ $order->id_pedido }}** ha sido **entregado** con éxito. ¡Esperamos que lo disfrutes!

@component('mail::panel')
**Total:** {{ number_format($order->precio_total, 2, ',', '.') }} €

**Dirección de entrega:** {{ $order->direccion_entrega }}

**Restaurante:** {{ $order->restaurante->user->nombre_completo ?? '-' }}
@endcomponent

Si todo ha ido bien, te invitamos a valorar tu experiencia desde la app.

@component('mail::button', ['url' => url('/orders/details/' . $order->id_pedido)])
Valorar mi pedido
@endcomponent

¡Gracias por confiar en ComfortFood!
@endcomponent