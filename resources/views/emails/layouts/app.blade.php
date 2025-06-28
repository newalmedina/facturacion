@php
    use App\Models\Setting;

$settings = Setting::first();
$generalSettings = $settings?->general;

$brandName = $generalSettings?->brand_name ?? config('app.name', 'Mi Empresa');
$brandLogoBase64 = $generalSettings?->image_base64 ?? null;
@endphp

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Notificación')</title>
    <style>
        /* Reset y estilos básicos */
        body, table, td, p, h1 {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        body {
            background-color: #f4f4f4;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        /* Wrapper con padding y ancho total */
        table.wrapper {
            width: 100% !important;
            background-color: #f4f4f4;
            padding: 20px 0;
        }
        /* Contenedor central */
        table.container {
            width: 600px;
            max-width: 600px;
            background-color: #ffffff;
            border-radius: 6px;
            overflow: hidden;
            margin: 0 auto;
        }
        /* Cabecera */
        td.header {
            background-color: #acacac;
            height: 80px;
            padding: 0 20px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        td.header img {
    max-height: 50px;
    width: auto;
    max-width: 100%;
    height: auto;
    display: block;
}

        td.header h1 {
            color: white;
            font-size: 24px;
            margin: 0;
        }
        /* Contenido */
        td.content {
            padding: 0 40px 30px 40px;
            color: #333333;
            font-size: 16px;
            line-height: 1.5;
        }
        /* Pie de página */
        td.footer {
            background-color: #f0f0f0;
            padding: 15px 40px;
            text-align: center;
            color: #999999;
            font-size: 12px;
        }
        /* Media Queries para móviles */
        @media only screen and (max-width: 620px) {
            table.container {
                width: 100% !important;
                max-width: 100% !important;
            }
            td.header {
                height: auto !important;
                padding: 15px 20px !important;
                display: block !important;
                text-align: center !important;
            }
            td.header h1 {
                font-size: 20px !important;
                margin-top: 10px !important;
            }
            td.content {
                padding: 20px !important;
                font-size: 14px !important;
            }
            td.footer {
                padding: 15px 20px !important;
                font-size: 11px !important;
            }
            img {
                max-width: 100% !important;
                height: auto !important;
            }
        }
    </style>
</head>
<body>
    <table class="wrapper" cellpadding="0" cellspacing="0" role="presentation">
        <tr>
            <td align="center">
                <table class="container" cellpadding="0" cellspacing="0" role="presentation">
                    <!-- Cabecera -->
                    <tr>
                        <td class="header">
                            @if($brandLogoBase64)
                            <img src="{{ $brandLogoBase64 }}" alt="Logo" style="max-height:50px; width:auto; max-width:100%; height:auto; display:block;" />

                            @endif

                            @if(!empty($brandName))
                                <h1>{{ $brandName }}</h1>
                            @endif

                            @if(empty($brandLogoBase64) && empty($brandName))
                                <h1>Mi Empresa</h1>
                            @endif
                        </td>
                    </tr>

                    <!-- Contenido dinámico -->
                    @yield('content')

                    <!-- Mensaje general -->
                    {{-- <tr>
                        <td class="content">
                            <p>Si tienes alguna duda, contacta con soporte.</p>
                        </td>
                    </tr> --}}

                    <!-- Pie de página -->
                    <tr>
                        <td class="footer">
                            &copy; {{ date('Y') }} {{ $brandName ?? 'Mi Empresa' }}. Todos los derechos reservados.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
