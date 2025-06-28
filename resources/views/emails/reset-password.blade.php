

@extends('emails.layouts.app')

@section('title', 'Restablecer contraseña')

@section('content')

@section('content')
@extends('emails.layouts.app')

@section('title', 'Restablecer contraseña')

@section('content')
<tr>
    <td style="padding: 30px 40px; color: #333333; font-size: 16px; line-height: 1.5; font-family: Arial, sans-serif;">
        <h2 style="margin-top: 0;">Hola {{ $user->name }}!</h2>

        <p>Recibimos una solicitud para restablecer tu contraseña. Puedes restablecerla haciendo clic en el botón de abajo:</p>

        <!-- Botón -->
        <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="margin: 20px 0;">
            <tr>
                <td align="center">
                    <a href="{{ $url }}" style="
                        background-color: #4F46E5;
                        border: none;
                        color: white;
                        padding: 12px 25px;
                        text-align: center;
                        text-decoration: none;
                        display: inline-block;
                        font-size: 16px;
                        border-radius: 6px;
                        font-weight: bold;
                    ">
                        Restablecer contraseña
                    </a>
                </td>
            </tr>
        </table>

        <p>Este enlace expirará en {{ $count }} minutos.</p>

        <p>Si tienes problemas al hacer clic en el botón, copia y pega la siguiente URL en tu navegador:</p>

        <p style="word-break: break-all;">
            <a href="{{ $url }}" style="color: #4F46E5;">{{ $url }}</a>
        </p>
    </td>
</tr>
@endsection

@endsection