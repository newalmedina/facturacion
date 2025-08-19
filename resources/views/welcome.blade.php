{{-- resources/views/welcome.blade.php --}}

@extends('layouts.front.default')

@section('title', $generalSettings->brand_name. " - Home")

    @section('content')
    @include('layouts.front.jumbotron')
    @include('layouts.front.booking')
    @include('layouts.front.about')
    @include('layouts.front.discounts')
    @include('layouts.front.services')
    @include('layouts.front.price_list')
    @include('layouts.front.gallery')
    @include('layouts.front.contact') 
@endsection
