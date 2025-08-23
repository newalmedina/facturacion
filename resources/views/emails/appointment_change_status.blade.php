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
        
<hr>
<p>Si necesitas modificarla o cancelarla, por favor contáctanos.</p>
    </td>
</tr>
@endsection
