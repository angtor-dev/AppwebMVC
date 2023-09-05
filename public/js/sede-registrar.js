
$(document).ready(function () {


    function Listar_Pastores() {

        $.ajax({
            type: "GET",
            url: "http://localhost/AppwebMVC/Sedes/Registrar",
            data: {

                listaPastores: 'listaPastores',

            },
            success: function (response) {

                let data = JSON.parse(response);

                

               let selector = document.getElementById('idPastor');

                data.forEach(item => {

                    const option = document.createElement('option');
                    option.value = item.id;
                    option.text = `${item.id} ${item.cedula} ${item.nombre} ${item.apellido}`;
                    selector.appendChild(option);

                });
                const element = document.getElementById('idPastor');
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

    Listar_Pastores();






let validaciones = {
    idPastor: false,
    nombre: false,
    direccion: false,
    estado: false
};


$("#formulario").submit(function(event) {
// Previene el comportamiento predeterminado del formulario
event.preventDefault();

// Validaciones
// Validación de la cédula del pastor
let idPastor = $("#idPastor").val();
        if (/^\d{1,8}$/.test(idPastor)) {
        validaciones.idPastor = true;
        $("#idPastor").removeClass("is-invalid");
        $("#msj_idPastor").addClass("d-none");
        } else {
        validaciones.idPastor = false;
        $("#idPastor").addClass("is-invalid");
        $("#msj_idPastor").removeClass("d-none");
        }
        
        // Validación del nombre de la sede
        let nombre = $("#nombre").val();
        if (/^[a-zA-Z\s]{1,30}$/.test(nombre)) {
        validaciones.nombre = true;
        $("#nombre").removeClass("is-invalid");
        $("#msj_nombre").addClass("d-none");
        } else {
        validaciones.nombre = false;
        $("#nombre").addClass("is-invalid");
        $("#msj_nombre").removeClass("d-none");
        }
        
        // Validación de la dirección
        let direccion = $("#direccion").val();
        if (/^[a-zA-Z0-9\s]{1,100}$/.test(direccion)) {
        validaciones.direccion = true;
        $("#direccion").removeClass("is-invalid");
        $("#msj_direccion").addClass("d-none");
        } else {
        validaciones.direccion = false;
        $("#direccion").addClass("is-invalid");
        $("#msj_direccion").removeClass("d-none");
        }
        
        // Validación del estado
        let estado = $("#estado").val();
        let estadosPermitidos = ["ANZ", "APUR", "ARA", "BAR", "BOL", "CAR", "COJ", "DELTA", "FAL", "GUA",
        "LAR", "MER", "MIR", "MON", "ESP", "POR", "SUC", "TÁCH", "TRU", "VAR", "YAR", "ZUL"];
        if (estadosPermitidos.includes(estado)) {
        validaciones.estado = true;
        $("#estado").removeClass("is-invalid");
        $("#msj_estado").addClass("d-none");
        } else {
        validaciones.estado = false;
        $("#estado").addClass("is-invalid");
        $("#msj_estado").removeClass("d-none");
        }

// Verificar si todas las validaciones son correctas
if (Object.values(validaciones).every(val => val)) {
// Si todas las validaciones son correctas, realiza la petición AJAX
// ... (tu código AJAX va aquí)
$.ajax({
type: "POST",
url: "http://localhost/AppwebMVC/Sedes/Registrar",
data: {

registrar: 'registrar',
idPastor: idPastor,
nombre: nombre,
direccion: direccion,
estado: estado
},
success: function(response) {

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
error: function(jqXHR, textStatus, errorThrown) {
// Aquí puedes manejar errores, por ejemplo:
console.error("Error al enviar:", textStatus, errorThrown);
alert("Hubo un error al realizar el registro. Por favor, inténtalo de nuevo.");
}
});

} else {

alert('Llena el formulario correctamente');
}
});

});