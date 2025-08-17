@if($aboutUs->active)
<section class="about-section section-padding" id="section_2">
    <div class="container">
        <div class="row">

            <div class="col-lg-12 col-12 mx-auto">
                <h2 class="mb-4">{!! $aboutUs->title !!}</h2>

                <div class="border-bottom pb-3 mb-5">
                    {!! $aboutUs->body !!}
                </div>
            </div>
            @if ($aboutUs->activeImages->count()>0)
                <h6 class="mb-5">Conoce a Nuestro Equipo de Expertas</h6>

                @foreach ($aboutUs->activeImages as $item)
                    <div class="col-lg-5 col-12 custom-block-bg-overlay-wrap me-lg-5 mb-5 mb-lg-0 mt-5">
                        @if ($item->image_path)
                        <img src ="{{ asset('storage/' . $item->image_path)}}" class="custom-block-bg-overlay-image img-fluid" alt="{{  $item->title }}">
                            
                        @endif

                        <div class="team-info d-flex align-items-center flex-wrap">
                            <p class="mb-0"><strong>{!! $item->title !!}</strong> – {!! $item->alt_text !!}</p>

                            {{-- <ul class="social-icon ms-auto">
                                <li class="social-icon-item">
                                    <a href="#" class="social-icon-link bi-facebook" aria-label="Facebook Estrella"></a>
                                </li>

                                <li class="social-icon-item">
                                    <a href="#" class="social-icon-link bi-instagram" aria-label="Instagram Estrella"></a>
                                </li>

                                <li class="social-icon-item">
                                    <a href="#" class="social-icon-link bi-whatsapp" aria-label="WhatsApp Estrella"></a>
                                </li>
                            </ul> --}}
                        </div>
                    </div>
                    
                @endforeach
                
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
