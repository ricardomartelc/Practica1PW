{{-- Ficha de un producto (ProductoController@show). Necesita: $producto --}}
@extends('miPortal.layout')

@push('estilos')
    <link rel="stylesheet" href="{{ asset('css/ecodirecto.css') }}">
@endpush

@section('content')
    <header class="store-head">
        <h1>{{ $producto->nombre }}</h1>
        <p>{{ $producto->categoria }} · se vende por {{ $producto->unidad }}</p>
    </header>

    <section class="dash">
        <section class="box">
            <h3>Dónde comprarlo</h3>
            <table class="lines">
                <tr><th>Puesto</th><th class="num">Precio</th><th class="num">Stock</th></tr>
                @foreach ($producto->vendedores as $vendedor)
                    <tr>
                        <td>{{ $vendedor->nombre_puesto }}</td>
                        <td class="num">{{ $vendedor->pivot->precio_b2c }} €</td>
                        <td class="num">{{ $vendedor->pivot->stock }}</td>
                    </tr>
                @endforeach
            </table>
        </section>

        <p><a href="{{ route('productos.index') }}" class="btn-ghost">← Volver a productos</a></p>
    </section>
@endsection
