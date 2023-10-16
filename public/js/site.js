// Inicializa datatables con id y clase '.datatable'
var datatables = []

document.querySelectorAll('.datatable[id]').forEach(t => {
    datatables[t.id] = new DataTable(t, {
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
    });

    // agrega funcionalidad de busqueda a input externo
    if (document.getElementById(t.id + '_search')) {
        let oTable = $('#' + t.id).DataTable();
        $('#' + t.id + '_search').keyup(function () {
            oTable.search($(this).val()).draw();
        })
    }
})

// Inicializa tooltips
document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(tooltipEl => {
    new bootstrap.Tooltip(tooltipEl)
})

// Inicializa validacion generica en formularios
const forms = document.querySelectorAll('.needs-validation')

forms.forEach(agregarValidacionGenerica)

function agregarValidacionGenerica(form) {
    form.addEventListener('submit', event => {
        if (!form.checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
        }

        form.classList.add('was-validated')
    }, false)
}

// Marcar notificacion como vista
async function marcarNotificacion(id, notifEl) {
    const contador = document.getElementById('contadorNotif')
    let res = await fetch("/AppwebMVC/Notificaciones/MarcarVisto?id=" + id)
    if (res.status != 200) {
        console.error("Ah ocurrido un error al marcar la notificacion");
        console.error(await res.text())
        return
    }
    contador.textContent = Number(contador.textContent) - 1;
    notifEl.querySelector('.tiempo').classList.remove('text-primary', 'fw-bold')
    notifEl.querySelector('.tiempo').classList.add('text-secondary')

    if (Number(contador.textContent) == 0) {
        document.getElementById('btn-notif').classList.remove('btn-accent')
        document.getElementById('btn-notif').classList.add('btn-dark')
    }
}