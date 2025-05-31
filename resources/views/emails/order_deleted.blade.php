@extends('emails.layouts.app')

@section('title', 'Orden Eliminada')

@section('content')
<tr>
    <td style="padding: 30px 40px; color: #333333; font-size: 16px; line-height: 1.5;">
        <h2 style="margin-top: 0;">Orden Eliminada</h2>
        <p>La orden <strong>#{{ $order->id }}</strong> ha sido eliminada por el usuario <strong>{{ $order->deletedBy?->name ?? 'Desconocido' }}</strong>.</p>

    </td>
</tr>
@endsection
