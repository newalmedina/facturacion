<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\CmsContent;
use App\Models\Setting;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class FrontBookingController extends Controller
{
    public function index()
    {
        // 🔹 Cargar configuración general desde cache 1h
        $generalSettings = Cache::remember('general_settings', 3600, function () {
            $settings = Setting::first();
            return $settings?->general;
        });

        if (!$generalSettings?->allow_appointment) {
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



        return view('front.appointments', [
            'jumbotron'       => $jumbotron,
            'aboutUs'         => $aboutUs,
            'service'         => $service,
            'discounts'       => $discounts,
            'contactForm'     => $contactForm,
            'priceList'       => $priceList,
            'gallery'         => $gallery,
            'generalSettings' => $generalSettings,
            'pageTitle'       => "Pedir cita",
        ]);
    }
}
