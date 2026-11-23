{{-- Práctica 4, Bloque 4 — JavaScript: librerías externas / gráficos (Ricardo, RMC)
     Ruta: /practica4/graficosRMC   ·   Menú: Práctica4 > Gráficos RMC
     Librería: Chart.js 4.4.1 (copia local en public/js/vendor/chart.umd.min.js)
     Nuestro código: public/js/practica4GraficosRMC.js --}}
@extends('miPortal.layout')

@push('estilos')
    <style>
        .graficosRMC { max-width: 820px; margin: 0 auto 40px; }
        .barraRMC { display: flex; align-items: center; gap: 14px; flex-wrap: wrap; margin-bottom: 10px; }
        .barraRMC button { font: inherit; padding: 8px 14px; border: none; border-radius: 6px; background: var(--moss); color: #fff; cursor: pointer; }
        .barraRMC button:disabled { background: #c9c3b6; cursor: not-allowed; }
        #mensajeRMC { margin: 0; font-weight: 600; }
        #mensajeRMC.error { color: #b3261e; }
        .cajaGraficoRMC { border: 1px solid var(--border); border-radius: 8px; padding: 12px; margin-top: 18px; }
        .infoRMC { font-size: 0.9rem; color: var(--muted); }
    </style>
@endpush

@section('content')
    <header style="text-align: center; padding: 30px 0 10px;">
        <h1>Práctica 4 · Gráficos de sensores (RMC)</h1>
        <p>Valor actual de cada sensor frente a sus umbrales mínimo y máximo.</p>
    </header>

    <section class="graficosRMC">
        <p class="infoRMC">
            Datos en vivo de la API <code>GET /api/sensores</code>. Cada sensor muestra tres barras:
            umbral mínimo (gris), valor actual (verde si está dentro del umbral, rojo si está fuera) y umbral máximo (gris).
            La API solo guarda el último valor de cada sensor, por eso no hay evolución en el tiempo.
        </p>

        <div class="barraRMC">
            <button type="button" id="actualizar">Actualizar</button>
            <p id="mensajeRMC">Cargando datos...</p>
        </div>

        {{-- Un gráfico por tipo de sensor, porque temperatura y humedad tienen escalas distintas --}}
        <div class="cajaGraficoRMC">
            <canvas id="graficoTemperatura"></canvas>
        </div>
        <div class="cajaGraficoRMC">
            <canvas id="graficoHumedad"></canvas>
        </div>
    </section>

    {{-- Primero la librería (define Chart) y después nuestro código, que la usa --}}
    <script src="{{ asset('js/vendor/chart.umd.min.js') }}"></script>
    <script src="{{ asset('js/practica4GraficosRMC.js') }}"></script>
@endsection
