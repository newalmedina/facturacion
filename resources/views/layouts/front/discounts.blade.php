@if($discounts->active)
<section class="featured-section section-padding"
 style="background-image: url('{{ $discounts->image_path ? asset('storage/' . $discounts->image_path) : '' }}')">
    <div class="section-overlay"></div>

    <div class="container">
           @foreach ($discounts->activeImages as $item)
            <div class="row">

                <div class="col-lg-10 col-12 mx-auto mt-5">
                    <h2 class="mb-3">{{ $item->title}}</h2>

                    <p>{{ $item->subtitle}}</p>

                    @if (!empty( $item->alt_text))
                    <strong>Código promoción: {{ $item->alt_text}}</strong>
                        
                    @endif
                </div>

            </div>
        @endforeach
    </div>
</section>
@endif
{{-- <section class="featured-section section-padding">
    <div class="section-overlay"></div>

    <div class="container">
        <div class="row">

            <div class="col-lg-10 col-12 mx-auto">
                <h2 class="mb-3">Get 32% Discount</h2>

                <p>on every second week of the month</p>

                <strong>Promo Code: BarBerMo</strong>
            </div>

        </div>
    </div>
</section> --}}