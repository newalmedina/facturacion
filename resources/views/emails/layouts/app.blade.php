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
        table.wrapper {
            width: 100% !important;
            background-color: #f4f4f4;
            padding: 20px 0;
        }
        table.container {
            width: 600px;
            max-width: 600px;
            background-color: #ffffff;
            border-radius: 6px;
            overflow: hidden;
            margin: 0 auto;
        }
        td.content {
            padding: 0 40px 30px 40px;
            color: #333333;
            font-size: 16px;
            line-height: 1.5;
        }
        td.footer {
            background-color: #f0f0f0;
            padding: 15px 40px;
            text-align: center;
            color: #999999;
            font-size: 12px;
        }
        @media only screen and (max-width: 620px) {
            table.container {
                width: 100% !important;
                max-width: 100% !important;
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
                        <td style="background-color:#581177; padding:20px;">
                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                   {{-- @if($brandLogoBase64)
                                        <td style="vertical-align: middle; width:60px; padding-right:15px;">
                                            <img src="{{ $brandLogoBase64 }}" alt="Logo" style="max-height:50px; display:block; border:0; outline:none; text-decoration:none;" />
                                        </td>
                                    @endif--}}
                                    <td style="vertical-align: middle;">
                                        @if(!empty($brandName))
                                            <h1 style="color:#ffffff; font-size:24px; margin:0; font-weight:normal;">{{ $brandName }}</h1>
                                        @else
                                            <h1 style="color:#ffffff; font-size:24px; margin:0; font-weight:normal;">Mi Empresa</h1>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Contenido dinámico -->
                    @yield('content')
                    <tr>
                        <td style="padding: 30px 40px; color: #333333; font-size: 16px; line-height: 1.5;">
                            <h2>Hola {{ $appointment->worker->name }},</h2>
                    
                            <p>
                                <b>{{ ucfirst($appointment->requester_name) }}</b>
                                acaba de solicitar una cita para el día <strong>{{ $appointment->date->format('d/m/Y') }}</strong>,
                                desde las <strong>{{ $appointment->start_time->format('H:i') }}</strong> hasta las <strong>{{ $appointment->end_time->format('H:i') }}</strong>.
                            </p>
                            <ul>
                                <li>Teléfono: <strong>{{ $appointment->requester_phone }}</strong></li>
                                <li>Email: <strong>{{ $appointment->requester_email  }}</strong></li>
                                <li>Peinado: <strong>{{ $appointment->item->name . ", " . $appointment->item->total_price }} €</strong></li>
                    
                            </ul>
                            
                            <hr>
                            <p>Por favor revisa tu agenda y confirma la disponibilidad en el siguiente enlace:</p>
                            <p>
                                <a href="{{ url('admin/appointments/' . $appointment->id . '/edit') }}">Ver cita</a>
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Pie de página -->
                    <tr>
                        <td>
                            @php
                        $contactForm = \App\Models\CmsContent::findBySlug('contact-form');
                    @endphp
                            <p>
                                Si necesitas más información, no dudes en contactarnos a través de 
                                <a 
                                    id="floating-whatsapp-btn" 
                                    target="_blank" 
                                    href="https://wa.me/{{ preg_replace('/\D/', '', $contactForm->whatsapp_url) }}" 
                                    title="Chatear por WhatsApp">
                                    WhatsApp
                                </a>. ¡Estaremos encantados de ayudarte!
                            </p>
                            
                        </td>
                    </tr>
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
