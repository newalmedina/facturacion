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
    
       <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- SWIPER CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
<!-- Font Awesome 5 -->
<!-- Font Awesome 5 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">


@livewireStyles
    @stack('styles')

</head>
<body>


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
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>


@livewireScripts
    @stack('scripts')
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
