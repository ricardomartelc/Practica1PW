<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil Privado - {{ $nombre ?? 'Usuario' }}</title>
    <!-- Carga la hoja de estilos del grupo -->
    <link rel="stylesheet" href="{{ asset('css/grupo1.css') }}">
</head>
<body>

    <!-- Incluimos la cabecera parcial -->
    @include('partials.header')

    <main class="container">
        <!-- Aquí se mostrará el contenido del archivo HTML privado -->
        {!! $contenido ?? '' !!}
    </main>

    <!-- Incluimos el pie de página parcial -->
    @include('partials.footer')

</body>
</html>