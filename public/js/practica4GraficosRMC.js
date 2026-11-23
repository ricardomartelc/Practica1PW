// =====================================================================
// practica4GraficosRMC.js — Práctica 4, Bloque 4: gráficos con Chart.js (RMC)
// Plantilla: resources/views/practica4/graficosRMC.blade.php
// Datos: GET /api/sensores (valor actual y umbrales de cada sensor)
// Librería: Chart.js 4.4.1 (public/js/vendor/chart.umd.min.js), que define "Chart"
// =====================================================================

// ---------- 1. Referencias y configuración ----------
const botonActualizar = document.getElementById('actualizar');
const mensaje = document.getElementById('mensajeRMC');

// Un gráfico por tipo de sensor: canvas donde se dibuja, título y unidad
const TIPOS = {
    temperatura: { canvas: 'graficoTemperatura', titulo: 'Sensores de temperatura', unidad: 'ºC' },
    humedad:     { canvas: 'graficoHumedad',     titulo: 'Sensores de humedad',     unidad: '%' }
};

// Aquí guardamos cada gráfico creado, para poder destruirlo antes de redibujar
const graficos = { temperatura: null, humedad: null };

// Colores (los mismos tonos que el portal)
const VERDE = '#7c9f77';       // valor dentro del umbral
const ROJO = '#b3261e';        // valor fuera del umbral
const GRIS_CLARO = '#d9d3c5';  // umbral mínimo
const GRIS_OSCURO = '#8f8676'; // umbral máximo


// ---------- 2. Mensajes para el usuario ----------
function mostrarMensaje(texto, esError) {
    mensaje.textContent = texto;
    mensaje.className = esError ? 'error' : ''; // la clase "error" lo pinta en rojo
}


// ---------- 3. Pedir los sensores a la API ----------
// Devuelve la lista de sensores o lanza un Error con un mensaje claro
async function pedirSensores() {
    let respuesta;
    try {
        respuesta = await fetch(window.location.origin + '/api/sensores', {
            headers: { 'Accept': 'application/json' }
        });
    } catch (error) {
        throw new Error('No se puede conectar con el servidor. ¿Está encendido?');
    }

    if (!respuesta.ok) {
        throw new Error('El servidor ha respondido con error ' + respuesta.status + '.');
    }

    let sensores;
    try {
        sensores = await respuesta.json();
    } catch (error) {
        throw new Error('El servidor no ha respondido con JSON.');
    }

    if (!Array.isArray(sensores)) {
        throw new Error('Respuesta inesperada: no es una lista de sensores.');
    }
    return sensores;
}


// ---------- 4. Dibujar un gráfico con Chart.js ----------
// true si el sensor tiene valor y está por debajo del mínimo o por encima del máximo
function fueraDeUmbral(sensor) {
    if (sensor.valor_actual === null) return false;
    const valor = Number(sensor.valor_actual);
    return valor < Number(sensor.umbral_min) || valor > Number(sensor.umbral_max);
}

// Dibuja (o redibuja) el gráfico de un tipo con sus sensores
function dibujarGrafico(tipo, sensores) {
    const config = TIPOS[tipo];
    const canvas = document.getElementById(config.canvas);

    // Si ya había un gráfico en este canvas, lo destruimos para no acumularlos
    if (graficos[tipo] !== null) {
        graficos[tipo].destroy();
        graficos[tipo] = null;
    }

    // Si no hay sensores de este tipo, ocultamos su recuadro
    if (sensores.length === 0) {
        canvas.parentElement.style.display = 'none';
        return;
    }
    canvas.parentElement.style.display = '';

    // Preparamos los datos: una posición por sensor en cada lista
    // (la API envía los números como texto, "6.50"; Number() los convierte)
    const etiquetas = sensores.map(s => s.codigo_dispositivo + (s.valor_actual === null ? ' (sin lectura)' : ''));
    const minimos = sensores.map(s => Number(s.umbral_min));
    const actuales = sensores.map(s => s.valor_actual === null ? null : Number(s.valor_actual));
    const maximos = sensores.map(s => Number(s.umbral_max));
    const colores = sensores.map(s => fueraDeUmbral(s) ? ROJO : VERDE);

    // new Chart(canvas, configuración) crea el gráfico dentro del <canvas>
    graficos[tipo] = new Chart(canvas, {
        type: 'bar', // gráfico de barras
        data: {
            labels: etiquetas, // eje X: un grupo de barras por sensor
            datasets: [        // cada dataset es una serie de barras
                { label: 'Umbral mínimo', data: minimos, backgroundColor: GRIS_CLARO },
                { label: 'Valor actual', data: actuales, backgroundColor: colores },
                { label: 'Umbral máximo', data: maximos, backgroundColor: GRIS_OSCURO }
            ]
        },
        options: {
            plugins: {
                title: { display: true, text: config.titulo + ' (' + config.unidad + ')' }
            },
            scales: {
                x: { title: { display: true, text: 'Sensor' } },
                y: { beginAtZero: true, title: { display: true, text: 'Valor (' + config.unidad + ')' } }
            }
        }
    });
}


// ---------- 5. Botón Actualizar: pedir datos y redibujar ----------
async function actualizar() {
    // Si la librería no se ha cargado, avisamos y no seguimos
    if (typeof Chart === 'undefined') {
        mostrarMensaje('Error: no se ha podido cargar la librería Chart.js.', true);
        return;
    }

    botonActualizar.disabled = true; // evita pulsar dos veces mientras carga
    mostrarMensaje('Cargando datos...', false);

    try {
        const sensores = await pedirSensores();

        if (sensores.length === 0) {
            dibujarGrafico('temperatura', []);
            dibujarGrafico('humedad', []);
            mostrarMensaje('No hay sensores registrados en el servidor.', true);
            return;
        }

        // Un gráfico por tipo, con los sensores de ese tipo
        for (const tipo in TIPOS) {
            dibujarGrafico(tipo, sensores.filter(s => s.tipo === tipo));
        }

        const fuera = sensores.filter(fueraDeUmbral).length;
        const hora = new Date().toLocaleTimeString('es-ES');
        mostrarMensaje(sensores.length + ' sensores cargados, ' + fuera
            + ' fuera de umbral (actualizado a las ' + hora + ').', false);
    } catch (error) {
        // El gráfico anterior (si lo había) se queda como estaba
        mostrarMensaje('Error al cargar los sensores: ' + error.message, true);
    } finally {
        botonActualizar.disabled = false;
    }
}


// ---------- 6. Eventos e inicio ----------
botonActualizar.addEventListener('click', actualizar);
actualizar(); // primera carga al abrir la página
