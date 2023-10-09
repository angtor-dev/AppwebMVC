const modalCedulaEl = document.getElementById('modal-cedula')
let modalCedula = bootstrap.Modal.getOrCreateInstance(modalCedulaEl)

// eventos
document.getElementById('form-buscar-cedula').addEventListener('submit', submitHandler)
modalCedulaEl.addEventListener('hidden.bs.modal', e => {
    document.getElementById('buscar-cedula').value = "";
    document.querySelector('.msgBox').innerHTML = "";
})

/**
 * Handler para formulario de validacion de cedula
 * @param {SubmitEvent} e 
 */
async function submitHandler(e) {
    e.preventDefault()
    let form = e.currentTarget
    const msgBox = document.querySelector(".msgBox")

    let res = await fetch(form.action, {
        method: form.method,
        body: new FormData(form)
    })

    if (res.status != 200) {
        console.error("Ah ocurrido un error.", res.status);
        return;
    }
    let data = await res.json();

    if (data.code != 1) {
        console.log(data.msg)
        msgBox.innerHTML = crearAlerta(data.msg)
        return;
    }

    modalCedula.hide()
    abrirModalDiscipulo(data.id)
}

function abrirModalUsuario(id = 0) {
    fetch('/AppwebMVC/Inscripciones/Registrar?id=' + id)
        .then(res => res.text())
        .then(data => {
            const modalEl = document.getElementById('offcanvas-estudiante')
            modalEl.innerHTML = data
            modalEl.querySelectorAll('.needs-validation')
                .forEach(agregarValidacionGenerica)

            let modal = new bootstrap.Offcanvas(modalEl)
            modal.show()
        })
        .catch(error => console.error(error))
}

function abrirModalDiscipulo(id) {
    fetch('/AppwebMVC/Inscripciones/ModalDiscipulo?id=' + id)
        .then(res => res.text())
        .then(data => {
            const modalEl = document.getElementById('offcanvas-estudiante')
            modalEl.innerHTML = data
            modalEl.querySelectorAll('.needs-validation')
                .forEach(agregarValidacionGenerica)

            let modal = new bootstrap.Offcanvas(modalEl)
            modal.show()
        })
        .catch(error => console.error(error))
}

function crearAlerta(mensaje) {
    return `
        <div class="alert alert-danger alert-dismissible fade show mt-3">
            ${mensaje}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>`
}