<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name'))</title>
    
    <!-- CSS FILES -->        
    <link rel="preconnect" href="https://fonts.googleapis.com">
    
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Unbounded:wght@300;500&display=swap" rel="stylesheet">

    <link href="{{ asset('assets/front/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/front/css/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/front/css/templatemo-barber-shop.css') }}" rel="stylesheet">
    <!-- SWIPER CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
<!-- Font Awesome 5 -->
<!-- Font Awesome 5 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        /* ... tu CSS existente de botones flotantes ... */
/* 
/* Swiper styles */
.swiper-button-next,
.swiper-button-prev {
    color: #b462e2 !important;
    height: 50px;
    background-size: 50% 50%;
}
.swiper-pagination-bullet {
    background: #b462e2 !important;
    width: 16px;
    height: 16px;
}
.swiper-pagination-bullet-active {
    background: #b462e2 !important;
} */
#floating-booking-btn {
    position: fixed;          /* Siempre visible */
    right: 20px;              /* Separado del borde derecho */
    top: 50%;                 /* Centrado verticalmente */
    transform: translateY(-50%);
    z-index: 9999;
    width: 60px;              /* Tamaño circular */
    height: 60px;
    border-radius: 50%;       /* Hace el círculo */
    background-color: #dd93ec; /* Color Bootstrap primary */
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;          /* Tamaño del icono o emoji */
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    text-decoration: none;
    transition: transform 0.2s, background-color 0.2s;
}

#floating-booking-btn:hover {
    transform: translateY(-50%) scale(1.1);
    background-color: #581177; /* Color más oscuro al pasar el mouse */
}

/*---------------------------------------
  FLOATING WHATSAPP BUTTON
-----------------------------------------*/
#floating-whatsapp-btn {
    position: fixed;
    right: 20px;
    top: calc(50% + 80px);   /* 80px debajo del botón de cita */
    transform: translateY(-50%);
    z-index: 9999;

    width: 60px;
    height: 60px;
    border-radius: 50%;

    background: #25D366;       /* Color oficial de WhatsApp */
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);

    text-decoration: none;
    transition: transform 0.2s, background-color 0.2s;
}

#floating-whatsapp-btn:hover {
    transform: translateY(-50%) scale(1.1);
    background: #1ebe57;       /* Verde más oscuro al hover */
}



</style>
@livewireStyles
    @stack('styles')
</head>
<body>
    @php
    use App\Models\Setting;

$settings = Setting::first();
$generalSettings = $settings?->general;
@endphp

    <div class="container-fluid">
        <div class="row">
                    </div>
    </div>
    <!-- Header -->
    
    <div class="container-fluid">
        <div class="row">

            @include('layouts.front.partials.sidebar_simple')
            
        
            <div class="col-md-8 ms-sm-auto col-lg-9 p-0">

                   <section class="hero-section-simple d-flex justify-content-center align-items-center" id=""
                        >

                            <div class="container">
                                <div class="row">

                                    <div class="col-lg-8 col-12">
                                           <h2 class="text-white mb-lg-3 mb-4"><strong> {{ Str::before($pageTitle, ' ') }} <em>{{ Str::after($pageTitle, ' ') }}</em></strong></h2>
                                        {{-- <h2 class="text-white mb-lg-3 mb-4"><strong> Pide <em>Cita</em></strong></h2> --}}
                                        
                                    </div>
                                </div>
                            </div>

                        
                        </section>
                @yield('content')

                @include('layouts.front.partials.footer')  
              
            
        </div>

    <!-- JAVASCRIPT FILES -->
    
    
    <script src="{{ asset('assets/front/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/front/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/front/js/click-scroll.js') }}"></script>
    <script src="{{ asset('assets/front/js/custom.js') }}"></script>
    
    <!-- SWIPER JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    
    @stack('scripts')
    @livewireScripts
 @if(!empty($contactForm->whatsapp_url))
    <a 
    id="floating-whatsapp-btn" 
    target="_blank" 
    href="https://wa.me/{{ preg_replace('/\D/', '', $contactForm->whatsapp_url) }}" 
    title="Chatear por WhatsApp">
        <i class="bi-whatsapp"></i>
    </a>
@endif
    </div>
</div>
</body>
</html>
