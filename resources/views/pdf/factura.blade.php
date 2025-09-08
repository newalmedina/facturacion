<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Factura Nº: 01</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      font-size: 12px;
      margin: 20px;
    }

    .header-table {
      width: 100%;
      margin-bottom: 30px;
    }

    .info-table {
      width: 100%;
      margin-bottom: 30px;
    }

    .items-table {
      width: 100%;
      margin-top: 20px;
    }

    .items-table th, .items-table td {
      padding: 6px;
      text-align: left;
    }

    .totals-table {
      width: 100%;
      margin-top: 20px;
    }

    .payment-info {
      margin-top: 30px;
    }
  </style>
</head>
<body>

  <!-- Cabecera con número de factura y logo -->
  <table class="header-table">
    <tr>
      <td style="width: 50%;  ">
        <b style="font-size: 16px;">FACTURA Nº:   {{ $order->code }}</b>
        <p for="">Fecha factura: {{ $order->date->format("d/m/Y") }}</p>
        <p for="">Fecha vencimiento: {{ $order->date->format("d/m/Y") }}</p>

      </td>
      <td style="width: 50%; text-align: right;">

        
        @if (!empty($generalSettings->image_base64))
         <img src="{{  $generalSettings->image_base64 }}" alt="Logo" style="width: 200px;">
        @endif
      </td>
    </tr>
  </table>

  <!-- Datos del cliente y empresa -->
  <table class="info-table" width="100%" cellspacing="0" cellpadding="10" style="border-collapse: collapse;">
    <tr>
      <td style="width: 50%; vertical-align: top; border-right: 2px solid #b462e2; padding-right: 20px;">
        <strong>Datos del Cliente</strong><br>
        Nombre: <b>{{ $order->billing_name ?? '-' }} </b><br>
          Email: <b>{{$order->billing_email ?? '-' }}  </b><br>
          Teléfono: <b>{{ $order->billing_phone ?? '-' }} </b><br>
          Dirección: <b>{{ $order->billing_address ?? 'Dirección' }} </b><br>
      </td>
      <td style="width: 50%; vertical-align: top; padding-left: 20px;">
       
        <strong>Datos de la Empresa</strong><br>
          Nombre: <b>{{ $generalSettings->brand_name ?? 'Nombre Marca' }} </b><br>
          NIF/CIF: <b>{{ $generalSettings->nif ?? 'Nombre Marca' }} </b><br>
          Email: <b>{{$generalSettings->email ?? '-' }}  </b><br>
          Teléfono: <b>{{ $generalSettings->phone ?? '-' }} </b><br>
          Dirección: <b>{{ $generalSettings->full_address ?? 'Dirección' }} </b><br>
      </td>
    </tr>
  </table>
  

  <!-- Detalle de productos/servicios -->
  <table class="items-table" width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">
    <thead>
      <tr>
        <th>Detalle</th>
        <th>Precio Unidad</th>
        <th>Cantidad</th>
        <th>Precio</th>
        <th>IVA</th>
        <th>Importe</th>
      </tr>
      <tr style="border-top: 3px solid #b462e2; height: 30px;">
        <th colspan="6"></th>
          
      </tr>
    </thead>
    <tbody>
     @php
         $subtotal=0;
         $ivaMonto=0;
         $total=0;
     @endphp
        @foreach($order->orderDetails as $detail)        
        @php
             $subtotal+=$detail->total_base_price;
             $total+=$detail->total_with_taxes;
             $ivaMonto+=$detail->taxes_amount ;
        @endphp
            <tr>
              <td class="text-left" style="font-size: 11px;">
                {{ ucfirst($detail->product_name_formatted) }}
              </td>
            
              <td class="text-right nobreak">{{ number_format($detail->price, 2) }} €</td>
              <td class="text-right nobreak">{{ $detail->quantity }}</td>
              <td class="text-right nobreak">{{ number_format($detail->total_base_price, 2) }}€</td>
              <td class="text-right nobreak">{{ number_format($detail->taxes_amount, 2) }} €</td>
              <td class="text-right bold nobreak">{{ number_format($detail->total_with_taxes, 2) }}€</td>
          </tr>
        @endforeach
       
 
    <tfoot>
      <tr>
        <td colspan="6" style="height: 5px;"></td>
      </tr>
      <tr>
        <td colspan="6" style="border-top: 3px solid #b462e2;"></td>
      </tr>

      <tr>
        <td colspan="3"></td>
        <td>Subtotal</td>
        <td></td>
        <td>{{ number_format($subtotal, 2) }} €</td>
      </tr>
      <tr>
        <td colspan="3"></td>
        <td>IVA</td>
        <td>{{ $order->iva ?? 0 }} %</td>
        <td>{{ number_format($ivaMonto, 2) }} €</td>
      </tr>

      <tr>
        <td colspan="4" style="height: 15px;"></td>
      </tr>

      <tr>
        <td colspan="3"  style=""></td>
        <td colspan="2" style="border: 2px solid #b462e2; text-align: center; font-weight: bold;">Total</td>
        <td style="border: 2px solid #b462e2; font-weight: bold;">{{ number_format($total , 2) }} €</td>
      </tr>
      
      
    </tfoot>
  </table>

  <!-- Información de pago -->
  <div style="width:45%; border: 2px solid #b462e2; border-radius: 12px; padding: 15px; width: fit-content; background-color: #f8e6fc; box-shadow: 0 4px 8px rgba(0,0,0,0.1); margin-top: 30px;">
    <strong>Información de Pago</strong><br>
    Método de pago: <b>{{ $order->payment_method }}</b><br>

    @if(in_array($order->payment_method, ['Transferencia Bancaria', 'Bizum']))
        {{-- {{ $generalSettings->bank_name }}<br> --}}
        Entidad bancaria: <b>{{ $generalSettings->bank_name }}</b> <br>
        Número de cuenta: <b>{{ $generalSettings->bank_number }}</b>
    @endif
  </div>

  <!-- Mensaje de agradecimiento -->
  <div style="text-align: center; margin-top: 50px; font-size: 24px; font-weight: bold; color: #581177;">
    ¡Gracias por preferirnos!
  </div>
  
</body>
</html>
