    @php
    use App\Models\Setting;
    use App\Models\State;
    use App\Models\City;

$settings = Setting::first();
 $generalSettings = $settings?->general;
        // $generalSettings?->brand_name = $generalSettings?->brand_name ?? config('app.name', 'Mi Empresa');
        $state = State::find(trim($generalSettings->state_id, '"'));
        $city = City::find(trim($generalSettings->city_id, '"'));
@endphp
<footer class="site-footer">
    <div class="container">
        <div class="row">

            <div class="col-lg-12 col-12">
                <h4 class="site-footer-title mb-4">Nuestro Local</h4>
            </div>

            {{-- <div class="col-lg-4 col-md-6 col-11"> --}}
            <div class=" col-12">
                <div class="site-footer-thumb">
                    <strong class="mb-1"> {{ trim($generalSettings->brand_name, '"') }}</strong>

                    <p> {{ trim($generalSettings->address, '"') }}, {{ trim($generalSettings->postal_code, '"') }}, {{ $city->name??"" }}</p>
                </div>
            </div>

            {{-- <div class="col-lg-4 col-md-6 col-11">
                <div class="site-footer-thumb">
                    <strong class="mb-1">Behrenstraße</strong>

                    <p>Behrenstraße 27, 10117 Berlin, Germany</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-11">
                <strong class="mb-1">Weinbergsweg</strong>

                <p>Weinbergsweg 23, 10119 Berlin, Germany</p>
            </div> --}}
        </div>
    </div>

    <div class="site-footer-bottom">
        <div class="container">
            <div class="row align-items-center">

                <p class="copyright-text mb-0">
                    Copyright © {{ date('Y') }}, {{ trim($generalSettings->brand_name, '"') }}:
                </p>


                <div class="col-lg-2 col-md-3 col-3 mt-lg-4 ms-auto">
                    <a href="#section_1" class="back-top-icon smoothscroll" title="Back Top">
                        <i class="bi-arrow-up-circle"></i>
                    </a>
                </div>

            </div>
        </div>
    </div>
</footer>

{{-- <footer class="site-footer">
    <div class="container">
        <div class="row">

            <div class="col-lg-12 col-12">
                <h4 class="site-footer-title mb-4">Our Branches</h4>
            </div>

            <div class="col-lg-4 col-md-6 col-11">
                <div class="site-footer-thumb">
                    <strong class="mb-1">Grünberger</strong>

                    <p>Grünberger Str. 31, 10245 Berlin, Germany</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-11">
                <div class="site-footer-thumb">
                    <strong class="mb-1">Behrenstraße</strong>

                    <p>Behrenstraße 27, 10117 Berlin, Germany</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-11">
                <strong class="mb-1">Weinbergsweg</strong>

                <p>Weinbergsweg 23, 10119 Berlin, Germany</p>
            </div>
        </div>
    </div>

    <div class="site-footer-bottom">
        <div class="container">
            <div class="row align-items-center">

                <div class="col-lg-8 col-12 mt-4">
                    <p class="copyright-text mb-0">Copyright © 2036 Barber Shop 
                    - Design: <a href="https://templatemo.com" rel="nofollow" target="_blank">TemplateMo</a></p>
                </div>

                <div class="col-lg-2 col-md-3 col-3 mt-lg-4 ms-auto">
                    <a href="#section_1" class="back-top-icon smoothscroll" title="Back Top">
                        <i class="bi-arrow-up-circle"></i>
                    </a>
                </div>

            </div>
        </div>
    </div>
</footer> --}}