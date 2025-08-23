@extends('emails.layouts.app')

@section('title', 'Recibo enviado')

@section('content')
<tr>
    <td style="padding: 30px 40px; color: #333333; font-size: 16px; line-height: 1.5;">
        <h2 style="margin-top: 0;">Hola {{ $userName}}!!,</h2>

        <p>
            Este correo es una prueba automática enviada por el sistema para confirmar que el cron y el envío de correos funcionan correctamente. 
            Si recibes este mensaje, significa que todo está configurado y funcionando sin problemas.
        </p>
        
    </td>
</tr>
@endsection
