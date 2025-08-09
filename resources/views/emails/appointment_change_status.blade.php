@extends('emails.layouts.app')

@section('title', 'Cambio de estado de cita')

@section('content')
<tr>
    <td style="padding: 30px 40px; color: #333333; font-size: 16px; line-height: 1.5;">
        <h2>Hola {{ $appointment->requester_name }},</h2>

        <p>
            Te informamos que tu cita para el peinado programada para el día {{ $appointment->date->format('d/m/Y') }},
            desde las {{ $appointment->start_time->format('H:i') }} hasta las {{ $appointment->end_time->format('H:i') }},
            ha cambiado de estado y ahora se encuentra como:
            <span style="
                background-color: {{ $appointment->status_color }};
                color: white;
                padding: 0.25rem 0.75rem;
                border-radius: 9999px;
                font-weight: 600;
                text-transform: uppercase;
            ">
                {{ $appointment->status_name_formatted }}
            </span>.
        </p>
<hr>
        <p>Si tienes alguna duda o necesitas asistencia, no dudes en contactarnos.</p>
    </td>
</tr>
@endsection
