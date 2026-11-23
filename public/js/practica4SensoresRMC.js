// =====================================================================
// practica4SensoresRMC.js — Práctica 4, Bloque 2: formularios / sensores (RMC)
// Plantilla: resources/views/practica4/sensoresRMC.blade.php
// API destino (práctica 3, bloque 5): POST /api/sensores/{sensor}/lectura?valor=...
// =====================================================================

// ---------- 1. Referencias a los elementos del formulario ----------
const selectSensor = document.getElementById('sensor_id');
const campoDescripcion = document.getElementById('descripcion');
const campoPeriodicidad = document.getElementById('periodicidad');
const campoValor = document.getElementById('valor');
const campoRepetir = document.getElementById('repetirNveces');
const campoIncremento = document.getElementById('incrementoValor');
const checkSoloLocal = document.getElementById('soloLocal');
const areaTexto = document.getElementById('textarea');


// ---------- 2. Habilitar / deshabilitar botones según soloLocal ----------
// Cuándo se habilita cada botón (para el bloque 3 basta con cambiar esta tabla):
//   'local'   -> solo si soloLocal está marcado
//   'remoto'  -> solo si soloLocal NO está marcado
//   'siempre' -> siempre habilitado
const reglasBotones = {
    anadir1vez: 'local',
    anadirNveces: 'local',
    cargarSensores: 'local',
    cargarDatosSensor: 'local',
    borrar: 'local'
};

function actualizarBotones() {
    const local = checkSoloLocal.checked; // true si está marcado

    for (const idBoton in reglasBotones) {
        const regla = reglasBotones[idBoton];
        let habilitado;

        if (regla === 'local') {
            habilitado = local;
        } else if (regla === 'remoto') {
            habilitado = !local;
        } else {
            habilitado = true; // 'siempre'
        }

        document.getElementById(idBoton).disabled = !habilitado;
    }
}


// ---------- 3. Mostrar descripción y periodicidad del sensor elegido ----------
function mostrarDatosSensor() {
    // Opción elegida en el desplegable
    const opcion = selectSensor.options[selectSensor.selectedIndex];

    if (selectSensor.value === '') {
        campoDescripcion.value = '';
        campoPeriodicidad.value = '';
        return;
    }

    // Los atributos data-* del <option> se leen con dataset
    campoDescripcion.value = opcion.dataset.descripcion;

    const periodicidad = opcion.dataset.periodicidad;
    if (periodicidad === 'dia') {
        campoPeriodicidad.value = 'día';
    } else if (periodicidad === '') {
        campoPeriodicidad.value = 'sin definir (falta ejecutar la migración)';
    } else {
        campoPeriodicidad.value = periodicidad; // 'minuto' u 'hora'
    }
}


// ---------- 4. Validaciones ----------
// Convierte un texto en número decimal ("6,5" o "6.5" -> 6.5), o null si no lo es
function leerNumero(texto) {
    const limpio = texto.trim().replace(',', '.');

    // Signo opcional, cifras y, opcionalmente, punto y más cifras
    if (!/^-?[0-9]+(\.[0-9]+)?$/.test(limpio)) {
        return null;
    }
    return parseFloat(limpio);
}

// Devuelve el id del sensor elegido, o null (con alert) si no hay ninguno
function validarSensor() {
    if (selectSensor.value === '') {
        alert('Error: elige un sensor en la lista.');
        return null;
    }
    return selectSensor.value;
}

// Devuelve el valor numérico, o null (con alert) si no es válido
function validarValor() {
    const valor = leerNumero(campoValor.value);
    if (valor === null) {
        alert('Error: el valor "' + campoValor.value + '" no es un número. Ejemplos válidos: 6.5, -2, 85');
        return null;
    }
    return valor;
}

// Devuelve repetirNveces (entero de 1 a 100), o null (con alert) si no es válido
function validarRepeticiones() {
    const texto = campoRepetir.value.trim();

    if (!/^[0-9]+$/.test(texto)) {
        alert('Error: repetirNveces debe ser un número entero (solo cifras). Has escrito: "' + texto + '"');
        return null;
    }

    const n = parseInt(texto, 10);
    if (n < 1 || n > 100) {
        alert('Error: repetirNveces debe estar entre 1 y 100. Has escrito: ' + n);
        return null;
    }
    return n;
}

// Devuelve incrementoValor (puede ser negativo o decimal), o null (con alert) si no es válido
function validarIncremento() {
    const incremento = leerNumero(campoIncremento.value);
    if (incremento === null) {
        alert('Error: incrementoValor "' + campoIncremento.value + '" no es un número. Ejemplos válidos: 0.5, -1, 0');
        return null;
    }
    return incremento;
}


// ---------- 5. Construir la URL de la API ----------
// Redondea a 2 decimales, como guarda la BD (decimal 10,2)
function redondear(numero) {
    return Math.round(numero * 100) / 100;
}

// Ejemplo: construirUrl(3, 9.2) -> "http://pweb26.test/api/sensores/3/lectura?valor=9.2"
function construirUrl(sensorId, valor) {
    return window.location.origin
        + '/api/sensores/' + encodeURIComponent(sensorId)
        + '/lectura?valor=' + encodeURIComponent(redondear(valor));
}

// Añade una línea al final del textarea y baja el scroll
function anadirLinea(texto) {
    areaTexto.value += texto + '\n';
    areaTexto.scrollTop = areaTexto.scrollHeight;
}


// ---------- 6. Funciones de los botones ----------
// añadir1vez: una línea con la URL de la API
function anadir1vez() {
    const sensorId = validarSensor();
    if (sensorId === null) return;

    const valor = validarValor();
    if (valor === null) return;

    anadirLinea(construirUrl(sensorId, valor));
}

// añadirNveces: N líneas; el valor aumenta en incrementoValor en cada una
function anadirNveces() {
    const sensorId = validarSensor();
    if (sensorId === null) return;

    const valor = validarValor();
    if (valor === null) return;

    const n = validarRepeticiones();
    if (n === null) return;

    const incremento = validarIncremento();
    if (incremento === null) return;

    for (let i = 0; i < n; i++) {
        // valor inicial + i incrementos (i = 0 en la primera línea)
        anadirLinea(construirUrl(sensorId, valor + i * incremento));
    }
}

// cargarSensores y cargarDatosSensor: existen, pero la llamada a la API es del bloque 3
function cargarSensores() {
    alert('Cargar sensores: se conectará con la API (GET /api/sensores) en el bloque 3.');
}

function cargarDatosSensor() {
    alert('Cargar datos del sensor: se conectará con la API (GET /api/sensores/{sensor}) en el bloque 3.');
}

// borrar: vacía el textarea
function borrar() {
    areaTexto.value = '';
}


// ---------- 7. Conectar eventos ----------
document.getElementById('anadir1vez').addEventListener('click', anadir1vez);
document.getElementById('anadirNveces').addEventListener('click', anadirNveces);
document.getElementById('cargarSensores').addEventListener('click', cargarSensores);
document.getElementById('cargarDatosSensor').addEventListener('click', cargarDatosSensor);
document.getElementById('borrar').addEventListener('click', borrar);

// 'change' salta al marcar/desmarcar el checkbox o al elegir otra opción del select
checkSoloLocal.addEventListener('change', actualizarBotones);
selectSensor.addEventListener('change', mostrarDatosSensor);

// Estado inicial al cargar la página (el navegador puede recordar el checkbox o el select)
actualizarBotones();
mostrarDatosSensor();
