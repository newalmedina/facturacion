
@if($contactForm->active)
<section class="contact-section" id="section_5">
    <div class="section-padding section-bg">
        <div class="container">
            <div class="row">   

                <div class="col-lg-8 col-12 mx-auto">
                    <h2 class="text-center">{{ $contactForm->title }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="section-padding">
        <div class="container">
            <div class="row">

                <div class="col-lg-6 col-12">
                    <h5 class="mb-3"><strong> {{ Str::before($contactForm->subtitle, ' ') }} </strong>{{ Str::after($contactForm->subtitle, ' ') }}</></h5>

                   <p class="text-white d-flex mb-1">
                        <a href="tel:{{ trim($generalSettings->phone, '"') }}" class="site-footer-link">
                            {{ trim($generalSettings->phone, '"') }}
                        </a>
                    </p>

                    <p class="text-white d-flex">
                        <a href="mailto:{{ trim($generalSettings->email, '"') }}" class="site-footer-link">
                            {{ trim($generalSettings->email, '"') }}
                        </a>
                    </p>


                    <ul class="social-icon">
                         @if(!empty($contactForm->facebook_url))
                            <li class="social-icon-item">
                                <a target="_blank" href="{{ $contactForm->facebook_url }}" class="social-icon-link bi-facebook"></a>
                            </li>
                        @endif

                        @if(!empty($contactForm->twitter_url))
                            <li class="social-icon-item">
                                <a target="_blank" href="{{ $contactForm->twitter_url }}" class="social-icon-link bi-twitter"></a>
                            </li>
                        @endif

                        @if(!empty($contactForm->instagram_url))
                            <li class="social-icon-item">
                                <a target="_blank" href="{{ $contactForm->instagram_url }}" class="social-icon-link bi-instagram"></a>
                            </li>
                        @endif

                        @if(!empty($contactForm->youtube_url))
                            <li class="social-icon-item">
                                <a target="_blank" href="{{ $contactForm->youtube_url }}" class="social-icon-link bi-youtube"></a>
                            </li>
                        @endif

                        @if(!empty($contactForm->whatsapp_url))
                            <li class="social-icon-item">
                                <a 
                                    target="_blank" 
                                    href="https://wa.me/{{ preg_replace('/\D/', '', $contactForm->whatsapp_url) }}" 
                                    class="social-icon-link bi-whatsapp">
                                </a>
                            </li>
                        @endif


                    </ul>
                </div>

                <div class="col-lg-5 col-12 contact-block-wrap mt-5 mt-lg-0 pt-4 pt-lg-0 mx-auto">
                    <div class="contact-block">
                        <h6 class="mb-0">
                            <i class="custom-icon bi-shop me-3"></i>

                            <strong>{{ $contactForm->secondary_text }}</strong>

                            <span class="ms-auto">{{ $contactForm->tertiary_text }}</span>
                        </h6>
                    </div>
                </div>

              <div class="col-lg-12 col-12 mx-auto mt-5 pt-5">
               @if(!empty($contactForm->body))
                    <iframe 
                        class="google-map" 
                        src="{!! trim($contactForm->body, '"') !!}" 
                        width="100%" 
                        height="500" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                @endif

            </div>


            </div>
        </div>
    </div>
</section>
@endif

@if($generalSettings->allow_appointment)
<!-- Botón flotante circular -->
<a href="#booking-section" 
   id="floating-booking-btn" 
   title="Pedir Cita">
    📅
</a>
@endif
     @if(!empty($contactForm->whatsapp_url))
    <a 
    id="floating-whatsapp-btn" 
    target="_blank" 
    href="https://wa.me/{{ preg_replace('/\D/', '', $contactForm->whatsapp_url) }}" 
    title="Chatear por WhatsApp">
        <i class="bi-whatsapp"></i>
    </a>
@endif
{{-- <section class="contact-section" id="section_5">
    <div class="section-padding section-bg">
        <div class="container">
            <div class="row">   

                <div class="col-lg-8 col-12 mx-auto">
                    <h2 class="text-center">Conecta con nosotros</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="section-padding">
        <div class="container">
            <div class="row">

                <div class="col-lg-6 col-12">
                    <h5 class="mb-3"><strong>Información</strong> de contacto</h5>

                    <p class="text-white d-flex mb-1">
                        <a href="tel: 120-240-3600" class="site-footer-link">
                            (+49) 
                            120-240-3600
                        </a>
                    </p>

                    <p class="text-white d-flex">
                        <a href="mailto:info@yourgmail.com" class="site-footer-link">
                            hello@barber.beauty
                        </a>
                    </p>

                    <ul class="social-icon">
                        <li class="social-icon-item">
                            <a href="#" class="social-icon-link bi-facebook">
                            </a>
                        </li>

                        <li class="social-icon-item">
                            <a href="#" class="social-icon-link bi-twitter">
                            </a>
                        </li>

                        <li class="social-icon-item">
                            <a href="#" class="social-icon-link bi-instagram">
                            </a>
                        </li>

                        <li class="social-icon-item">
                            <a href="#" class="social-icon-link bi-youtube">
                            </a>
                        </li>

                        <li class="social-icon-item">
                            <a href="#" class="social-icon-link bi-whatsapp">
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="col-lg-5 col-12 contact-block-wrap mt-5 mt-lg-0 pt-4 pt-lg-0 mx-auto">
                    <div class="contact-block">
                        <h6 class="mb-0">
                            <i class="custom-icon bi-shop me-3"></i>

                            <strong>Open Daily</strong>

                            <span class="ms-auto">10:00 AM - 8:00 PM</span>
                        </h6>
                    </div>
                </div>

                <div class="col-lg-12 col-12 mx-auto mt-5 pt-5">
                    <iframe class="google-map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7702.122299518348!2d13.396786616231472!3d52.531268574169616!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47a85180d9075183%3A0xbba8c62c3dc41a7d!2sBarbabella%20Barbershop!5e1!3m2!1sen!2sth!4v1673886261201!5m2!1sen!2sth" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>

            </div>
        </div>
    </div>
</section> --}}