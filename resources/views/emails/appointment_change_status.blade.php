@extends('emails.layouts.app')

@section('title', 'Cambio de estado de cita')

@section('content')
@php
    $contactForm = \App\Models\CmsContent::findBySlug('contact-form');
@endphp
<tr>
    <td style="padding: 30px 40px; color: #333333; font-size: 16px; line-height: 1.5;">
        <h2>Hola {{ $appointment->requester_name }},</h2>

        <p>
            Te informamos que tu cita programada para el día <strong>{{ $appointment->date->format('d/m/Y') }}</strong>,
            desde las <strong>{{ $appointment->start_time->format('H:i') }}</strong> hasta las <strong>{{ $appointment->end_time->format('H:i') }}</strong>,
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
        <ul>
            <li>Empleado: <strong>{{ $appointment->worker->name }}</strong></li>
            <li>Peinado: <strong>{{ $appointment->item->name . ", " . $appointment->item->total_price }} €</strong></li>


        </ul>
        @if ($appointment->status=="confirmed")
        <p style="margin-top:20px; color:rgb(235, 118, 118)">
            En caso de que no puedas asistir, te rogamos que nos lo comuniques con la mayor antelación posible 
            para poder reprogramar tu cita o asignar el horario a otra persona.  
            Puedes cancelar o modificar tu cita respondiendo a este correo o contactándonos directamente.
        </p>
        @endif
        

<hr>
<p>Si necesitas modificarla o cancelarla, por favor contáctanos.</p>
    </td>
</tr>
@endsection
