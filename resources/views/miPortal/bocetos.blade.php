@extends('miPortal.layout')

@push('estilos')
    <link rel="stylesheet" href="{{ asset('css/bocetos.css') }}">
@endpush

@section('content')
    <!-- Índice de los bocetos estáticos de la Práctica 2, Bloque 4 (casos de uso).
         Las páginas están en public/bocetos y usan los datos de ecodirecto_datos.sql. -->
    <header class="store-head">
        <h1>Bocetos de Ecodirecto</h1>
        <p>Cómo se verían en la web los cuatro casos de uso del modelo de datos.</p>
    </header>

    <div class="dash">
        <section class="box">
            <h3>Páginas</h3>
            <table class="lines">
                <tr><th>Caso</th><th>Qué muestra</th><th>Boceto</th></tr>
                <tr>
                    <td>1</td><td>Un particular compra en un puesto</td>
                    <td><a href="/bocetos/caso1_puesto.html">Puesto</a> · <a href="/bocetos/caso1_confirmacion.html">Confirmación</a></td>
                </tr>
                <tr>
                    <td>2</td><td>Una empresa hace un pedido al por mayor</td>
                    <td><a href="/bocetos/caso2_catalogo_empresa.html">Catálogo empresa</a></td>
                </tr>
                <tr>
                    <td>3</td><td>Alerta de un sensor y descuento</td>
                    <td><a href="/bocetos/caso3_panel_vendedor.html">Panel vendedor</a> · <a href="/bocetos/caso3_portada.html">Portada</a></td>
                </tr>
                <tr>
                    <td>4</td><td>Un vendedor gestiona su puesto y su stock</td>
                    <td><a href="/bocetos/caso4_gestion_puesto.html">Gestión del puesto</a></td>
                </tr>
            </table>
        </section>
    </div>
@endsection
