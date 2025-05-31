<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Notificación')</title>
</head>
<body style="margin:0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4; padding: 20px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 6px; overflow: hidden;">
                    <!-- Cabecera -->
                    <tr>
                        <td style="background-color: #2a7ae2; height: 80px; padding: 0 20px; display: flex; align-items: center; gap: 15px;">
                            @if(!empty($brandLogoUrl))
                                <img src="{{ $brandLogoUrl }}" alt="Logo" style="height: 50px;"/>
                            @endif

                            @if(!empty($brandName))
                                <h1 style="color: white; font-size: 24px; margin: 0;">{{ $brandName }}</h1>
                            @endif

                            @if(empty($brandLogoUrl) && empty($brandName))
                                <h1 style="color: white; font-size: 24px; margin: 0;">Mi Empresa</h1>
                            @endif
                        </td>
                    </tr>

                    <!-- Contenido dinámico -->
                    @yield('content')

                    <!-- Mensaje general -->
                    <tr>
                        <td style="padding: 0 40px 30px 40px; color: #333333; font-size: 16px; line-height: 1.5;">
                            <p>Si tienes alguna duda, contacta con soporte.</p>
                        </td>
                    </tr>

                    <!-- Pie de página -->
                    <tr>
                        <td style="background-color: #f0f0f0; padding: 15px 40px; text-align: center; color: #999999; font-size: 12px;">
                            &copy; {{ date('Y') }} {{ $brandName ?? 'Mi Empresa' }}. Todos los derechos reservados.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
