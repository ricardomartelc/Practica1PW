// =====================================================================
// practica4SensoresRMC.js — Práctica 4, Bloques 2 y 3: formularios / sensores (RMC)
// Plantilla: resources/views/practica4/sensoresRMC.blade.php
// APIs (práctica 3, bloque 5):
//   GET  /api/sensores                       -> lista de sensores
//   GET  /api/sensores/{sensor}              -> datos de un sensor
//   POST /api/sensores/{sensor}/lectura?valor=...  -> guarda una lectura
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

// true mientras hay una petición al servidor en marcha (evita doble envío)
let ocupado = false;


// ---------- 2. Habilitar / deshabilitar botones según soloLocal ----------
// Cuándo se habilita cada botón:
//   'local'   -> solo si soloLocal está marcado
//   'remoto'  -> solo si soloLocal NO está marcado
//   'siempre' -> siempre habilitado
const reglasBotones = {
    anadir1vez: 'siempre',        // marcado: simula (URL) · desmarcado: llama a la API
    anadirNveces: 'siempre',      // marcado: simula (URLs) · desmarcado: llama a la API
    cargarSensores: 'siempre',    // inicializa el formulario en cualquier modo
    cargarDatosSensor: 'remoto',  // solo tiene sentido hablando con el servidor
    borrar: 'siempre'             // vaciar el textarea no depende del modo
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

        // Mientras hay una petición en marcha, todos los botones quedan bloqueados
        document.getElementById(idBoton).disabled = ocupado || !habilitado;
    }
}

// Bloquea los botones al empezar una petición y los libera al terminar
function empezarPeticion() {
    ocupado = true;
    actualizarBotones();
}

function terminarPeticion() {
    ocupado = false;
    actualizarBotones();
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
    campoPeriodicidad.value = textoPeriodicidad(opcion.dataset.periodicidad);
}

// Convierte el valor de la BD en un texto para mostrar
function textoPeriodicidad(periodicidad) {
    if (periodicidad === 'dia') {
        return 'día';
    }
    if (!periodicidad) { // vacía, null o undefined
        return 'sin definir (falta ejecutar la migración)';
    }
    return periodicidad; // 'minuto' u 'hora'
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


// ---------- 6. Comunicación con el servidor (AJAX con fetch) ----------
// Cabecera común: pedimos que el servidor responda siempre en JSON
const CABECERAS = { 'Accept': 'application/json' };

// Hace la petición y devuelve el JSON de la respuesta.
// Si algo falla, lanza un Error con un mensaje claro (lo recoge el catch de quien llama).
async function pedirJson(url, opciones) {
    let respuesta;
    try {
        // fetch envía la petición; await espera a que llegue la respuesta
        respuesta = await fetch(url, opciones);
    } catch (error) {
        // fetch solo falla aquí si no hay conexión (servidor apagado, sin red...)
        throw new Error('No se puede conectar con el servidor. ¿Está encendido?');
    }

    // Intentamos leer el cuerpo como JSON (puede no serlo, por ejemplo una página de error HTML)
    let datos = null;
    try {
        datos = await respuesta.json();
    } catch (error) {
        datos = null;
    }

    // response.ok es true si el código HTTP está entre 200 y 299
    if (!respuesta.ok) {
        throw new Error(mensajeErrorHttp(respuesta.status, datos));
    }

    if (datos === null) {
        throw new Error('El servidor ha respondido, pero no con JSON.');
    }
    return datos;
}

// Traduce un código de error HTTP a un mensaje para el usuario
function mensajeErrorHttp(codigo, datos) {
    // Laravel suele enviar el motivo en datos.message
    const detalle = (datos && datos.message) ? ' Detalle: ' + datos.message : '';

    if (codigo === 404) return 'No encontrado (404): el sensor no existe en el servidor.' + detalle;
    if (codigo === 405) return 'Método no permitido (405): la API no acepta este tipo de petición.' + detalle;
    if (codigo === 422) return 'Datos no válidos (422).' + detalle;
    if (codigo >= 500)  return 'Error interno del servidor (' + codigo + ').' + detalle;
    return 'Error HTTP ' + codigo + '.' + detalle;
}

// Muestra un error con alert y lo deja también como línea en el textarea
function mostrarError(accion, error) {
    alert('Error al ' + accion + ':\n' + error.message);
    anadirLinea('✖ ERROR al ' + accion + ': ' + error.message);
}

// POST a la API de lecturas y traza en el textarea. Devuelve los datos de la respuesta.
async function enviarLectura(sensorId, valor) {
    const datos = await pedirJson(construirUrl(sensorId, valor), {
        method: 'POST',
        headers: CABECERAS
    });

    // Comprobamos que la respuesta tiene la forma esperada: { sensor, valor, alerta }
    if (datos.valor === undefined) {
        throw new Error('Respuesta inesperada del servidor (falta "valor").');
    }

    const textoAlerta = datos.alerta ? 'SÍ (fuera de umbral)' : 'no';
    anadirLinea('✔ Guardado en BD · sensor ' + datos.sensor + ' · valor ' + datos.valor + ' · alerta: ' + textoAlerta);
    return datos;
}


// ---------- 7. Funciones de los botones ----------
// añadir1vez: marcado -> escribe la URL · desmarcado -> la envía a la API
async function anadir1vez() {
    const sensorId = validarSensor();
    if (sensorId === null) return;

    const valor = validarValor();
    if (valor === null) return;

    if (checkSoloLocal.checked) {
        anadirLinea(construirUrl(sensorId, valor)); // simulación (bloque 2)
        return;
    }

    if (ocupado) return;
    empezarPeticion();
    try {
        await enviarLectura(sensorId, valor);
    } catch (error) {
        mostrarError('guardar la lectura', error);
    } finally {
        terminarPeticion(); // se ejecuta siempre, haya error o no
    }
}

// añadirNveces: N lecturas; el valor aumenta en incrementoValor en cada una
async function anadirNveces() {
    const sensorId = validarSensor();
    if (sensorId === null) return;

    const valor = validarValor();
    if (valor === null) return;

    const n = validarRepeticiones();
    if (n === null) return;

    const incremento = validarIncremento();
    if (incremento === null) return;

    if (checkSoloLocal.checked) {
        // Simulación (bloque 2): solo escribe las URLs
        for (let i = 0; i < n; i++) {
            anadirLinea(construirUrl(sensorId, valor + i * incremento));
        }
        return;
    }

    if (ocupado) return;
    empezarPeticion();
    let guardadas = 0;
    try {
        for (let i = 0; i < n; i++) {
            // await dentro del for: cada petición espera a que termine la anterior (en orden)
            await enviarLectura(sensorId, valor + i * incremento);
            guardadas++;
        }
        anadirLinea('— ' + guardadas + ' de ' + n + ' lecturas guardadas.');
    } catch (error) {
        // Al primer error paramos para no repetir el mismo fallo N veces
        mostrarError('guardar la lectura ' + (guardadas + 1) + ' de ' + n
            + ' (se guardaron ' + guardadas + ')', error);
    } finally {
        terminarPeticion();
    }
}

// cargarSensores: pide la lista a la API y rellena el desplegable
async function cargarSensores() {
    if (ocupado) return;
    empezarPeticion();
    try {
        const sensores = await pedirJson(window.location.origin + '/api/sensores', { headers: CABECERAS });

        // Debe ser una lista (array) de sensores con id y código
        if (!Array.isArray(sensores) || sensores.some(s => s.id === undefined || !s.codigo_dispositivo)) {
            throw new Error('Respuesta inesperada del servidor (no es una lista de sensores).');
        }

        rellenarSelect(sensores);
        anadirLinea('✔ ' + sensores.length + ' sensores cargados desde la API (GET /api/sensores).');
    } catch (error) {
        mostrarError('cargar los sensores', error);
    } finally {
        terminarPeticion();
    }
}

// Sustituye las opciones del desplegable por las de la API, manteniendo la selección
function rellenarSelect(sensores) {
    const seleccionAnterior = selectSensor.value;

    // Deja solo la primera opción ("-- Elige un sensor --")
    selectSensor.length = 1;

    for (const sensor of sensores) {
        const opcion = document.createElement('option'); // crea un <option> nuevo
        opcion.value = sensor.id;
        opcion.textContent = sensor.id + ' — ' + sensor.codigo_dispositivo + ' (' + sensor.tipo + ')';
        opcion.dataset.descripcion = sensor.tipo + ' · puesto ' + sensor.vendedor_id
            + ' · umbral ' + sensor.umbral_min + ' a ' + sensor.umbral_max;
        opcion.dataset.periodicidad = sensor.periodicidad || '';
        selectSensor.appendChild(opcion); // lo añade al final del desplegable
    }

    // Si el sensor elegido sigue existiendo, se queda seleccionado; si no, vuelve a "-- Elige --"
    selectSensor.value = seleccionAnterior;
    if (selectSensor.value !== seleccionAnterior) {
        selectSensor.value = '';
    }
    mostrarDatosSensor();
}

// cargarDatosSensor: vacía el textarea y muestra los datos del sensor que tiene el servidor
async function cargarDatosSensor() {
    if (checkSoloLocal.checked || ocupado) return; // solo en modo remoto

    const sensorId = validarSensor();
    if (sensorId === null) return;

    areaTexto.value = '';
    empezarPeticion();
    try {
        const url = window.location.origin + '/api/sensores/' + encodeURIComponent(sensorId);
        const datos = await pedirJson(url, { headers: CABECERAS });

        if (typeof datos !== 'object' || Array.isArray(datos)) {
            throw new Error('Respuesta inesperada del servidor (no son los datos de un sensor).');
        }

        anadirLinea('Datos del sensor ' + sensorId + ' en el servidor (GET /api/sensores/' + sensorId + '):');
        // Una línea por cada dato recibido: "campo: valor"
        for (const campo in datos) {
            const valor = (datos[campo] === null) ? '(sin dato)' : datos[campo];
            anadirLinea('  ' + campo + ': ' + valor);
        }
        anadirLinea('Nota: la API solo guarda el último valor (valor_actual); no devuelve histórico.');
    } catch (error) {
        mostrarError('cargar los datos del sensor', error);
    } finally {
        terminarPeticion();
    }
}

// borrar: vacía el textarea
function borrar() {
    areaTexto.value = '';
}


// ---------- 8. Conectar eventos ----------
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
