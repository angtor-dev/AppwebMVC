$(document).ready(function () {

    let choices1
    let choices2
    let choices3

    function Listar_Lideres() {

        $.ajax({
            type: "GET",
            url: "http://localhost/AppwebMVC/CelulaConsolidacion/Registrar",
            data: {

                listaLideres: 'listaLideres',

            },
            success: function (response) {

                let data = JSON.parse(response);
                let data2 = JSON.parse(response);


                let selector = document.getElementById('idLider');
                let selector2 = document.getElementById('idCoLider');

                data.forEach(item => {

                    const option = document.createElement('option');
                    option.value = item.id;
                    option.text = `${item.cedula} ${item.nombre} ${item.apellido}`;
                    selector.appendChild(option);

                });

                data2.forEach(item => {

                    const option = document.createElement('option');
                    option.value = item.id;
                    option.text = `${item.cedula} ${item.nombre} ${item.apellido}`;
                    selector2.appendChild(option);

                });


                choices1 = new Choices(selector, {
                    allowHTML: true,
                    searchEnabled: true,  // Habilita la funcionalidad de búsqueda
                    removeItemButton: true,  // Habilita la posibilidad de remover items
                    placeholderValue: 'Selecciona una opción',  // Texto del placeholder
                });

                choices2 = new Choices(selector2, {
                    allowHTML: true,
                    searchEnabled: true,  // Habilita la funcionalidad de búsqueda
                    removeItemButton: true,  // Habilita la posibilidad de remover items
                    placeholderValue: 'Selecciona una opción',  // Texto del placeholder
                });

            },
            error: function (jqXHR, textStatus, errorThrown) {
                // Aquí puedes manejar errores, por ejemplo:
                console.error("Error al enviar:", textStatus, errorThrown);
            }
        })
    }

    Listar_Lideres();



    function Listar_Territorio() {

        $.ajax({
            type: "GET",
            url: "http://localhost/AppwebMVC/CelulaConsolidacion/Registrar",
            data: {

                listaTerritorio: 'listaTerritorio',

            },
            success: function (response) {


                let data = JSON.parse(response);

                let selector = document.getElementById('idTerritorio');

                data.forEach(item => {

                    const option = document.createElement('option');
                    option.value = item.id;
                    option.text = `${item.codigo} ${item.nombre}`;
                    selector.appendChild(option);

                });
                choices3 = new Choices(selector, {
                    allowHTML: true,
                    searchEnabled: true,  // Habilita la funcionalidad de búsqueda
                    removeItemButton: true,  // Habilita la posibilidad de remover items
                    placeholderValue: 'Selecciona una opción',  // Texto del placeholder
                });

            },
            error: function (jqXHR, textStatus, errorThrown) {
                // Aquí puedes manejar errores, por ejemplo:
                console.error("Error al enviar:", textStatus, errorThrown);
                alert("Hubo un error al realizar el registro. Por favor, inténtalo de nuevo.");
            }
        })
    }

    Listar_Territorio();


    const regexObj = {
        
        nombre: /^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]{5,50}$/, // Letras, números, espacios, puntos y comas con un máximo de 20 caracteres
        idLider: /^[1-9]\d*$/, // Números enteros mayores a 0
        idCoLider: /^[1-9]\d*$/, // Números enteros mayores a 0
        idTerritorio: /^[1-9]\d*$/, // Números enteros mayores a 0

    };

    const validationStatus = {
        nombre: false,
        idLider: false,
        idCoLider: false,
        idTerritorio: false
    };


    //Validar nombre
    const nombre = document.getElementById("nombre");
    nombre.addEventListener('keyup', (e) => {
        // Validar nombre
        if (!regexObj.nombre.test(e.target.value)) {
            document.getElementById("msj_nombre").classList.remove("d-none");
            validationStatus.nombre = false;
        } else {
            document.getElementById("msj_nombre").classList.add("d-none");
            validationStatus.nombre = true;
        }
    })


    // Validacion de idLider y idCoLider

    const idLider = document.getElementById("idLider");
    const idCoLider = document.getElementById("idCoLider");

    idLider.addEventListener('change', (e) => {
        if (!regexObj.idLider.test(e.target.value) || e.target.value === idCoLider.value) {
            document.getElementById("msj_idLider").classList.remove("d-none");
            validationStatus.idLider = false;
        } else {
            document.getElementById("msj_idLider").classList.add("d-none");
            validationStatus.idLider = true;
        }
    })

    idCoLider.addEventListener('change', (e) => {
        if (!regexObj.idCoLider.test(e.target.value) || e.target.value === idLider.value) {
            document.getElementById("msj_idCoLider").classList.remove("d-none");
            validationStatus.idCoLider = false;
        } else {
            document.getElementById("msj_idCoLider").classList.add("d-none");
            validationStatus.idCoLider = true;
        }
    })


    // Validar idTerritorio
    const idTerritorio = document.getElementById("idTerritorio");
    idTerritorio.addEventListener('change', (e) => {
        if (!regexObj.idTerritorio.test(e.target.value)) {
            document.getElementById("msj_idTerritorio").classList.remove("d-none");
            validationStatus.idSede = false;
        } else {
            document.getElementById("msj_idTerritorio").classList.add("d-none");
            validationStatus.idTerritorio = true;
        }
    })



    const form = document.getElementById("formulario");
    form.addEventListener("submit", (e) => {
        e.preventDefault();

        // Verifica si todos los campos son válidos antes de enviar el formulario
        if (Object.values(validationStatus).every(status => status === true)) {
            // Aquí puedes agregar el código para enviar el formulario
            $.ajax({
                type: "POST",
                url: "http://localhost/AppwebMVC/CelulaConsolidacion/Registrar",
                data: {

                    registrar: 'registrar',
                    nombre: nombre,
                    idLider: idLider,
                    idCoLider: idCoLider,
                    idTerritorio: idTerritorio
                },
                success: function (response) {
                    console.log(response);
                    let data = JSON.parse(response);

                    Swal.fire({
                        icon: 'success',
                        title: 'Registrado Correctamente',
                        showConfirmButton: false,
                        timer: 2000,
                    })

                    document.getElementById('nombre').value = '';
                    choices1.setChoiceByValue('');
                    choices2.setChoiceByValue('');
                    choices3.setChoiceByValue('');

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
            });


        } else {
            Swal.fire({
                icon: 'error',
                title: 'Formulario invalido. Por favor, verifique sus datos',
                showConfirmButton: false,
                timer: 2000,
            })
        }
    });

});


