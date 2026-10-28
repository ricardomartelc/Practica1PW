{{--
    Editar un producto del puesto (GestionPuestoController@edit).
    Necesita: $vendedor y $producto (con su pivot: precio, stock...)
--}}
@extends('miPortal.layout')

@push('estilos')
    <link rel="stylesheet" href="{{ asset('css/ecodirecto.css') }}">
@endpush

@section('content')
    <header class="store-head">
        <h1>{{ $producto->nombre }}</h1>
        <p>{{ $vendedor->nombre_puesto }}</p>
    </header>

    <section class="dash">
        @if ($errors->any())
            <p class="error-msg">Revisa el formulario: {{ $errors->first() }}</p>
        @endif

        <section class="box">
            <form action="{{ route('gestion.update', [$vendedor, $producto]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-grid">
                    @include('gestion.partials.campos', ['valores' => $producto->pivot])
                </div>
                <div class="form-actions">
                    <a href="{{ route('gestion.index', $vendedor) }}" class="btn-ghost">Cancelar</a>
                    <button type="submit" class="btn-story">Guardar</button>
                </div>
            </form>
        </section>
    </section>
@endsection
