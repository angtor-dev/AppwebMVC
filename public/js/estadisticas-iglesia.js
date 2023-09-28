$(document).ready(function () {

    $('#botonSede1').on('click', function () {
        $.ajax({
            type: "GET",
            url: "http://localhost/AppwebMVC/Estadisticas/Iglesia",
            data: {
                cantidad_celulas_sede: 'cantidad_celulas_sede',
            },
            success: function (response) {
                console.log(response);

                let json = JSON.parse(response);

                let labels = [];
                let data = [];
                let colores = [];

                json.forEach(element => {
                    labels.push(element.nombreSede)
                    data.push(element.cantidadCelulas)
                    colores.push(colorRGB())
                });

                grafico1(labels, data, colores)

            },
            error: function (jqXHR, textStatus, errorThrown) {
                // Aquí puedes manejar errores, por ejemplo:
                console.error("Error al enviar:", textStatus, errorThrown);
            }
        })
    })

    $('#botonSede2').on('click', function () {
        $.ajax({
            type: "GET",
            url: "http://localhost/AppwebMVC/Estadisticas/Iglesia",
            data: {
                cantidad_territorios_sede: 'cantidad_territorios_sede',
            },
            success: function (response) {
                console.log(response);

                let json = JSON.parse(response);

                let labels = [];
                let data = [];
                let colores = [];

                json.forEach(element => {
                    labels.push(element.nombreSede)
                    data.push(element.cantidadTerritorios)
                    colores.push(colorRGB())
                });

                grafico1(labels, data, colores)

            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR.responseText);
                // Aquí puedes manejar errores, por ejemplo:
                console.error("Error al enviar:", textStatus, errorThrown);
            }
        })
    })

    $('#botonSede3').on('click', function () {
        $.ajax({
            type: "GET",
            url: "http://localhost/AppwebMVC/Estadisticas/Iglesia",
            data: {
                cantidad_sedes_fecha: 'cantidad_sedes_fecha',
            },
            success: function (response) {
                console.log(response);

                let json = JSON.parse(response);

                let labels = [];
                let data = [];
                let colores = [];

                Object.entries(json[0]).forEach(([month, value]) => {
                    labels.push(month);
                    data.push(Number(value));
                    colores.push(colorRGB())
                });
                    
                grafico1(labels, data, colores)

            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR.responseText);
                // Aquí puedes manejar errores, por ejemplo:
                console.error("Error al enviar:", textStatus, errorThrown);
            }
        })
    })



    let chart1;
    function grafico1(array1, array2, colores) {
        const ctx = document.getElementById('estadistica1');

        if (chart1) {
            chart1.destroy()
        }

        chart1 = new Chart(ctx, {
            type: "bar",
            data: {
                labels: array1,
                datasets: [
                    {
                        data: array2,
                        backgroundColor: colores,
                        borderColor: colores,
                        borderWidth: 1,
                    },
                ],
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                    },
                },
            },
        });
    }


    function generarNumero(numero) {
        return (Math.random() * numero).toFixed(0);
    }
    function colorRGB() {
        var color = "(" + generarNumero(255) + "," + generarNumero(255) + "," + generarNumero(255) + ", 0.5)";
        return "rgb" + color;
    }
})