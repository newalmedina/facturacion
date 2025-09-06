<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .container {
            margin: 0 auto;
            padding: 20px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .bold {
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        th,
        td {
            padding: 5px;
            border: 1px solid #000;
            word-break: break-word;
        }

        th {
            background-color: #f2f2f2;
        }

        .no-border td {
            border: none;
            padding: 2px 5px;
        }

        .separator {
            border-top: 2px dashed #000;
            margin: 15px 0;
        }

        .footer {
            margin-top: 30px;
            font-size: 11px;
        }

        .signature {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }

        .signature div {
            width: 45%;
            text-align: center;
            border-top: 1px solid #000;
            padding-top: 5px;
        }
    </style>
</head>

<body>
    <div class="container">

        {{-- Cabecera de la empresa --}}
        <div class="text-center bold" style="font-size:16px;">
            @if (!empty($settings['image']))
            <img src="{{ public_path('storage/' . $settings['image']) }}" alt="Logo" style="max-height: 80px; margin-bottom: 10px;"><br>
            @endif
            {{ $settings['brand_name'] ?? 'Nombre Marca' }}<br>
            {{ $settings['address'] ?? 'Dirección' }}<br>
            NIF: {{ $settings['nif'] ?? '-' }}<br>
            Tel: {{ $settings['phone'] ?? '-' }}<br>
            Email: {{ $settings['email'] ?? '-' }}
        </div>

        <div class="separator"></div>

        {{-- Datos de la factura y cliente --}}
        <table class="no-border">
            <tr>
                <td>
                    <strong>Factura Nº:</strong> {{ $order->code ?? '-' }}<br>
                    <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($order->date)->format('d/m/Y') }}
                </td>
                <td>
                    <strong>Cliente:</strong> {{ $order->customer->name ?? '-' }}<br>
                    <strong>Dirección:</strong> {{ $order->customer->address ?? '-' }}<br>
                    <strong>NIF/CIF:</strong> {{ $order->customer->nif ?? '-' }}
                </td>
            </tr>
        </table>

        <div class="separator"></div>

        {{-- Tabla de productos --}}
        <table>
            <thead>
                <tr>
                    <th class="text-left">Producto / Servicio</th>
                    <th class="text-right">P.U.</th>
                    <th class="text-right">Cantidad</th>
                    <th class="text-right">IVA %</th>
                    <th class="text-right">Base</th>
                    <th class="text-right">IVA (€)</th>
                    <th class="text-right">Total (€)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderDetails as $detail)
                @php
                $base = $detail->total_base_price;
                $iva_amount = $detail->taxes_amount ?? ($base * $detail->taxes / 100);
                $total = $detail->total_with_taxes;
                @endphp
                <tr>
                    <td>{{ $detail->product_name_formatted }}</td>
                    <td class="text-right">{{ number_format($detail->price, 2) }} €</td>
                    <td class="text-right">{{ $detail->quantity }}</td>
                    <td class="text-right">{{ number_format($detail->taxes, 2) }}%</td>
                    <td class="text-right">{{ number_format($base, 2) }} €</td>
                    <td class="text-right">{{ number_format($iva_amount, 2) }} €</td>
                    <td class="text-right bold">{{ number_format($total, 2) }} €</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Totales --}}
        <table>
            <tr>
                <td class="text-right bold" style="width: 80%;">Subtotal</td>
                <td class="text-right bold">{{ number_format($order->subtotal, 2) }} €</td>
            </tr>
            <tr>
                <td class="text-right bold">Total IVA</td>
                <td class="text-right bold">{{ number_format($order->impuestos, 2) }} €</td>
            </tr>
            <tr>
                <td class="text-right bold">TOTAL FACTURA</td>
                <td class="text-right bold">{{ number_format($order->total, 2) }} €</td>
            </tr>
        </table>

        <div class="footer text-center">
            Forma de pago: {{ $order->payment_method ?? 'Efectivo' }}<br>
            Gracias por su confianza.<br>
            Esta factura es válida como documento contable.
        </div>

        {{-- Firma y sello --}}
        <div class="signature">
            <div>Firma cliente</div>
            <div>Firma emisor</div>
        </div>

    </div>
</body>

</html>