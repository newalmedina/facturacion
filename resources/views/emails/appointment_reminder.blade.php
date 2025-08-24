@extends('emails.layouts.app')

@section('title', "Recordatorio de cita")

@section('content')
<tr>
    <td style="padding: 30px 40px; color: #333333; font-size: 16px; line-height: 1.5;">
        @if($appointment->worker)
            <h2>Hola {{ $appointment->requester_name }},</h2>

            <p>
                Te recordamos que tienes una cita programada para el día 
                <strong>{{ $appointment->date->format('d/m/Y') }}</strong> desde 
                <strong>{{ $appointment->start_time->format('H:i') }}</strong> hasta 
                <strong>{{ $appointment->end_time->format('H:i') }}</strong>.
            </p>

            <ul>
                <li>Empleado: <strong>{{ $appointment->worker->name }}</strong></li>
                <li>Peinado: <strong>{{ $appointment->item->name . ", " . $appointment->item->total_price }} €</strong></li>
            </ul>

            <p style="margin-top:20px;">
                En caso de que no puedas asistir, te rogamos que nos lo comuniques con la mayor antelación posible 
                para poder reprogramar tu cita o asignar el horario a otra persona.  
                Puedes cancelar o modificar tu cita respondiendo a este correo o contactándonos directamente.
            </p>
        @else
            <p>Este correo fue generado automáticamente.</p>
        @endif
    </td>
</tr>
@endsection
