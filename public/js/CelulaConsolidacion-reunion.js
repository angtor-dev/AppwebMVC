$(document).ready(function () {

    const dataTable = $('#celulaDatatables').DataTable({
        responsive: true,
        ajax: {
            method: "GET",
            url: 'http://localhost/AppwebMVC/CelulaConsolidacion/Reunion',
            data: { cargar_data: 'cargar_data' }
        },
        columns: [
            { data: 'codigo' },
            { data: 'nombre' },
            { data: 'fecha' },
            {
                defaultContent: `
            <button type="button" id="ver_info" data-bs-toggle="modal" data-bs-target="#modal_verInfo" class="btn btn-light">Info</button>
            <button type="button" id="editar" data-bs-toggle="modal" data-bs-target="#modal_editarInfo" class="btn btn-primary">Editar</button>
            <button type="button" id="eliminar" class="btn btn-danger delete-btn">Eliminar</button>
            `}

        ],
    })

    $('#celulaDatatables tbody').on('click', '#ver_info', function () {
        const datos = dataTable.row($(this).parents()).data();

        document.getElementById('inf_codigocelulaconsolidacion').textContent = datos.codigo;
        document.getElementById('inf_fecha').textContent = datos.fecha;
        document.getElementById('inf_tematica').textContent = datos.tematica;
        document.getElementById('inf_semana').textContent = datos.semana;
        document.getElementById('inf_generosidad').textContent = datos.generosidad;
        document.getElementById('inf_actividad').textContent = datos.actividad;
        document.getElementById('inf_observaciones').textContent = datos.observaciones;

    })

    $('#celulaDatatables tbody').on('click', '#editar', function () {
        const datos = dataTable.row($(this).parents()).data();

        document.getElementById('idreunionconsolidacion').textContent = datos.id;
        document.getElementById('idCelulaConsolidacion').value = datos.idcelulafamiliar;
        document.getElementById('fecha').value = datos.fecha;
        document.getElementById('tematica').value = datos.tematica;
        document.getElementById('semana').value= datos.semana;
        document.getElementById('generosidad').value = datos.generosidad;
        document.getElementById('actividad').value = datos.actividad;
        document.getElementById('observaciones').value = datos.observaciones;

    })

    

    $('#celulaDatatables tbody').on('click', '#eliminar', function () {
        const datos = dataTable.row($(this).parents()).data();

        Swal.fire({
            title: '¿Estas Seguro?',
            text: "No podras acceder a esta reunion otra vez!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '¡Si, estoy seguro!',
            confirmButtonColor: '#007bff',
            cancelButtonText: '¡No, cancelar!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {

                $.ajax({
                    type: "POST",
                    url: "http://localhost/AppwebMVC/CelulaCrecimiento/Reunion",
                    data: {

                        eliminar: 'eliminar',
                        id: datos.id,
                    },
                    success: function (response) {
                        console.log(response);
                        let data = JSON.parse(response);
                        dataTable.ajax.reload();

                        Swal.fire({
                            icon: 'success',
                            title: '¡Borrado!',
                            text: 'La reunion ha sido borrada',
                            showConfirmButton: false,
                            timer: 2000,
                        })

                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        if (jqXHR.responseText) {
                            let jsonResponse = JSON.parse(jqXHR.responseText);
                    
                            if (jsonResponse.msj) {
                                Swal.fire({
                                    icon: 'error',
                                    title: jsonResponse.msj,
                                    showConfirmButton: true,
                                })
                            } else {
                                const respuesta = JSON.stringify(jsonResponse, null, 2)
                                Swal.fire({
                                    background: 'red',
                                    color: '#fff',
                                    title: respuesta,
                                    showConfirmButton: true,
                                })
                            }
                        } else {
                            alert('Error desconocido: ' + textStatus);
                        }
                    }
                })
            } 
        });
    });


    function Listar_celulas() {

        $.ajax({
            type: "GET",
            url: "http://localhost/AppwebMVC/CelulaConsolidacion/Reunion",
            data: {

                listarcelulas: 'listarcelulas',

            },
            success: function (response) {

                let data = JSON.parse(response);
                
                console.log(data);

                let selector = document.getElementById('idCelulaConsolidacion');


                data.forEach(item => {

                    const option = document.createElement('option');
                    option.value = item.id;
                    option.text = `${item.codigo} ${item.nombre}`;
                    selector.appendChild(option);

                });

    
                const element = document.getElementById('idCelulaConsolidacion');
                const choices = new Choices(element, {
                    searchEnabled: true,  // Habilita la funcionalidad de búsqueda
                    removeItemButton: true,  // Habilita la posibilidad de remover items
                    placeholderValue: 'Selecciona una opción',  // Texto del placeholder
                });


                //console.log(data);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                // Aquí puedes manejar errores, por ejemplo:
                console.error("Error al enviar:", textStatus, errorThrown);
            }
        })
    }

    Listar_celulas();


    //Registro de Reuinion de celula      


    const regexObj2 = {

        idCelulaConsolidacion: /^[1-9]\d*$/, // Números enteros mayores a 0
        tematica: /^[a-zA-Z0-9\s.,]{1,100}$/, // Letras, números, espacios, puntos y comas con un máximo de 100 caracteres
        semana: /^[1-9]\d*$/, // Números enteros mayores a 0
        generosidad: /^[0-9]+(\.[0-9]{2})?$/,
        actividad: /^[a-zA-Z0-9\s.,]{1,100}$/, // Letras, números, espacios, puntos y comas con un máximo de 100 caracteres
        observaciones: /^[a-zA-Z0-9\s.,]{1,100}$/ // Letras, números, espacios, puntos y comas con un máximo de 100 caracteres
    };

    const validationStatus2 = {

        idCelulaConsolidacion: false,
        tematica: false,
        semana: false,
        generosidad: false,
        actividad: false,
        observaciones: false
    };


    const form2 = document.getElementById("formularioReunion");

    form2.addEventListener("submit", (e) => {
        e.preventDefault();

        const id = document.getElementById('idreunionconsolidacion').textContent



         // Validar idCelulaConsolidacion
         const idCelulaConsolidacion = document.getElementById("idCelulaConsolidacion").value;
         if (!regexObj2.idCelulaConsolidacion.test(idCelulaConsolidacion)) {
             document.getElementById("msj_idCelulaConsolidacion").classList.remove("d-none");
             validationStatus2.idCelulaConsolidacion = false;
         } else {
             document.getElementById("msj_idCelulaConsolidacion").classList.add("d-none");
             validationStatus2.idCelulaConsolidacion = true;
         }


        
        // Validar fecha
        const fecha = document.getElementById("fecha").value;
       /* if (fecha === "") {
            document.getElementById("msj_fecha").classList.remove("d-none");
            validationStatus2.fecha = false;
        } else {
            // Comprobar que la fecha esté en un formato válido
            if (!regexObj2.actividad.test(fecha)) {
                document.getElementById("msj_fecha").classList.remove("d-none");
                validationStatus2.fecha = false;
            } else {
                document.getElementById("msj_fecha").classList.add("d-none");
                validationStatus2.fecha = true;
            }
        }*/

        // Validar tematica
        const tematica = document.getElementById("tematica").value;
        if (!regexObj2.tematica.test(tematica)) {
            document.getElementById("msj_tematica").classList.remove("d-none");
            validationStatus2.tematica = false;
        } else {
            document.getElementById("msj_tematica").classList.add("d-none");
            validationStatus2.tematica = true;
        }

        // Validar semana
        const semana = document.getElementById("semana").value;
        if (!regexObj2.semana.test(semana)) {
            document.getElementById("msj_semana").classList.remove("d-none");
            validationStatus2.semana = false;
        } else {
            document.getElementById("msj_semana").classList.add("d-none");
            validationStatus2.semana = true;
        }

        // Validar generosidad
        const generosidad = document.getElementById("generosidad").value;
        if (!regexObj2.generosidad.test(generosidad)) {
            document.getElementById("msj_generosidad").classList.remove("d-none");
            validationStatus2.generosidad = false;
        } else {
            document.getElementById("msj_generosidad").classList.add("d-none");
            validationStatus2.generosidad = true;
        }

 

        // Validar actividad
        const actividad = document.getElementById("actividad").value;
        if (!regexObj2.actividad.test(actividad)) {
            document.getElementById("msj_actividad").classList.remove("d-none");
            validationStatus2.actividad = false;
        } else {
            document.getElementById("msj_actividad").classList.add("d-none");
            validationStatus2.actividad = true;
        }

        // Validar observaciones
        const observaciones = document.getElementById("observaciones").value;
        if (!regexObj2.observaciones.test(observaciones)) {
            document.getElementById("msj_juvenil").classList.remove("d-none");
            validationStatus2.observaciones = false;
        } else {
            document.getElementById("msj_observaciones").classList.add("d-none");
            validationStatus2.observaciones = true;
        }



        // Verifica si todos los campos son válidos antes de enviar el formulario
        if (Object.values(validationStatus2).every(status => status === true)) {
            console.log("Formulario válido. Puedes enviar los datos al servidor");
            // Aquí puedes agregar el código para enviar el formulario
            $.ajax({
                type: "POST",
                url: "http://localhost/AppwebMVC/CelulaConsolidacion/Reunion",
                data: {

                    editar: 'editar',
                    id: id,
                    idCelulaConsolidacion: idCelulaConsolidacion,
                    fecha: fecha,
                    tematica: tematica,
                    semana: semana,
                    generosidad: generosidad,
                    actividad: actividad,
                    observaciones: observaciones
                },
                success: function (response) {

                    let data = JSON.parse(response);
                    dataTable.ajax.reload();

                    // Aquí puedes manejar una respuesta exitosa, por ejemplo:
                    console.log("Respuesta del servidor:", data);
                    Swal.fire({
                        icon: 'success',
                        title: 'Se actualizo correctamente la Reunion',
                        showConfirmButton: false,
                        timer: 2000,
                    })

                    document.getElementById("formularioReunion").reset();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    // Aquí puedes manejar errores, por ejemplo:
                    console.error("Error al enviar:", textStatus, errorThrown);
                    alert("Hubo un error al realizar el registro. Por favor, inténtalo de nuevo.");
                }
            });




        } else {
            Swal.fire({
                icon: 'error',
                title: 'Formulario llenado incorrectamente. Por favor, verifique sus datos',
                showConfirmButton: false,
                timer: 2000,
            })
        }
    });

});

