{{-- Lista de productores (ProductorController@index). Necesita: $productores --}}
@extends('miPortal.layout')

@push('estilos')
    <link rel="stylesheet" href="{{ asset('css/ecodirecto.css') }}">
@endpush

@section('content')
    <header class="store-head">
        <h1>Productores</h1>
        <p>Quién produce lo que compras y en qué municipio.</p>
    </header>

    <section class="dash">
        <section class="box">
            <table class="lines">
                <tr><th>Productor</th><th>Municipio</th></tr>
                @foreach ($productores as $productor)
                    <tr>
                        <td><a href="{{ route('productores.show', $productor) }}">{{ $productor->nombre }}</a></td>
                        <td>{{ $productor->municipio }}</td>
                    </tr>
                @endforeach
            </table>
        </section>
    </section>
@endsection
