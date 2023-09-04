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

// Modales de eliminacion
document.querySelectorAll('.modal-eliminar').forEach(modalEl => {
    modalEl.addEventListener('show.bs.modal', e => {
        const button = e.relatedTarget
        const id = button.dataset.id
        const btnEliminar = modalEl.querySelector('.btn-eliminar')

        btnEliminar.href = btnEliminar.dataset.href + id
    })
})

/* Funciones de utilidades */

/**
 * Genera una alerta de Bootstrap
 * @param {string} mensaje Mensaje a mostrar
 * @param {string} tipo Tipo de alerta basado en las clases de bootstrap
 * (Ej. primary, secondary, success...). "danger" por defecto.
 */
function alerta(mensaje, tipo = "danger") {
    let wrapper = document.getElementById('alerts-section')

    wrapper.innerHTML += `
    <div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
        ${mensaje}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>`;
}