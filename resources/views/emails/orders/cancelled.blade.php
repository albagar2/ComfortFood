@component('mail::message')

<div style="text-align: center;">
    <img src="{{ $message->embed(public_path('images/logo.png')) }}" alt="Logo" width="50" height="50">
</div>
# Hola {{ $order->cliente->user->nombre_completo ?? 'Cliente' }}

Lamentamos informarte que tu pedido **#{{ $order->id_pedido }}** ha sido cancelado.

@component('mail::panel')
**Motivo de cancelación:** {{ $cancellationReason ?? 'No especificado' }}

**Información de reembolso:**
{{ $refundInfo ?? 'En caso de corresponder, el reembolso se procesará según el método de pago utilizado.' }}
@endcomponent

Si necesitas ayuda adicional, estamos disponibles para ti.

@component('mail::button', ['url' => $supportUrl ?? route('customer.support')])
Contactar soporte
@endcomponent

Gracias por tu comprensión,
Equipo ComfortFood
@endcomponent