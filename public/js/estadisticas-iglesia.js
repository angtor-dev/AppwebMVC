$(document).ready(function () {


    ////////////// REPORTES ESTADISTICOS SEDES //////////////

    $('#botonSede1').on('click', function (e) {
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
                let valores = [];
                let colores = [];

                json.forEach(element => {
                    labels.push(element.nombreSede)
                    valores.push(element.cantidadCelulas)
                    colores.push(colorRGB())
                });

                const data = {
                    labels: labels,
                    datasets: [
                        {
                            data: valores,
                            backgroundColor: colores,
                            borderColor: colores,
                            borderWidth: 1,
                        },
                    ],
                }
                const config = {
                    type: 'bar',
                    data: data,
                    options: {
                        indexAxis: 'x',
                        elements: {
                            bar: {
                                borderWidth: 2,
                            }
                        },
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false,
                            },
                        }
                    },
                };

                grafico1(config)

                document.getElementById('nombreReporte').innerText = e.target.textContent

            },
            error: function (jqXHR, textStatus, errorThrown) {
                // Aquí puedes manejar errores, por ejemplo:
                console.error("Error al enviar:", textStatus, errorThrown);
            }
        })
    })

    $('#botonSede2').on('click', function (e) {
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
                let valores = [];
                let colores = [];

                json.forEach(element => {
                    labels.push(element.nombreSede)
                    valores.push(element.cantidadTerritorios)
                    colores.push(colorRGB())
                });

                const data = {
                    labels: labels,
                    datasets: [
                        {
                            data: valores,
                            backgroundColor: colores,
                            borderColor: colores,
                            borderWidth: 1,
                        },
                    ],
                }

                const config = {
                    type: 'doughnut',
                    data: data,
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: true,
                            },
                        }
                    },
                };

                grafico1(config)

                document.getElementById('nombreReporte').innerText = e.target.textContent

            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR.responseText);
                // Aquí puedes manejar errores, por ejemplo:
                console.error("Error al enviar:", textStatus, errorThrown);
            }
        })
    })

    $('#botonSede3').on('click', function (e) {
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
                let valores = [];
                let colores = [];

                Object.entries(json[0]).forEach(([month, value]) => {
                    labels.push(month);
                    valores.push(Number(value));
                    colores.push(colorRGB())
                });

                const data = {
                    labels: labels,
                    datasets: [
                        {
                            data: valores,
                            backgroundColor: colores,
                            borderColor: colores,
                            borderWidth: 1,
                        },
                    ],
                }

                const config = {
                    type: 'bar',
                    data: data,
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false,
                            },
                        }
                    },
                };

                grafico1(config)

                document.getElementById('nombreReporte').innerText = e.target.textContent

            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR.responseText);
                // Aquí puedes manejar errores, por ejemplo:
                console.error("Error al enviar:", textStatus, errorThrown);
            }
        })
    })




    ////////////// REPORTES ESTADISTICOS TERRITORIOS //////////////

    $('#botonTerritorio1').on('click', function (e) {
        $.ajax({
            type: "GET",
            url: "http://localhost/AppwebMVC/Estadisticas/Iglesia",
            data: {
                cantidad_celulas_territorio: 'cantidad_celulas_territorio',
            },
            success: function (response) {
                console.log(response);

                let json = JSON.parse(response);

                let labels = [];
                let valores = [];
                let colores = [];

                json.forEach(element => {
                    labels.push(element.nombreTerritorio)
                    valores.push(element.cantidadCelulas)
                    colores.push(colorRGB())
                });

                const data = {
                    labels: labels,
                    datasets: [
                        {
                            data: valores,
                            backgroundColor: colores,
                            borderColor: colores,
                            borderWidth: 1,
                        },
                    ],
                }

                const config = {
                    type: 'bar',
                    data: data,
                    options: {
                        indexAxis: 'x',
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false,
                            },
                        }
                    },
                };

                grafico1(config)

                document.getElementById('nombreReporte').innerText = e.target.textContent

            },
            error: function (jqXHR, textStatus, errorThrown) {
                // Aquí puedes manejar errores, por ejemplo:
                console.error("Error al enviar:", textStatus, errorThrown);
            }
        })
    })




    ////////////// REPORTES ESTADISTICOS CELULA FAMILIAR //////////////

    $('#botonCelulaFamiliar1').on('click', function (e) {
        $.ajax({
            type: "GET",
            url: "http://localhost/AppwebMVC/Estadisticas/Iglesia",
            data: {
                lideres_cantidad_celulas: 'lideres_cantidad_celulas',
                tipo: 'familiar'
            },
            success: function (response) {
                console.log(response);

                let json = JSON.parse(response);

                let labels = [];
                let valores = [];
                let colores = [];

                json.forEach(element => {
                    labels.push(element.nombre + ' ' + element.apellido)
                    valores.push(element.cantidadCelulas)
                    colores.push(colorRGB())
                });

                const data = {
                    labels: labels,
                    datasets: [
                        {
                            data: valores,
                            backgroundColor: colores,
                            borderColor: colores,
                            borderWidth: 1,
                        },
                    ],
                }

                const config = {
                    type: 'bar',
                    data: data,
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false,
                            },
                        }
                    },
                };

                grafico1(config)

                document.getElementById('nombreReporte').innerText = e.target.textContent

            },
            error: function (jqXHR, textStatus, errorThrown) {
                // Aquí puedes manejar errores, por ejemplo:
                console.error("Error al enviar:", textStatus, errorThrown);
            }
        })
    })





    ////////////// REPORTES ESTADISTICOS CELULA CRECIMIENTO //////////////

    $('#botonCelulaCrecimiento1').on('click', function (e) {
        $.ajax({
            type: "GET",
            url: "http://localhost/AppwebMVC/Estadisticas/Iglesia",
            data: {
                lideres_cantidad_celulas: 'lideres_cantidad_celulas',
                tipo: 'crecimiento'
            },
            success: function (response) {
                console.log(response);

                let json = JSON.parse(response);

                let labels = [];
                let valores = [];
                let colores = [];

                json.forEach(element => {
                    labels.push(element.nombre + ' ' + element.apellido)
                    valores.push(element.cantidadCelulas)
                    colores.push(colorRGB())
                });

                const data = {
                    labels: labels,
                    datasets: [
                        {
                            data: valores,
                            backgroundColor: colores,
                            borderColor: colores,
                            borderWidth: 1,
                        },
                    ],
                }

                const config = {
                    type: 'bar',
                    data: data,
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false,
                            },
                        }
                    },
                };

                grafico1(config)

                document.getElementById('nombreReporte').innerText = e.target.textContent

            },
            error: function (jqXHR, textStatus, errorThrown) {
                // Aquí puedes manejar errores, por ejemplo:
                console.error("Error al enviar:", textStatus, errorThrown);
            }
        })
    })





    ////////////// REPORTES ESTADISTICOS CELULA CONSOLIDACION //////////////

    $('#botonCelulaConsolidacion1').on('click', function (e) {
        $.ajax({
            type: "GET",
            url: "http://localhost/AppwebMVC/Estadisticas/Iglesia",
            data: {
                lideres_cantidad_celulas: 'lideres_cantidad_celulas',
                tipo: 'consolidacion'
            },
            success: function (response) {
                console.log(response);

                let json = JSON.parse(response);

                let labels = [];
                let valores = [];
                let colores = [];

                json.forEach(element => {
                    labels.push(element.nombre + ' ' + element.apellido)
                    valores.push(element.cantidadCelulas)
                    colores.push(colorRGB())
                });

                const data = {
                    labels: labels,
                    datasets: [
                        {
                            data: valores,
                            backgroundColor: colores,
                            borderColor: colores,
                            borderWidth: 1,
                        },
                    ],
                }

                const config = {
                    type: 'bar',
                    data: data,
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false,
                            },
                        }
                    },
                };

                grafico1(config)

                document.getElementById('nombreReporte').innerText = e.target.textContent

            },
            error: function (jqXHR, textStatus, errorThrown) {
                // Aquí puedes manejar errores, por ejemplo:
                console.error("Error al enviar:", textStatus, errorThrown);
            }
        })
    })

    ///////////////////////////////////////////////////////





    ////////////// REPORTES ESTADISTICOS DISCIPULOS //////////////

    $('#botonDiscipulos1').on('click', function (e) {
        $.ajax({
            type: "GET",
            url: "http://localhost/AppwebMVC/Estadisticas/Iglesia",
            data: {
                discipulos_consolidados_fecha: 'discipulos_consolidados_fecha',
            },
            success: function (response) {
                console.log(response);

                let json = JSON.parse(response);

                let labels = [];
                let valores = [];
                let colores = [];

                json.forEach(element => {
                    labels.push(element.fechaConvercion)
                    valores.push(element.cantidad_discipulos)
                    colores.push(colorRGB())
                });

                const data = {
                    labels: labels,
                    datasets: [
                        {
                            data: valores,
                            backgroundColor: colores,
                            borderColor: colores,
                            borderWidth: 1,
                        },
                    ],
                }

                const config = {
                    type: 'polarArea',
                    data: data,
                    options: {
                      responsive: true,
                      scales: {
                        r: {
                          pointLabels: {
                            display: true,
                            centerPointLabels: true,
                            font: {
                              size: 15
                            }
                          }
                        }
                      },
                      plugins: {
                        legend: {
                          position: 'top',
                        },
                        title: {
                          display: false,
                        }
                      }
                    },
                  };

                grafico1(config)

                document.getElementById('nombreReporte').innerText = e.target.textContent

            },
            error: function (jqXHR, textStatus, errorThrown) {
                // Aquí puedes manejar errores, por ejemplo:
                console.error("Error al enviar:", textStatus, errorThrown);
            }
        })
    })


    ////////////////////////////////////////////////////////






    let tipoCelula;
    $('#botonCelulaFamiliar2').on('click', function (e) {
        document.getElementById('nombreSeleccionador').innerText = 'Seleccione la celula familiar'
        listar_celulas('familiar')
        tipoCelula = 'familiar'
    })

    $('#botonCelulaCrecimiento2').on('click', function (e) {
        document.getElementById('nombreSeleccionador').innerText = 'Seleccione la celula crecimiento'
        listar_celulas('crecimiento')
        tipoCelula = 'crecimiento'
    })

    $('#botonCelulaConsolidacion2').on('click', function (e) {
        document.getElementById('nombreSeleccionador').innerText = 'Seleccione la celula consolidacion'
        listar_celulas('consolidacion')
        tipoCelula = 'consolidacion'
    })

    $('#consultaAsistencia').on('click', function (e) {
        const idCelula = document.getElementById('selectorCelulas').value;
        $.ajax({
            type: "GET",
            url: "http://localhost/AppwebMVC/Estadisticas/Iglesia",
            data: {

                asistencias_reuniones_celulas: 'asistencias_reuniones_celulas',
                idCelula: idCelula,
                tipo: tipoCelula

            },
            success: function (response) {

                let json = JSON.parse(response);

                let labels = [];
                let valores = [];
                let colores = [];

                json.forEach(element => {
                    labels.push(element.fecha)
                    valores.push(element.cantidad_asistencia)
                    colores.push(colorRGB())
                });

                const data = {
                    labels: labels,
                    datasets: [
                        {
                            label: json[0].nombre,
                            data: valores,
                            backgroundColor: colores,
                            borderColor: colores,
                            borderWidth: 1,
                            fill: true,
                            pointStyle: 'circle',
                            pointRadius: 10,
                            pointHoverRadius: 15
                        },
                    ],
                }

                const config = {
                    type: 'line',
                    data: data,
                };

                grafico1(config)

                $('#modal2').modal('hide')
                $('#modal1').modal('show')

                //document.getElementById('nombreReporte').innerText = e.target.textContent

            },
            error: function (jqXHR, textStatus, errorThrown) {
                // Aquí puedes manejar errores, por ejemplo:
                console.error("Error al enviar:", textStatus, errorThrown);
                alert("Hubo un error al realizar el registro. Por favor, inténtalo de nuevo.");
            }
        })
    })


    let choices;
    function listar_celulas(tipo) {
        $.ajax({
            type: "GET",
            url: "http://localhost/AppwebMVC/Estadisticas/Iglesia",
            data: {

                listar_celulas: 'listar_celulas',
                tipo: tipo

            },
            success: function (response) {

                console.log(response);
                let data = JSON.parse(response);

                // Destruir la instancia existente si la hay
                if (choices) {
                    choices.destroy();
                }

                let selector = document.getElementById('selectorCelulas');
                selector.innerHTML = '';

                const optionVacio = document.createElement('option');
                optionVacio.value = '';
                optionVacio.text = 'Seleccionador de celulas';
                optionVacio.disabled = true;
                selector.appendChild(optionVacio);

                data.forEach(item => {

                    const option = document.createElement('option');
                    option.value = item.id;
                    option.text = `${item.codigo} ${item.nombre}`;
                    selector.appendChild(option);

                });

                choices = new Choices(selector, {
                    allowHTML: true,
                    searchEnabled: true,  // Habilita la funcionalidad de búsqueda
                    removeItemButton: true,  // Habilita la posibilidad de remover items
                });

                choices.setChoiceByValue('');

            },
            error: function (jqXHR, textStatus, errorThrown) {
                // Aquí puedes manejar errores, por ejemplo:
                console.error("Error al enviar:", textStatus, errorThrown);
                alert("Hubo un error al realizar el registro. Por favor, inténtalo de nuevo.");
            }
        })
    }






    ///////// CREACION DEL OBJETO CHART JS //////////
    // Se hizo de una forma modular para no repetir tanto. Love u

    let chart1;
    function grafico1(config) {
        const ctx = document.getElementById('estadistica1');

        if (chart1) {
            chart1.destroy()
        }

        chart1 = new Chart(ctx, config)
    }


    // Funciones para generar colores aleatorios para el Chart JS

    function generarNumero(numero) {
        return (Math.random() * numero).toFixed(0);
    }
    function colorRGB() {
        var color = "(" + generarNumero(255) + "," + generarNumero(255) + "," + generarNumero(255) + ", 0.5)";
        return "rgb" + color;
    }

})