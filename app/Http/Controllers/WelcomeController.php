<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\CmsContent;
use App\Models\Setting;
use App\Models\State;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {

        $settings = Setting::first();
        $generalSettings = $settings?->general;

        if (!$generalSettings->has_home) {
            abort(404);
        }
        // 🔹 Cargar todos los CmsContent en una sola query
        $slugs = [
            'header-jumbotron',
            'about-us',
            'discounts',
            'services',
            'price-catalog',
            'contact-form',
            'gallery'
        ];

        $cmsContents = CmsContent::whereIn('slug', $slugs)
            ->get()
            ->keyBy('slug');

        $jumbotron   = $cmsContents['header-jumbotron'] ?? null;
        $aboutUs     = $cmsContents['about-us'] ?? null;
        $discounts   = $cmsContents['discounts'] ?? null;
        $service     = $cmsContents['services'] ?? null;
        $priceList   = $cmsContents['price-catalog'] ?? null;
        $contactForm = $cmsContents['contact-form'] ?? null;
        $gallery     = $cmsContents['gallery'] ?? null;
        // dd($generalSettings);
        // $generalSettings?->brand_name = $generalSettings?->brand_name ?? config('app.name', 'Mi Empresa');
        $state = State::find(trim($generalSettings->state_id, '"'));
        $city = City::find(trim($generalSettings->city_id, '"'));

        return view('welcome', [
            'jumbotron' => $jumbotron,
            'aboutUs' => $aboutUs,
            'service' => $service,
            'discounts' => $discounts,
            'contactForm' => $contactForm,
            'priceList' => $priceList,
            'gallery' => $gallery,
            'generalSettings' => $generalSettings,
            'state' => $state,
            'city' => $city,
        ]);
    }
}
