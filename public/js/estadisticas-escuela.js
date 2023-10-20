// Coloca titulo al modal antes de abrirlo
const modalEl = document.getElementById('modal-generico')
modalEl.addEventListener('show.bs.modal', e => {
    const modal = e.target
    const boton = e.relatedTarget
    let titulo = boton.dataset.titulo
    
    modal.querySelector('.modal-title').textContent = titulo ?? "Estadisticas"
})

// Estadisticas
document.getElementById('botonNivel1').addEventListener('click', async e => {
    let res = await fetch("/AppwebMVC/Estadisticas/Escuela?gruposPorNivel=true")
    let dataJson = await res.json()

    const data = {
        labels: dataJson.map(x => x.nombre),
        datasets: [
            {
                label: "Grupos",
                data: dataJson.map(x => x.cantidad),
                backgroundColor: dataJson.map(colorRGB)
            },
        ],
    }

    const config = {
        type: 'bar',
        data: data,
        options: {
            indexAxis: 'x',
            responsive: true
        },
    }

    grafico1(config)

    console.log(dataJson);
})

document.getElementById('botonGrupo1').addEventListener('click', async e => {
    let res = await fetch("/AppwebMVC/Estadisticas/Escuela?inscripcionesPorMes=true")
    let dataJson = await res.json()

    const data = {
        labels: Object.keys(dataJson[0]),
        datasets: [
            {
                label: "Cant. Inscripciones",
                data: Object.values(dataJson[0]),
                backgroundColor: colorRGB()
            },
        ],
    }

    const config = {
        type: 'bar',
        data: data,
        options: {
            indexAxis: 'x',
            responsive: true
        },
    }

    grafico1(config)

    console.log(dataJson);
})

document.getElementById('botonGrupo2').addEventListener('click', async e => {
    let res = await fetch("/AppwebMVC/Estadisticas/Escuela?estudiantesPorGrupo=true")
    let dataJson = await res.json()

    const data = {
        labels: dataJson.map(x => x.nombre),
        datasets: [
            {
                label: "Cant. Estudiantes",
                data: dataJson.map(x => x.cantidad),
                backgroundColor: dataJson.map(colorRGB)
            },
        ],
    }

    const config = {
        type: 'bar',
        data: data,
        options: {
            indexAxis: 'x',
            responsive: true
        },
    }

    grafico1(config)

    console.log(dataJson);
})

document.getElementById('botonGrupo3').addEventListener('click', async e => {
    let res = await fetch("/AppwebMVC/Estadisticas/Escuela?gruposPorSede=true")
    let dataJson = await res.json()

    const data = {
        labels: dataJson.map(x => x.nombre),
        datasets: [
            {
                label: "Grupos",
                data: dataJson.map(x => x.cantidad),
                backgroundColor: dataJson.map(colorRGB)
            },
        ],
    }

    const config = {
        type: 'doughnut',
        data: data,
        options: {
            indexAxis: 'x',
            responsive: true
        },
    }

    grafico1(config)

    console.log(dataJson);
})

document.getElementById('botonNotas1').addEventListener('click', async e => {
    let res = await fetch("/AppwebMVC/Estadisticas/Escuela?promedioNotasPorGrupo=true")
    let dataJson = await res.json()

    const data = {
        labels: dataJson.map(x => x.nombre),
        datasets: [
            {
                label: "Grupos",
                data: dataJson.map(x => x.promedio),
                backgroundColor: dataJson.map(colorRGB)
            },
        ],
    }

    const config = {
        type: 'doughnut',
        data: data,
        options: {
            indexAxis: 'x',
            responsive: true
        },
    }

    grafico1(config)

    console.log(dataJson);
})

async function test() {
    let res = await fetch("/AppwebMVC/Estadisticas/Escuela?estudiantesPorGrupo=true")
    let data = await res.text();
    console.log(data);
}

// Chart supongo
let chart1;
function grafico1(config) {
    const ctx = document.getElementById('estadistica1');

    if (chart1) {
        chart1.destroy()
    }

    chart1 = new Chart(ctx, config)
}

// Colores
function generarNumero(numero) {
    return (Math.random() * numero).toFixed(0);
}
function colorRGB() {
    var color = "(" + generarNumero(255) + "," + generarNumero(255) + "," + generarNumero(255) + ", 0.5)";
    return "rgb" + color;
}