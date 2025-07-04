@extends('emails.layouts.app')

@section('title', 'Factura enviada')

@section('content')
<tr>
    <td style="padding: 30px 40px; color: #333333; font-size: 16px; line-height: 1.5;">
        <h2 style="margin-top: 0;">Hola {{ $userName}}!!,</h2>

        <p>
            Este es un correo de prueba para verificar que la configuración de envío de correos electrónicos está funcionando correctamente. Si estás viendo este mensaje, significa que el sistema puede enviar correos electrónicos sin problemas.
        </p>
    </td>
</tr>
@endsection
