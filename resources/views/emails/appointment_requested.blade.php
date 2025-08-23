@extends('emails.layouts.app')

@section('title', 'Cita solicitada')

@section('content')
@php
    $contactForm = \App\Models\CmsContent::findBySlug('contact-form');
@endphp
<tr>
    <td style="padding: 30px 40px; color: #333333; font-size: 16px; line-height: 1.5;">
        <h2>Hola {{ $appointment->requester_name }},</h2>

        <p>
            Acabas de solicitar una cita para el día <strong>{{ $appointment->date->format('d/m/Y') }}</strong>,
            desde las <strong>{{ $appointment->start_time->format('H:i') }}</strong> hasta las 
            <strong>{{ $appointment->end_time->format('H:i') }}</strong> con <strong>{{ $appointment->worker->name }}</strong>.
        </p>
        <ul>
            <li>Empleado: <strong>{{ $appointment->worker->name }}</strong></li>
            <li>Peinado: <strong>{{ $appointment->item->name . ", " . $appointment->item->total_price }} €</strong></li>


        </ul>
        <hr>
        <p>Si necesitas modificarla o cancelarla, por favor contáctanos al  teléfono <a 
            id="floating-whatsapp-btn" 
            target="_blank" 
            href="https://wa.me/{{ preg_replace('/\D/', '', $contactForm->whatsapp_url) }}" 
            title="Chatear por WhatsApp">
            {{ $contactForm->whatsapp_url}}
         </a>.</p>
    </td>
</tr>
@endsection
