{{-- Práctica 4, Bloque 1 — JavaScript: manejo básico del DOM (Ricardo, RMC)
     Ruta: /practica4/basicoRMC   ·   Menú: Práctica4 > Básico RMC
     Las funciones JavaScript están en public/js/practica4RMC.js --}}
@extends('miPortal.layout')

{{-- Estilos solo de esta página (se insertan en el <head> del layout) --}}
@push('estilos')
    <style>
        .formRMC { display: flex; flex-direction: column; gap: 6px; max-width: 640px; margin: 0 auto 40px; }
        .formRMC label { font-weight: 600; margin-top: 12px; }
        .formRMC input, .formRMC textarea { font: inherit; padding: 8px; border: 1px solid var(--border); border-radius: 6px; }
        .botonesRMC { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 16px; }
        .botonesRMC button { font: inherit; padding: 8px 14px; border: none; border-radius: 6px; background: var(--moss); color: #fff; cursor: pointer; }

        /* Aspecto inicial del elemento que modifica boton3 */
        #elementoRMC { max-width: 640px; margin: 24px auto; padding: 16px; border: 2px dashed var(--rust); border-radius: 8px; }
    </style>
@endpush

@section('content')
    <header style="text-align: center; padding: 30px 0 10px;">
        <h1>Práctica 4 · JavaScript básico (RMC)</h1>
        <p>Manejo básico del DOM: añadir texto, temporizadores y cambio de estilos.</p>
    </header>

    {{-- Elemento propio que cambia boton3. Solo se modifica este. --}}
    <p id="elementoRMC">
        🌱 Consejo ODS 12: antes de comprar, pregúntate si lo necesitas de verdad.
        Reutilizar, reparar y comprar a productores locales reduce residuos.
    </p>

    {{-- Formulario: los botones son type="button" para que NO envíen el formulario ni recarguen la página --}}
    <form class="formRMC">
        <label for="text-1">Texto 1: frase a añadir o declaración CSS</label>
        <input type="text" id="text-1" placeholder="Ej.: Llevo mi propia bolsa  ·  o  background-color: yellow">

        <label for="textarea">Registro de buenas prácticas sostenibles</label>
        <textarea id="textarea" rows="8" placeholder="Aquí irán apareciendo los textos añadidos..."></textarea>

        <label for="text-2">Texto 2: segundos del temporizador (entero de 1 a 60)</label>
        <input type="text" id="text-2" placeholder="Ej.: 5">

        <div class="botonesRMC">
            <button type="button" id="boton1">Añadir texto</button>
            <button type="button" id="boton2">Repetir cada segundo</button>
            <button type="button" id="boton3">Aplicar estilo al consejo</button>
        </div>
    </form>

    {{-- Fichero JavaScript con todas las funciones. Va al final para que los elementos ya existan cuando se ejecute. --}}
    <script src="{{ asset('js/practica4RMC.js') }}"></script>
@endsection
