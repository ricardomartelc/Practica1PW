{{-- Práctica 4, Bloque 2 — JavaScript: manejo de formularios / sensores (Ricardo, RMC)
     Ruta: /practica4/sensoresRMC   ·   Menú: Práctica4 > Sensores RMC
     Necesita: $sensores (sensores de la BD, con su vendedor)
     Las funciones JavaScript están en public/js/practica4SensoresRMC.js --}}
@extends('miPortal.layout')

@push('estilos')
    <style>
        .formRMC { display: flex; flex-direction: column; gap: 6px; max-width: 720px; margin: 0 auto 40px; }
        .formRMC fieldset { border: 1px solid var(--border); border-radius: 8px; padding: 12px 16px 16px; margin: 12px 0 0; }
        .formRMC legend { font-weight: 700; padding: 0 6px; }
        .formRMC label { display: block; font-weight: 600; margin-top: 10px; }
        .formRMC input[type=text], .formRMC select, .formRMC textarea { width: 100%; font: inherit; padding: 8px; border: 1px solid var(--border); border-radius: 6px; }
        .formRMC input[readonly] { background: #f4f1ea; }
        .formRMC textarea { font-family: monospace; font-size: 0.85rem; }
        .formRMC .checkRMC { display: flex; align-items: center; gap: 8px; margin-top: 14px; font-weight: 600; }
        .botonesRMC { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 16px; }
        .botonesRMC button { font: inherit; padding: 8px 14px; border: none; border-radius: 6px; background: var(--moss); color: #fff; cursor: pointer; }
        /* Aspecto de los botones deshabilitados */
        .botonesRMC button:disabled { background: #c9c3b6; cursor: not-allowed; }
        .infoRMC { max-width: 720px; margin: 0 auto; font-size: 0.9rem; color: var(--muted); }
    </style>
@endpush

@section('content')
    <header style="text-align: center; padding: 30px 0 10px;">
        <h1>Práctica 4 · Sensores (RMC)</h1>
        <p>Histórico de lecturas de los sensores de los puestos: genera las llamadas a la API que guarda los valores.</p>
    </header>

    <p class="infoRMC">
        Con <strong>Solo local</strong> marcado, cada línea del registro es la URL completa de la API
        <code>POST /api/sensores/{sensor}/lectura</code> con el parámetro <code>valor</code> (no se envía).
        Desmarcado, las lecturas se guardan en el servidor y el registro muestra la respuesta.
    </p>

    {{-- Botones type="button": no envían el formulario ni recargan la página --}}
    <form class="formRMC">

        <fieldset>
            <legend>Sensor</legend>

            <label for="sensor_id">Sensor (sensor_id)</label>
            <select id="sensor_id">
                <option value="">-- Elige un sensor --</option>
                {{-- Sensores reales de la BD. Los data-* guardan datos que lee el JS --}}
                @foreach ($sensores as $sensor)
                    <option value="{{ $sensor->id }}"
                            data-descripcion="{{ $sensor->tipo }} · {{ $sensor->vendedor->nombre_puesto ?? 'sin puesto' }} · umbral {{ $sensor->umbral_min }} a {{ $sensor->umbral_max }}"
                            data-periodicidad="{{ $sensor->periodicidad ?? '' }}">
                        {{ $sensor->id }} — {{ $sensor->codigo_dispositivo }} ({{ $sensor->tipo }})
                    </option>
                @endforeach
            </select>

            <label for="descripcion">Descripción</label>
            <input type="text" id="descripcion" readonly placeholder="Se rellena al elegir un sensor">

            <label for="periodicidad">Periodicidad de las muestras</label>
            <input type="text" id="periodicidad" readonly placeholder="minuto, hora o día">

            <label for="valor">Valor de la lectura (valor)</label>
            <input type="text" id="valor" placeholder="Ej.: 6.5  (temperatura en ºC o humedad en %)">
        </fieldset>

        <fieldset>
            <legend>Otros</legend>

            <label for="repetirNveces">Repetir N veces (repetirNveces)</label>
            <input type="text" id="repetirNveces" placeholder="Entero de 1 a 100. Ej.: 5">

            <label for="incrementoValor">Incremento del valor en cada repetición (incrementoValor)</label>
            <input type="text" id="incrementoValor" placeholder="Puede ser negativo o decimal. Ej.: 0.5">

            <label class="checkRMC">
                <input type="checkbox" id="soloLocal" checked>
                Solo local (generar las URLs sin llamar a la API)
            </label>

            <label for="textarea">Registro de llamadas a la API</label>
            <textarea id="textarea" rows="10" placeholder="Aquí aparecerán las URLs generadas..."></textarea>
        </fieldset>

        <div class="botonesRMC">
            <button type="button" id="anadir1vez">Añadir 1 vez</button>
            <button type="button" id="anadirNveces">Añadir N veces</button>
            <button type="button" id="cargarSensores">Cargar sensores</button>
            <button type="button" id="cargarDatosSensor">Cargar datos del sensor</button>
            <button type="button" id="borrar">Borrar</button>
        </div>
    </form>

    {{-- JavaScript de este bloque (al final, cuando los elementos ya existen) --}}
    <script src="{{ asset('js/practica4SensoresRMC.js') }}"></script>
@endsection
