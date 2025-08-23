@extends('emails.layouts.app')

@section('title', 'Nueva cita asignada')

@section('content')
<tr>
    <td style="padding: 30px 40px; color: #333333; font-size: 16px; line-height: 1.5;">
        <h2>Hola {{ $appointment->worker->name }},</h2>

        <p>
            {{ $appointment->requester_name }} acaba de solicitar una cita para el día <strong>{{ $appointment->date->format('d/m/Y') }}</strong>,
            desde las <strong>{{ $appointment->start_time->format('H:i') }}</strong> hasta las <strong>{{ $appointment->end_time->format('H:i') }}</strong>.
        </p>

        <hr>
        <p>Por favor revisa tu agenda y confirma la disponibilidad en el siguiente enlace:</p>
        <p>
            <a href="{{ url('admin/appointments/' . $appointment->id . '/edit') }}">Ver cita</a>
        </p>
    </td>
</tr>
@endsection
