@extends('emails.layouts.app')

@section('title', 'Cambio de estado de cita asignada')

@section('content')
@php
    $contactForm = \App\Models\CmsContent::findBySlug('contact-form');
@endphp
<tr>
    <td style="padding: 30px 40px; color: #333333; font-size: 16px; line-height: 1.5;">
        @if($appointment->worker)
            <h2>Hola {{ $appointment->worker->name }},</h2>

            <p>
                Te informamos que la cita que tienes asignada con <strong>{{ $appointment->requester_name }}</strong> programada para el día 
                <strong>{{ $appointment->date->format('d/m/Y') }}</strong>,
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
                <li>Teléfono: <strong>{{ $appointment->requester_phone }}</strong></li>
                <li>Email: <strong>{{ $appointment->requester_email  }}</strong></li>
                <li>Peinado: <strong>{{ $appointment->item->name . ", " . $appointment->item->total_price }} €</strong></li>
    
    
            </ul>
            <hr>
            
        @else
            <p>Este correo fue generado automáticamente, pero no se encontró un trabajador asignado a esta cita.</p>
        @endif
    </td>
</tr>
@endsection
