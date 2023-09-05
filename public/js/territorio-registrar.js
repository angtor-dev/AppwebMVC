
$(document).ready(function () {



    function Listar_Lideres() {

        $.ajax({
            type: "GET",
            url: "http://localhost/AppwebMVC/Territorios/Registrar",
            data: {

                listaLideres: 'listaLideres',

            },
            success: function (response) {

                let data = JSON.parse(response);

                let selector = document.getElementById('idLider');

                data.forEach(item => {

                    const option = document.createElement('option');
                    option.value = item.id;
                    option.text = `${item.cedula} ${item.nombre} ${item.apellido}`;
                    selector.appendChild(option);

                });
                
                const element = document.getElementById('idLider');
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

    Listar_Lideres();

    function Listar_Sedes() {

        $.ajax({
            type: "GET",
            url: "http://localhost/AppwebMVC/Territorios/Registrar",
            data: {

                listaSedes: 'listaSedes',

            },
            success: function (response) {


                let data = JSON.parse(response);

                let selector = document.getElementById('idSede');

                data.forEach(item => {

                    const option = document.createElement('option');
                    option.value = item.id;
                    option.text = `${item.codigo} ${item.nombre}`;
                    selector.appendChild(option);

                });
                const element = document.getElementById('idSede');
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

    Listar_Sedes();







    const regexObj = {
        idSede: /^[1-9]\d*$/, // Números enteros mayores a 0
        nombre: /^[a-zA-Z0-9\s.,]{1,20}$/, // Letras, números, espacios, puntos y comas con un máximo de 20 caracteres
        idLider: /^[1-9]\d*$/, // Números enteros mayores a 0
        detalles: /^[a-zA-Z0-9\s.,]{1,100}$/ // Letras, números, espacios, puntos y comas con un máximo de 100 caracteres
    };
    
    const validationStatus = {
        idSede: false,
        nombre: false,
        idLider: false,
        detalles: false
    };
    
    
        const form = document.getElementById("formulario");
    
        form.addEventListener("submit", (e) => {
            e.preventDefault();
            
            // Validar idSede
            const idSede = document.getElementById("idSede").value;
            if(!regexObj.idSede.test(idSede)) {
                document.getElementById("msj_idSede").classList.remove("d-none");
                validationStatus.idSede = false;
            } else {
                document.getElementById("msj_idSede").classList.add("d-none");
                validationStatus.idSede = true;
            }
    
            // Validar nombre
            const nombre = document.getElementById("nombre").value;
            if(!regexObj.nombre.test(nombre)) {
                document.getElementById("msj_nombre").classList.remove("d-none");
                validationStatus.nombre = false;
            } else {
                document.getElementById("msj_nombre").classList.add("d-none");
                validationStatus.nombre = true;
            }
    
            // Validar idLider
            const idLider = document.getElementById("idLider").value;
            if(!regexObj.idLider.test(idLider)) {
                document.getElementById("msj_idLider").classList.remove("d-none");
                validationStatus.idLider = false;
            } else {
                document.getElementById("msj_idLider").classList.add("d-none");
                validationStatus.idLider = true;
            }
    
            // Validar detalles
            const detalles = document.getElementById("detalles").value;
            if(!regexObj.detalles.test(detalles)) {
                document.getElementById("msj_detalles").classList.remove("d-none");
                validationStatus.detalles = false;
            } else {
                document.getElementById("msj_detalles").classList.add("d-none");
                validationStatus.detalles = true;
            }
            
            // Verifica si todos los campos son válidos antes de enviar el formulario
            if(Object.values(validationStatus).every(status => status === true)) {
                console.log("Formulario válido. Puedes enviar los datos al servidor");
                // Aquí puedes agregar el código para enviar el formulario
                $.ajax({
                    type: "POST",
                    url: "http://localhost/AppwebMVC/Territorios/Registrar",
                    data: {
                
                        registrar: 'registrar',
                        idSede: idSede,
                        nombre: nombre,
                        idLider: idLider,
                        detalles: detalles
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
    



