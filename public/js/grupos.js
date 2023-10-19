function abrirModalEstudiantes(id = 0) {
    fetch('/AppwebMVC/Grupos/AgregarEstudiantes?id=' + id)
        .then(res => res.text())
        .then(data => {
            const modalEl = document.getElementById('modal-estudiantes')
            modalEl.innerHTML = data
            modalEl.querySelectorAll('.needs-validation')
                .forEach(agregarValidacionGenerica)

            let modal = new bootstrap.Modal(modalEl)
            modal.show()

            var tabla = new DataTable('#tabla-estudiantes', {
                info: false,
                lengthChange: false,
                pageLength: 15,
                language: {
                    url: '/AppwebMVC/public/lib/datatables/datatable-spanish.json'
                },
                // Muestra paginacion solo si hay mas de una pagina
                drawCallback: function (settings) {
                    var pagination = $(this).closest('.dataTables_wrapper').find('.dataTables_paginate');
                    pagination.toggle(this.api().page.info().pages > 1);
                }
            })
        })
        .catch(error => console.error(error))
}

function abrirModalNotas(id) {
    fetch('/AppwebMVC/Grupos/Notas/Consultar?id=' + id)
        .then(res => res.text())
        .then(data => {
            const modalEl = document.getElementById('modal-notas')
            modalEl.innerHTML = data

            modalEl.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(tooltipEl => {
                new bootstrap.Tooltip(tooltipEl)
            })
            modalEl.querySelectorAll('#input-nota').forEach(el => {
                el.addEventListener('input', e => {
                    if (e.target.value > 20) {
                        e.target.value = 20
                    }
                    if (e.target.value < 0) {
                        e.target.value = 0
                    }
                })
            })

            let modal = new bootstrap.Modal(modalEl)
            modal.show()
        })
        .catch(error => console.error(error))
}

function agregarNota(el) {
    const wrapper = el.parentElement.parentElement
    const notaEl = wrapper.querySelector('.nota')
    const input = wrapper.querySelector('#input-nota')

    input.classList.remove('d-none')
    notaEl.classList.add('d-none')

    el.parentElement.innerHTML = btnGuardar()
}

async function crearNota(el) {
    const wrapper = el.parentElement.parentElement
    const notaEl = wrapper.querySelector('.nota')
    const input = wrapper.querySelector('#input-nota')
    const inputId = wrapper.querySelector('input[name=id]')
    const formEl = wrapper.querySelector('form')
    let data = new FormData(formEl)

    let res = await fetch('/AppwebMVC/Grupos/Notas/Registrar', {
        method: "post",
        body: data
    })

    if (res.status != 200) {
        let error = await res.text()
        console.error(error)

        input.classList.add('d-none')
        notaEl.classList.remove('d-none')
        el.parentElement.innerHTML = btnAgregar()
        return
    }
    
    let nota = await res.json()
    notaEl.textContent = nota.calificacion + " / 20"
    input.value = nota.calificacion
    inputId.value = nota.id
    input.classList.add('d-none')
    notaEl.classList.remove('d-none')

    el.parentElement.innerHTML = btnActualizar()
}

async function eliminarNota(el) {
    const wrapper = el.parentElement.parentElement
    const notaEl = wrapper.querySelector('.nota')
    const input = wrapper.querySelector('#input-nota')
    const inputId = wrapper.querySelector('input[name=id]')
    const formEl = wrapper.querySelector('form')
    let data = new FormData(formEl)

    let res = await fetch('/AppwebMVC/Grupos/Notas/Eliminar', {
        method: "post",
        body: data
    })

    if (res.status != 200) {
        let error = await res.text()
        console.error(error)

        input.classList.add('d-none')
        notaEl.classList.remove('d-none')
        el.parentElement.innerHTML = btnActualizar()
        return
    }

    notaEl.innerHTML = "<em>Sin nota</em>"
    input.value = 0
    inputId.value = ""
    input.classList.add('d-none')
    notaEl.classList.remove('d-none')

    el.parentElement.innerHTML = btnAgregar()
}

function btnGuardar() {
    return `
        <button class="btn-small bg-success" title="Guardar"
            onclick="crearNota(this)">
            <i class="fa-solid fa-check"></i>
        </button>`
}

function btnAgregar() {
    return `
        <button class="btn-small bg-success" title="Agregar"
            onclick="agregarNota(this)">
            <i class="fa-solid fa-plus"></i>
        </button>`
}

function btnActualizar() {
    return `
        <button class="btn-small bg-primary" title="Actulizar" onclick="agregarNota(this)">
            <i class="fa-solid fa-pen"></i>
        </button>
        <button class="btn-small bg-danger" title="Eliminar" onclick="eliminarNota(this)">
            <i class="fa-solid fa-trash"></i>
        </button>`
}