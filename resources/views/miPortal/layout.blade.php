<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal ODS 12 - Proyecto</title>
    <link rel="stylesheet" href="{{ asset('css/grupo1.css') }}">
</head>
<body>

    @include('miportal.partials.header')

    <main class="container">
        @yield('content')
    </main>

    @include('partials.footer')

</body>
</html>