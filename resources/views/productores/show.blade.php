{{-- Ficha de un productor (ProductorController@show). Necesita: $productor --}}
@extends('miPortal.layout')

@push('estilos')
    <link rel="stylesheet" href="{{ asset('css/ecodirecto.css') }}">
@endpush

@section('content')
    <header class="store-head">
        <h1>{{ $productor->nombre }}</h1>
        <p>{{ $productor->municipio }}, Gran Canaria</p>
    </header>

    <section class="dash">
        <section class="box">
            <h3>Lo que produce</h3>
            <table class="lines">
                <tr><th>Producto</th><th class="num">Stock en la finca</th></tr>
                @forelse ($productor->productos as $producto)
                    <tr>
                        <td><a href="{{ route('productos.show', $producto) }}">{{ $producto->nombre }}</a></td>
                        <td class="num">{{ $producto->pivot->stock }} {{ $producto->unidad }}</td>
                    </tr>
                @empty
                    <tr><td colspan="2">Sin productos registrados.</td></tr>
                @endforelse
            </table>
        </section>

        <p><a href="{{ route('productores.index') }}" class="btn-ghost">← Volver a productores</a></p>
    </section>
@endsection
