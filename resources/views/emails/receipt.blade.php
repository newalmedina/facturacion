@extends('emails.layouts.app')

@section('title', $factura ? 'Factura enviado':"Presupuesto enviado")

@section('content')
<tr>
    <td style="padding: 30px 40px; color: #333333; font-size: 16px; line-height: 1.5;">
        <h2 style="margin-top: 0;">{{ $factura ? 'Factura enviado':"Presupuesto enviado" }}</h2>
        <p>El documento se acaba de enviar como adjunto</p>

    </td>
</tr>
@endsection
