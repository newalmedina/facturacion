<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name'))</title>
    
    <!-- CSS FILES -->        
    <link rel="preconnect" href="https://fonts.googleapis.com">
    
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    
    <link href="{{ asset('assets/front/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/front/css/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/front/css/templatemo-barber-shop.css') }}" rel="stylesheet">
    <!-- SWIPER CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Unbounded:wght@300;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />

    
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

            @include('layouts.front.partials.sidebar')
            
            <div class="col-md-8 ms-sm-auto col-lg-9 p-0">

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

    </div>
</div>
</body>
</html>
