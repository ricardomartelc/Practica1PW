<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Personal - {{ ucfirst($nombre ?? '') }}</title>
    <link rel="stylesheet" href="{{ asset('css/grupo1.css') }}">
</head>
<body>

    @include('partials.header')

    <main class="container">
        @php
            $nombreCapitalizado = ucfirst(strtolower($nombre ?? ''));
            $path = base_path("practica/{$nombreCapitalizado}Privado.html");
            $contenido = '';

            if (file_exists($path)) {
                $html = file_get_contents($path);
                if (preg_match('/<body[^>]*>(.*?)<\/body>/s', $html, $matches)) {
                    $contenido = $matches[1];
                    $contenido = preg_replace('/<nav\b[^>]*>(.*?)<\/nav>/is', '', $contenido);
                } else {
                    $contenido = $html;
                }
            }
        @endphp

        @if(!empty($contenido))
            {!! $contenido !!}
        @else
            <div class="error-container">
                <h2>Página no encontrada</h2>
                <p>El perfil privado de "{{ $nombre }}" no existe o no se encuentra disponible.</p>
            </div>
        @endif
    </main>

    @include('partials.footer')

</body>
</html>