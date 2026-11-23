// =====================================================================
// practica4RMC.js — Práctica 4, Bloque 1: manejo básico del DOM (RMC)
// Plantilla: resources/views/practica4/basicoRMC.blade.php
// =====================================================================

// ---------- 1. Referencias a los elementos de la página ----------
const texto1 = document.getElementById('text-1');
const areaTexto = document.getElementById('textarea');
const texto2 = document.getElementById('text-2');
const elementoRMC = document.getElementById('elementoRMC');

// Guarda el identificador del timer activo (null = no hay ninguno)
let temporizador = null;


// ---------- 2. boton1: añadir text-1 al textarea ----------
function anadirTexto() {
    // Añade el texto al final, en una línea nueva
    areaTexto.value += texto1.value + '\n';

    // Baja el scroll del textarea para ver siempre la última línea
    areaTexto.scrollTop = areaTexto.scrollHeight;
}


// ---------- 3. boton2: validar text-2 ----------
// Devuelve el número de segundos si es válido, o null si no lo es
function validarSegundos(valor) {
    const limpio = valor.trim(); // quita espacios al principio y al final

    if (limpio === '') {
        alert('Error: text-2 está vacío. Escribe un número entero entre 1 y 60.');
        return null;
    }

    // Solo se aceptan dígitos del 0 al 9 (sin letras, signos ni decimales)
    if (!/^[0-9]+$/.test(limpio)) {
        alert('Error: "' + limpio + '" no es un número entero válido (solo cifras, sin signos ni decimales).');
        return null;
    }

    const segundos = parseInt(limpio, 10); // convierte el texto en número

    if (segundos < 1 || segundos > 60) {
        alert('Error: ' + segundos + ' está fuera de rango. Debe estar entre 1 y 60.');
        return null;
    }

    return segundos;
}


// ---------- 4. boton2: timer que repite boton1 cada segundo ----------
function iniciarTemporizador() {
    const segundos = validarSegundos(texto2.value);
    if (segundos === null) {
        return; // valor no válido: ya se mostró el alert, no hacemos nada más
    }

    // Si ya había un timer en marcha, lo cancelamos para no mezclar dos
    detenerTemporizador();

    let repeticiones = 0;

    // Cada 1000 ms (1 segundo) ejecuta la función de dentro
    temporizador = setInterval(function () {
        anadirTexto();
        repeticiones++;

        // Al llegar a text-2 repeticiones, el timer se para solo
        if (repeticiones >= segundos) {
            detenerTemporizador();
        }
    }, 1000);
}

function detenerTemporizador() {
    if (temporizador !== null) {
        clearInterval(temporizador); // cancela el timer
        temporizador = null;
    }
}


// ---------- 5. boton3: aplicar el estilo de text-1 a elementoRMC ----------
function aplicarEstilo() {
    // Ejemplo: "background-color: yellow;" -> quitamos espacios y el ";" final
    const declaracion = texto1.value.trim().replace(/;$/, '');

    // Separamos por los primeros dos puntos: propiedad y valor
    const posicion = declaracion.indexOf(':');
    if (posicion === -1) {
        alert('Error: escribe una declaración CSS del tipo "propiedad: valor", por ejemplo "color: green".');
        return;
    }

    const propiedad = declaracion.substring(0, posicion).trim(); // "background-color"
    const valor = declaracion.substring(posicion + 1).trim();    // "yellow"

    // Aplica el estilo SOLO a elementoRMC.
    // Si la propiedad o el valor no son válidos, el navegador lo ignora (no se rompe nada)
    elementoRMC.style.setProperty(propiedad, valor);

    // Comprobamos si el navegador lo ha aceptado para avisar al usuario
    if (elementoRMC.style.getPropertyValue(propiedad) === '') {
        alert('Error: "' + declaracion + '" no es un estilo CSS válido. No se ha aplicado.');
    }
}


// ---------- 6. Conectar cada botón con su función ----------
document.getElementById('boton1').addEventListener('click', anadirTexto);
document.getElementById('boton2').addEventListener('click', iniciarTemporizador);
document.getElementById('boton3').addEventListener('click', aplicarEstilo);
