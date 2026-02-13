<?php

namespace App\Mail;

use App\Models\Pedido;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DeliveredOrderMail extends Mailable
{
    use Queueable, SerializesModels;

    public Pedido $order;

    /**
     * Create a new message instance.
     */
    public function __construct(Pedido $order)
    {
        $this->order = $order;
        $this->order->loadMissing('cliente.user', 'restaurante.user');
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this
            ->subject('Tu pedido #' . $this->order->id_pedido . ' ha sido entregado - ComfortFood')
            ->markdown('emails.orders.delivered', [
                'order' => $this->order,
            ]);
    }
}
