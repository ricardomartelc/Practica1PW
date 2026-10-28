{{-- Lista de productos (ProductoController@index). Necesita: $productos --}}
@extends('miPortal.layout')

@push('estilos')
    <link rel="stylesheet" href="{{ asset('css/ecodirecto.css') }}">
@endpush

@section('content')
    <header class="store-head">
        <h1>Productos</h1>
        <p>Productos de los mercados de Gran Canaria.</p>
    </header>

    <section class="dash">
        <div class="products">
            @foreach ($productos as $producto)
                @include('productos.partials.tarjeta', ['producto' => $producto])
            @endforeach
        </div>
    </section>
@endsection
