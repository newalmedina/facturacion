{{-- resources/views/welcome.blade.php --}}

@extends('layouts.front.default')

@section('title', 'Página de Bienvenida')

    @section('content')
    @include('layouts.front.jumbotron')
    @include('layouts.front.about')
    @include('layouts.front.discounts')
    @include('layouts.front.services')
    @include('layouts.front.appointments')
    @include('layouts.front.price_list')
    @include('layouts.front.contact') 
@endsection
