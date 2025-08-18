@if($gallery->active)
<section class="about-section section-padding" id="section_2">
    <div class="container">
        <div class="row">

            <div class="col-lg-12 col-12 mx-auto">
                <h2 class="mb-4">{!! $gallery->title !!}</h2>

                <div class="border-bottom pb-3 mb-5" style="color: #b462e2; font-weight: bold;font-size: 1.2em;">
                    {{-- Aquí puedes agregar un subtítulo o descripción breve --}}
                   {!! $gallery->subtitle !!}
                </div>
            </div>
          @if ($gallery->activeImages->count() > 0)
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
<style>
/* Color de las flechas next/prev */
.swiper-button-next,
.swiper-button-prev {
    color: #b462e2 !important;
     height: 50px;         /* Más alto */
    background-size: 50% 50%; /* Ajusta el tamaño de la flecha */
}

/* Color de los puntos de paginación */
.swiper-pagination-bullet {
    background: #b462e2 !important;
        width: 16px;          /* ancho */
    height: 16px;    
}

/* Opción: cambiar el color del bullet activo */
.swiper-pagination-bullet-active {
    background: #b462e2 !important;
}
</style>
    <div class="swiper mySwiper">
        <div class="swiper-wrapper">
            @foreach ($gallery->activeImages as $item)
                @if ($item->image_path)
                <div class="swiper-slide d-flex justify-content-center">
                    <div class="custom-block-bg-overlay-wrap position-relative" style="width: 90%;">
                        <img src="{{ asset('storage/' . $item->image_path) }}"
                             class="custom-block-bg-overlay-image img-fluid"
                             alt="{{ $item->alt_text }}"
                             style="width: 100%; height: auto; display: block; z-index: 1;">
                        
                        <!-- Si quieres texto u overlay encima -->
                        {{-- <div class="overlay-text position-absolute top-0 start-0 w-100 h-100" style="z-index: 2;">
                            Aquí tu contenido encima
                        </div> --}}
                    </div>
                </div>
                @endif
            @endforeach
        </div>

        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-pagination"></div>
    </div>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script>
        const swiper = new Swiper(".mySwiper", {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: true,
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            breakpoints: {
                576: { slidesPerView: 1 },
                768: { slidesPerView: 2 },
                992: { slidesPerView: 3 },
                1200: { slidesPerView: 4 },
            }
        });
    </script>
@endif


        </div>
    </div>
</section>
@endif

{{-- <section class="about-section section-padding" id="section_2">
    <div class="container">
        <div class="row">

            <div class="col-lg-12 col-12 mx-auto">
                <h2 class="mb-4">Expertas en Trenzas y Estilos Únicos</h2>

                <div class="border-bottom pb-3 mb-5">
                    <p>En <strong>By Estrella Salón de Trenzas</strong> reinventamos el arte de las trenzas con técnicas innovadoras y diseños personalizados para cada estilo y personalidad. Nuestro equipo apasionado combina creatividad y precisión para que luzcas radiante, auténtica y siempre a la moda.</p>
                    <p>¡Ven y descubre por qué somos el referente en trenzas modernas y tradicionales! Más que un salón, somos tu espacio para expresar tu estilo único.</p>
                </div>
            </div>

            <h6 class="mb-5">Conoce a Nuestro Equipo de Expertas</h6>

            <div class="col-lg-5 col-12 custom-block-bg-overlay-wrap me-lg-5 mb-5 mb-lg-0">
                <img src =" {{ asset('assets/front/images/barber/portrait-male-hairdresser-with-scissors.jpg') }}" class="custom-block-bg-overlay-image img-fluid" alt="Estilista experta en trenzas">

                <div class="team-info d-flex align-items-center flex-wrap">
                    <p class="mb-0"><strong>Estrella</strong> – Maestra de Trenzas</p>

                    <ul class="social-icon ms-auto">
                        <li class="social-icon-item">
                            <a href="#" class="social-icon-link bi-facebook" aria-label="Facebook Estrella"></a>
                        </li>

                        <li class="social-icon-item">
                            <a href="#" class="social-icon-link bi-instagram" aria-label="Instagram Estrella"></a>
                        </li>

                        <li class="social-icon-item">
                            <a href="#" class="social-icon-link bi-whatsapp" aria-label="WhatsApp Estrella"></a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-5 col-12 custom-block-bg-overlay-wrap mt-4 mt-lg-0 mb-5 mb-lg-0">
                <img src =" {{ asset('assets/front/images/barber/portrait-mid-adult-bearded-male-barber-with-folded-arms.jpg') }}" class="custom-block-bg-overlay-image img-fluid" alt="Estilista especialista en trenzas creativas">

                <div class="team-info d-flex align-items-center flex-wrap">
                    <p class="mb-0"><strong>Samira</strong> – Artista en Trenzas Creativas</p>

                    <ul class="social-icon ms-auto">
                        <li class="social-icon-item">
                            <a href="#" class="social-icon-link bi-facebook" aria-label="Facebook Samira"></a>
                        </li>

                        <li class="social-icon-item">
                            <a href="#" class="social-icon-link bi-instagram" aria-label="Instagram Samira"></a>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</section> --}}
