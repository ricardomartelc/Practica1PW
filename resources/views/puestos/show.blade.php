{{-- Página de un puesto (VendedorController@show). Necesita: $vendedor --}}
@extends('miPortal.layout')

@push('estilos')
    <link rel="stylesheet" href="{{ asset('css/ecodirecto.css') }}">
@endpush

@section('content')
    <header class="store-head">
        <h1>{{ $vendedor->nombre_puesto }}</h1>
        <p>{{ $vendedor->ubicacion }} · {{ $vendedor->entrega === 'envio' ? 'Envío a domicilio' : 'Recogida en el puesto' }}</p>
    </header>

    <section class="dash">
        <section class="box">
            <h3>Productos del puesto</h3>
            <table class="lines">
                <tr><th>Producto</th><th class="num">Precio</th><th class="num">Stock</th></tr>
                @forelse ($vendedor->productos as $producto)
                    <tr>
                        <td><a href="{{ route('productos.show', $producto) }}">{{ $producto->nombre }}</a></td>
                        <td class="num">{{ $producto->pivot->precio_b2c }} € / {{ $producto->unidad }}</td>
                        <td class="num">{{ $producto->pivot->stock }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3">Este puesto todavía no tiene productos.</td></tr>
                @endforelse
            </table>
        </section>

        <p>
            <a href="{{ route('puestos.index') }}" class="btn-ghost">← Volver a puestos</a>
            <a href="{{ route('gestion.index', $vendedor) }}" class="btn-ghost">Gestionar productos</a>
        </p>
    </section>
@endsection
