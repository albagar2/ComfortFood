<?php

namespace App\Mail;

use App\Models\Pedido;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PreparingOrderMail extends Mailable
{
    use Queueable, SerializesModels;

    public Pedido $order;
    public ?string $estimatedTime;
    public ?string $orderUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(Pedido $order, ?string $estimatedTime = null, ?string $orderUrl = null)
    {
        $this->order = $order;
        $this->estimatedTime = $estimatedTime;
        $this->orderUrl = $orderUrl;
        $this->order->loadMissing('cliente.user');
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this
            ->subject('Tu pedido #' . $this->order->id_pedido . ' esta en preparacion - ComfortFood')
            ->markdown('emails.orders.preparing', [
                'order' => $this->order,
                'estimatedTime' => $this->estimatedTime,
                'orderUrl' => $this->orderUrl,
            ]);
    }
}
