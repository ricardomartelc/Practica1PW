{{--
    Gestión de los productos de un puesto (GestionPuestoController@index).
    Necesita: $vendedor y $disponibles (productos que el puesto aún no vende)
--}}
@extends('miPortal.layout')

@push('estilos')
    <link rel="stylesheet" href="{{ asset('css/ecodirecto.css') }}">
@endpush

@section('content')
    <header class="store-head">
        <h1>Gestionar productos</h1>
        <p>{{ $vendedor->nombre_puesto }}</p>
    </header>

    <section class="dash">
        @if (session('ok'))
            <div class="note">{{ session('ok') }}</div>
        @endif

        @if ($errors->any())
            <p class="error-msg">Revisa el formulario: {{ $errors->first() }}</p>
        @endif

        <div class="dash-grid">

            <!-- Productos que ya vende el puesto -->
            <section class="box">
                <h3>Productos del puesto</h3>
                <table class="lines">
                    <tr><th>Producto</th><th class="num">Precio</th><th class="num">Stock</th><th></th></tr>
                    @forelse ($vendedor->productos as $producto)
                        <tr>
                            <td>{{ $producto->nombre }}</td>
                            <td class="num">{{ $producto->pivot->precio_b2c }} €</td>
                            <td class="num">{{ $producto->pivot->stock }}</td>
                            <td class="num">
                                <a href="{{ route('gestion.edit', [$vendedor, $producto]) }}">Editar</a>
                                <form action="{{ route('gestion.destroy', [$vendedor, $producto]) }}" method="POST" style="display: inline"
                                      onsubmit="return confirm('¿Quitar {{ $producto->nombre }} del puesto?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-ghost" style="padding: 2px 10px">Quitar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4">Este puesto todavía no tiene productos.</td></tr>
                    @endforelse
                </table>
            </section>

            <!-- Formulario para añadir un producto del catálogo al puesto -->
            <section class="box">
                <h3>Añadir producto</h3>
                @if ($disponibles->isEmpty())
                    <p class="hint">El puesto ya vende todos los productos del catálogo.</p>
                @else
                    <form action="{{ route('gestion.store', $vendedor) }}" method="POST">
                        @csrf
                        <div class="form-grid">
                            <div class="field wide">
                                <label for="producto_id">Producto</label>
                                <select id="producto_id" name="producto_id">
                                    @foreach ($disponibles as $producto)
                                        <option value="{{ $producto->id }}">{{ $producto->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @include('gestion.partials.campos', ['valores' => null])
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn-story">Añadir</button>
                        </div>
                    </form>
                @endif
            </section>

        </div>

        <p><a href="{{ route('puestos.show', $vendedor) }}" class="btn-ghost">← Volver al puesto</a></p>
    </section>
@endsection
