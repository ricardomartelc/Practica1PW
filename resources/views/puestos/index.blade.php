{{-- Lista de puestos (VendedorController@index). Necesita: $vendedores --}}
@extends('miPortal.layout')

@push('estilos')
    <link rel="stylesheet" href="{{ asset('css/ecodirecto.css') }}">
@endpush

@section('content')
    <header class="store-head">
        <h1>Puestos</h1>
        <p>Los puestos de los mercados de Gran Canaria que venden en Ecodirecto.</p>
    </header>

    <section class="dash">
        <div class="products">
            @foreach ($vendedores as $vendedor)
                @include('puestos.partials.tarjeta', ['vendedor' => $vendedor])
            @endforeach
        </div>
    </section>
@endsection
