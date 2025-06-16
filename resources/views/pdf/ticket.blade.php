<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
    body {
        font-family: Arial, sans-serif;
        font-size: 12px;
        width: 58mm;
        margin: 0 auto;
        padding: 0;
        box-sizing: border-box;
    }
    .nobreak {
        white-space: nowrap;
    }

    .text-center { text-align: center; }
    .text-right { text-align: right; }
    .text-left { text-align: left; }
    .bold { font-weight: bold; }

    table {
        width: 100%;
        border-collapse: collapse;
        margin: 0;
        font-size: 11px;
    }

    td, th {
        padding: 3px 5px;
        word-break: break-word;
        white-space: normal;
        overflow-wrap: break-word;
    }

    th {
        border-bottom: 1px solid #000;
    }

    .separator {
        border-top: 1px dashed #000;
        margin: 10px 0px;
    }
</style>
</head>
<body>
    <div class="text-center bold" style="font-size:14px;">
 
         @if (!empty($settings['image']))
            <img src="{{ public_path('storage/' . $settings['image']) }}" alt="Logo" style="max-height: 50px; margin-bottom: 10px;">
            <br>
        @endif
        {{ $settings['brand_name'] ?? 'Nombre Marca' }}<br>
        {{ $settings['address'] ?? 'Dirección' }}<br>
        Tel: {{ $settings['phone'] ?? '-' }}<br>
        {{ $settings['email'] ?? '-' }}
    </div>

    <div class="separator"></div>

    <div>
        <strong>Cliente:</strong> {{ $order->customer->name ?? '-' }}<br>
        <strong>Tel:</strong> {{ $order->customer->phone ?? '-' }}<br>
        <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($order->date)->format('d/m/Y') }}<br>
        <strong>Código:</strong> {{ $order->code ?? '-' }}
    </div>

    <div class="separator"></div>

    <br>
    <table>
        {{-- <thead>
            <tr>
                <th class="text-left" style="width: 35%;">Producto</th>
                <th class="text-right" style="width: 15%;">P.U.</th>
                <th class="text-right" style="width: 10%;">C.</th>
                <th class="text-right" style="width: 6%;">IVA%</th>
                <th class="text-right" style="width: 6%;">IVA%</th>
                <th class="text-right" style="width: 25%;">Total</th>
            </tr>
        </thead>--}}
        <tbody>
            @php
                $subtotal = 0;
                $totalImpuestos = 0;
            @endphp
            @foreach($order->orderDetails as $detail)
               
                <tr>
                    <td colspan="6" class="text-left" style="font-size: 11px;">{{ \Illuminate\Support\Str::limit($detail->product_name_formatted, 30, '...') }}</td>
                    
                </tr>
                <tr>
                    <td class="text-right nobreak">{{ number_format($detail->price, 2) }}€</td>
                    <td class="text-right nobreak">x {{ $detail->quantity }}</td>
                    <td class="text-right nobreak">{{ number_format($detail->total_base_price, 2) }}€</td>
                    <td class="text-right nobreak">{{ number_format($detail->taxes, 2) }} %</td>
                    {{-- <td class="text-right nobreak">{{ number_format($detail->taxes_amount, 2) }} €</td> --}}
                    <td class="text-right bold nobreak">{{ number_format($detail->total_with_taxes, 2) }}€</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="separator"></div>

    <table>
        <tr>
            <td class="text-left">Subtotal</td>
            <td class="text-right bold">{{ number_format($order->subtotal, 2) }}€</td>
        </tr>
        <tr>
            <td class="text-left">Impuestos</td>
            <td class="text-right bold">{{ number_format($order->impuestos, 2) }}€</td>
        </tr>
        <tr class="bold">
            <td class="text-left">Total</td>
            <td class="text-right bold">{{ number_format($order->total, 2) }}€</td>
        </tr>
    </table>


    <div class="separator"></div>

    <div class="text-center" style="font-size: 12px;">
        ¡Gracias por su compra!<br>
        {{ \Carbon\Carbon::parse($order->date)->format('d/m/Y') }}
    </div>
</body>
</html>
