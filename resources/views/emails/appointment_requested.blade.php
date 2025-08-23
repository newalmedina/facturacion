@extends('emails.layouts.app')

@section('title', 'Cita solicitada')

@section('content')
<tr>
    <td style="padding: 30px 40px; color: #333333; font-size: 16px; line-height: 1.5;">
        <h2>Hola {{ $appointment->requester_name }},</h2>

        <p>
            Acabas de solicitar una cita para el día <strong>{{ $appointment->date->format('d/m/Y') }}</strong>,
            desde las <strong>{{ $appointment->start_time->format('H:i') }}</strong> hasta las 
            <strong>{{ $appointment->end_time->format('H:i') }}</strong> con <strong>{{ $appointment->worker->name }}</strong>.
        </p>

        <hr>
        <p>Si necesitas modificarla o cancelarla, por favor contáctanos.</p>
    </td>
</tr>
@endsection
