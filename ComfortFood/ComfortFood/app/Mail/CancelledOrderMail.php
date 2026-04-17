<?php

namespace App\Mail;

use App\Models\Pedido;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CancelledOrderMail extends Mailable
{
    use Queueable, SerializesModels;

    public Pedido $order;
    public ?string $cancellationReason;
    public ?string $refundInfo;
    public ?string $supportUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(
        Pedido $order,
        ?string $cancellationReason = null,
        ?string $refundInfo = null,
        ?string $supportUrl = null
    ) {
        $this->order = $order;
        $this->cancellationReason = $cancellationReason;
        $this->refundInfo = $refundInfo;
        $this->supportUrl = $supportUrl;
        $this->order->loadMissing('cliente.user');
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this
            ->subject('Tu pedido #' . $this->order->id_pedido . ' fue cancelado - ComfortFood')
            ->markdown('emails.orders.cancelled', [
                'order' => $this->order,
                'cancellationReason' => $this->cancellationReason,
                'refundInfo' => $this->refundInfo,
                'supportUrl' => $this->supportUrl,
            ]);
    }
}
