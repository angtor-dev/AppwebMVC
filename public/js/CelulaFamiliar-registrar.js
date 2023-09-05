$(document).ready(function () {



    function Listar_Lideres() {

        $.ajax({
            type: "GET",
            url: "http://localhost/AppwebMVC/CelulaFamiliar/Registrar",
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



                const element = document.getElementById('idLider');
                const choices = new Choices(element, {
                    searchEnabled: true,  // Habilita la funcionalidad de búsqueda
                    removeItemButton: true,  // Habilita la posibilidad de remover items
                    placeholderValue: 'Selecciona una opción',  // Texto del placeholder
                });

                const element2 = document.getElementById('idCoLider');
                const choices2 = new Choices(element2, {
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

    Listar_Lideres();



    function Listar_Territorio() {

        $.ajax({
            type: "GET",
            url: "http://localhost/AppwebMVC/CelulaFamiliar/Registrar",
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
                const element = document.getElementById('idTerritorio');
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
                alert("Hubo un error al realizar el registro. Por favor, inténtalo de nuevo.");
            }
        })
    }

    Listar_Territorio();







    const regexObj = {
        
        nombre: /^[a-zA-Z0-9\s.,]{1,20}$/, // Letras, números, espacios, puntos y comas con un máximo de 20 caracteres
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


    const form = document.getElementById("formulario");

    form.addEventListener("submit", (e) => {
        e.preventDefault();



        // Validar nombre
        const nombre = document.getElementById("nombre").value;
        if (!regexObj.nombre.test(nombre)) {
            document.getElementById("msj_nombre").classList.remove("d-none");
            validationStatus.nombre = false;
        } else {
            document.getElementById("msj_nombre").classList.add("d-none");
            validationStatus.nombre = true;
        }

        // Validar idLider
        const idLider = document.getElementById("idLider").value;
        if (!regexObj.idLider.test(idLider)) {
            document.getElementById("msj_idLider").classList.remove("d-none");
            validationStatus.idLider = false;
        } else {
            document.getElementById("msj_idLider").classList.add("d-none");
            validationStatus.idLider = true;
        }

        // Validar idCoLider
        const idCoLider = document.getElementById("idCoLider").value;
        if (!regexObj.idCoLider.test(idCoLider)) {
            document.getElementById("msj_idCoLider").classList.remove("d-none");
            validationStatus.idCoLider = false;
        } else {
            document.getElementById("msj_idCoLider").classList.add("d-none");
            validationStatus.idCoLider = true;
        }

        // Validar idTerritorio
        const idTerritorio = document.getElementById("idTerritorio").value;
        if (!regexObj.idTerritorio.test(idTerritorio)) {
            document.getElementById("msj_idTerritorio").classList.remove("d-none");
            validationStatus.idSede = false;
        } else {
            document.getElementById("msj_idTerritorio").classList.add("d-none");
            validationStatus.idTerritorio = true;
        }
        

        // Verifica si todos los campos son válidos antes de enviar el formulario
        if (Object.values(validationStatus).every(status => status === true)) {
            console.log("Formulario válido. Puedes enviar los datos al servidor");
            // Aquí puedes agregar el código para enviar el formulario
            $.ajax({
                type: "POST",
                url: "http://localhost/AppwebMVC/CelulaFamiliar/Registrar",
                data: {

                    registrar: 'registrar',
                    nombre: nombre,
                    idLider: idLider,
                    idCoLider: idCoLider,
                    idTerritorio: idTerritorio
                },
                success: function (response) {

                    let data = JSON.parse(response);

                    // Aquí puedes manejar una respuesta exitosa, por ejemplo:
                    console.log("Respuesta del servidor:", data);
                    Swal.fire({
                        icon: 'success',
                        title: 'Registrado Correctamente',
                        showConfirmButton: false,
                        timer: 2000,
                    })

                    document.getElementById("#formulario").reset();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    // Aquí puedes manejar errores, por ejemplo:
                    console.error("Error al enviar:", textStatus, errorThrown);
                    alert("Hubo un error al realizar el registro. Por favor, inténtalo de nuevo.");
                }
            });




        } else {
            console.log("Formulario inválido. Por favor, corrija los errores.");
        }
    });

});


