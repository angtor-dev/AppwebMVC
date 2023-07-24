/**
 * Archivo con funciones de utilidades personalizadas.
 * Tambien se incluye la inicializacion y configuracion de componentes personalizados.
 */

/* Inicializar componentes */
// Acordeones
document.querySelectorAll('.acordeon-toggle').forEach(a => {
    a.addEventListener('click', e =>
        e.currentTarget.parentElement.classList.toggle('show'))
})