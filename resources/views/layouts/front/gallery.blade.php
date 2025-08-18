@if($gallery->active && $gallery->activeImages->count() > 0)
<section class="about-section section-padding" id="section_2">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-12 mx-auto">
                <h2 class="mb-4">{!! $gallery->title !!}</h2>
                <div class="border-bottom pb-3 mb-5" style="color: #b462e2; font-weight: bold;font-size: 1.2em;">
                   {!! $gallery->subtitle !!}
                </div>
            </div>

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
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>

                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
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
@endpush
@endif
