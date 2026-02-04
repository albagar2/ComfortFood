@component('mail::message')
<div style="text-align: center;">
    <img src="{{ config('app.url') }}/images/logo.png" alt="ComfortFood"
        style="width: 80px; height: 80px; border-radius: 20px; margin-bottom: 20px;">
</div>

# ¡Hola, {{ $notifiable->nombre_completo }}!

Gracias por registrarte en **ComfortFood**. Para empezar a disfrutar de los mejores platos locales y gestionar tus
pedidos, por favor verifica tu cuenta haciendo clic en el botón de abajo.

@component('mail::button', ['url' => $url, 'color' => 'primary'])
Verificar cuenta
@endcomponent

Si no has creado ninguna cuenta, no es necesario realizar ninguna otra acción.

Saludos,<br>
El equipo de {{ config('app.name') }}
@endcomponent