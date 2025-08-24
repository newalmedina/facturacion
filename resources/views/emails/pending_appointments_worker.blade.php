@extends('emails.layouts.app')

@section('title', 'Citas pendientes de confirmación')

@section('content')
<tr>
    <td style="padding: 30px 40px; color: #333333; font-size: 16px; line-height: 1.5;">
        <h2>Hola {{ $worker->name }},</h2>

        <p>
            Tienes las siguientes citas <strong>pendientes de confirmar</strong> desde hoy en adelante:
        </p>
        <br>
            <table width="100%" cellpadding="8" cellspacing="0" border="1" style="border-collapse: collapse; font-size: 14px; color: #333333;">
                <thead>
                    <tr style="background-color: #f4f4f4; text-align: left;">
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Servicio</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($appointments as $appointment)
                        <tr>
                            <td>{{ $appointment->requester_name }}</td>
                            <td>{{ $appointment->date->format('d/m/Y') }}</td>
                            <td>{{ $appointment->start_time->format('H:i') }} - {{ $appointment->end_time->format('H:i') }}</td>
                            <td>{{ $appointment->item->name }}</td>
                            <td>
                                <a href="{{ url('admin/appointments/' . $appointment->id . '/edit') }}" 
                                   style=" padding: 6px 10px; text-decoration: none; border-radius: 4px; font-size: 13px;">
                                    Ver cita
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <br>

        <p>
            Te rogamos que confirmes estas citas lo antes posible.  
            Si alguna no puede realizarse, por favor actualiza su estado en el sistema y notifique al cliente   .
        </p>
    </td>
</tr>
@endsection
