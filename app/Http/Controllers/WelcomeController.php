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
        $jumbotron = CmsContent::findBySlug('header-jumbotron');
        $aboutUs = CmsContent::findBySlug('about-us');
        $discounts = CmsContent::findBySlug('discounts');
        $service = CmsContent::findBySlug('services');
        $priceList = CmsContent::findBySlug('price-catalog');
        $contactForm = CmsContent::findBySlug('contact-form');
        $gallery = CmsContent::findBySlug('gallery');


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
