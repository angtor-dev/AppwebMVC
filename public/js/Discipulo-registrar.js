
$(document).ready(function () {


    function Listar_Consolidador() {

        $.ajax({
            type: "GET",
            url: "http://localhost/AppwebMVC/Discipulos/Registrar",
            data: {

                listaConsolidador: 'listaConsolidador',

            },
            success: function (response) {

                let data = JSON.parse(response);



                let selector = document.getElementById('idConsolidador');

                data.forEach(item => {

                    const option = document.createElement('option');
                    option.value = item.id;
                    option.text = `${item.id} ${item.cedula} ${item.nombre} ${item.apellido}`;
                    selector.appendChild(option);

                });
                const element = document.getElementById('idConsolidador');
                const choices = new Choices(element, {
                    searchEnabled: true,  // Habilita la funcionalidad de búsqueda
                    removeItemButton: true,  // Habilita la posibilidad de remover items
                    placeholderValue: 'Selecciona una opción',  // Texto del placeholder
                });

                // console.log(data);



            },
            error: function (jqXHR, textStatus, errorThrown) {
                // Aquí puedes manejar errores, por ejemplo:
                console.error("Error al enviar:", textStatus, errorThrown);
                alert("Hubo un error al realizar el registro. Por favor, inténtalo de nuevo.");
            }
        })
    }

    Listar_Consolidador();

    function Listar_celulas() {

        $.ajax({
            type: "GET",
            url: "http://localhost/AppwebMVC/Discipulos/Registrar",
            data: {

                listarcelulas: 'listarcelulas',

            },
            success: function (response) {

                

                let data = JSON.parse(response);
                
                console.log(data);

                let selector = document.getElementById('idcelulaconsolidacion');


                data.forEach(item => {

                    const option = document.createElement('option');
                    option.value = item.id;
                    option.text = `${item.codigo} ${item.nombre}`;
                    selector.appendChild(option);

                });

    
                const element = document.getElementById('idcelulaconsolidacion');
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






    let validaciones = {
            nombre: false,
            apellido: false,
            cedula: false,
            telefono: false,
            estadoCivil: false,
            fechaNacimiento: false,
            fechaConvercion: false,
            idConsolidador: false,
            idcelulaconsolidacion: false,
            direccion: false,
            motivo: false
            
    };


    $("#formulario").on("change", function (event) {
        // Previene el comportamiento predeterminado del formulario
        event.preventDefault();



       
        let nombre = $("#nombre").val();
        if (/^[a-zA-Z\s]{1,30}$/.test(nombre)) {
            validaciones.nombre = true;
            $("#nombre").removeClass("is-invalid");
            $("#nombre").addClass("is-valid");
            
        } else {
            validaciones.nombre = false;
            $("#nombre").removeClass("is-valid");
            $("#nombre").addClass("is-invalid");
            
        }

        let apellido = $("#apellido").val();
        if (/^[a-zA-Z\s]{1,30}$/.test(apellido)) {
            validaciones.apellido = true;
            $("#apellido").removeClass("is-invalid");
            $("#apellido").addClass("is-valid");
            
        } else {
            validaciones.apellido = false;
            $("#apellido").removeClass("is-valid");
            $("#apellido").addClass("is-invalid");
            
        }

        let cedula = $("#cedula").val();
        if (/^[0-9]{7,8}$/.test(cedula)) {
            validaciones.cedula = true;
            $("#cedula").removeClass("is-invalid");
            $("#cedula").addClass("is-valid");
            
        } else {
            validaciones.cedula = false;
            $("#cedula").removeClass("is-valid");
            $("#cedula").addClass("is-invalid");
            
        }
        
        
        
        let telefono = $("#telefono").val();
        if (/^(0414|0424|0416|0426|0412)[0-9]{7}/.test(telefono)) {
            validaciones.telefono = true;
            $("#telefono").removeClass("is-invalid");
            $("#telefono").addClass("is-valid");
        } else {
            validaciones.telefono = false;
            $("#telefono").removeClass("is-valid");
            $("#telefono").addClass("is-invalid");
        }

        let estadoCivil = $("#estadoCivil").val();
        let estadosPermitido = ["casado", "soltero", "viudo"];
        if (estadosPermitido.includes(estadoCivil)) {
            validaciones.estadoCivil = true;
            $("#estadoCivil").removeClass("is-invalid");
            $("#estadoCivil").addClass("is-valid");
        } else {
            validaciones.estadoCivil = false;
            $("#estadoCivil").removeClass("is-valid");
            $("#estadoCivil").addClass("is-invalid");
        }
        

        let fechaNacimiento = $("#fechaNacimiento").val();
        if (/^.+$/.test(fechaNacimiento)) {
            validaciones.fechaNacimiento = true;
            $("#fechaNacimiento").removeClass("is-invalid");
            $("#fechaNacimiento").addClass("is-valid");
        } else {
            validaciones.fechaNacimiento = false;
            $("#fechaNacimiento").removeClass("is-valid");
            $("#fechaNacimiento").addClass("is-invalid");
        }

        let fechaConvercion = $("#fechaConvercion").val();
        if (/^.+$/.test(fechaConvercion)) {
            validaciones.fechaConvercion = true;
            $("#fechaConvercion").removeClass("is-invalid");
            $("#fechaConvercion").addClass("is-valid");
        } else {
            validaciones.fechaConvercion = false;
            $("#fechaConvercion").removeClass("is-valid");
            $("#fechaConvercion").addClass("is-invalid");
        }

        let idConsolidador = $("#idConsolidador").val();
        if (/^[1-9]\d*$/.test(idConsolidador)) {
            validaciones.idConsolidador = true;
            $("#idConsolidador").removeClass("is-invalid");
            $("#idConsolidador").addClass("is-valid");
        } else {
            validaciones.idConsolidador = false;
            $("#idConsolidador").removeClass("is-valid");
            $("#idConsolidador").addClass("is-invalid");
        }

        let idcelulaconsolidacion = $("#idcelulaconsolidacion").val();
        if (/^[1-9]\d*$/.test(idcelulaconsolidacion)) {
            validaciones.idcelulaconsolidacion = true;
            $("#idcelulaconsolidacion").removeClass("is-invalid");
            $("#idcelulaconsolidacion").addClass("is-valid");
        } else {
            validaciones.idcelulaconsolidacion = false;
            $("#idcelulaconsolidacion").removeClass("is-valid");
            $("#idcelulaconsolidacion").addClass("is-invalid");} 
        
        

        let direccion = $("#direccion").val();
        if (/^[a-zA-Z0-9\s]{1,100}$/.test(direccion)) {
            validaciones.direccion = true;
            $("#direccion").removeClass("is-invalid");
            $("#direccion").addClass("is-valid");
        } else {
            validaciones.direccion = false;
            $("#direccion").removeClass("is-valid");
            $("#direccion").addClass("is-invalid");
        }

        let motivo = $("#motivo").val();
        if (/^[a-zA-Z0-9\s]{1,100}$/.test(motivo)) {
            validaciones.motivo = true;
            $("#motivo").removeClass("is-invalid");
            $("#motivo").addClass("is-valid");
        } else {
            validaciones.motivo = false;
            $("#motivo").removeClass("is-valid");
            $("#motivo").addClass("is-invalid");
        }

        
       
 
        // Verificar si todas las validaciones son correctas*/
           
    
     });
   

   $("#formulario").submit(function (event) {


     if (Object.values(validaciones).every(val => val)) {
    

        let asisFamiliar = $("#asisFamiliar").val();
        let asisCrecimiento = $("#asisCrecimiento").val();
        let nombre = $("#nombre").val();
        let apellido = $("#apellido").val();
        let cedula = $("#cedula").val();
        let telefono = $("#telefono").val();
        let estadoCivil = $("#estadoCivil").val();
        let fechaNacimiento = $("#fechaNacimiento").val();
        let fechaConvercion = $("#fechaConvercion").val();
        let idConsolidador = $("#idConsolidador").val();
        let idcelulaconsolidacion = $("#idcelulaconsolidacion").val();
        let direccion = $("#direccion").val();
        let motivo = $("#motivo").val();

        
       
        event.preventDefault();
        
    

        $.ajax({
            type: "POST",
            url: "http://localhost/AppwebMVC/Discipulos/Registrar",
            data: {

                registrar: 'registrar',
                nombre: nombre,
                apellido: apellido,
                cedula: cedula,
                telefono: telefono,
                estadoCivil: estadoCivil,
                fechaNacimiento: fechaNacimiento,
                fechaConvercion: fechaConvercion,
                asisCrecimiento: asisCrecimiento,
                asisFamiliar: asisFamiliar,
                idConsolidador: idConsolidador,
                idcelulaconsolidacion: idcelulaconsolidacion,
                direccion: direccion,
                motivo: motivo
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
    
    
    }else {

        alert('Llena el formulario correctamente'); }


   });
   
   

});

