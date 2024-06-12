document.addEventListener('DOMContentLoaded', function () {

  let datatables;
  let choices1;
  let choices_sinSedes;

  var calendarDiv = document.getElementById('calendar');
  const right = permisos.registrar ? 'addEventButton dayGridMonth,listMonth' : 'dayGridMonth,listMonth'

  var calendar = new FullCalendar.Calendar(calendarDiv, {

    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: right
    },

    customButtons: {
      addEventButton: {
        text: 'Agregar Evento',
        click: function () {
          $('#agregar').modal('show');
          Listar_Sedes();
        }
      },
    },

    locale: 'es',
    navLinks: true, // can click day/week names to navigate views
    businessHours: true, // display business hours
    editable: false,

    events: {
      url: '/AppwebMVC/Agenda/Index',
      method: 'GET',
      extraParams: {
        listarEventos: 'listarEventos',
      },
      color: '#FFD28E',   // a non-ajax option
      textColor: 'black'
    },

    eventClick: function (info) {
      if (permisos.actualizar) {
        $('#editarEvento').modal('show');
        document.getElementById('idEvento').textContent = info.event.id;
        document.getElementById('tituloEditar').value = info.event.title;
        document.getElementById('fechaInicioEditar').value = info.event.startStr;
        if (info.event.endStr == '') {
          document.getElementById('fechaFinalEditar').value = info.event.startStr;
        }else{
          document.getElementById('fechaFinalEditar').value = info.event.endStr;
        }
        document.getElementById('descripcionEditar').value = info.event.extendedProps.descripcion;
        listar_sedes_evento(info.event.id);
        sedes_sin_agregar(info.event.id);


      } else if (permisos.registrarComentario) {
        console.log(info.event);
        $('#verEventoPastor').modal('show');
        document.getElementById('idEvento2').textContent = info.event.id;
        document.getElementById('nombre2').textContent = info.event.title;
        document.getElementById('fechaInicio2').textContent = info.event.startStr;
        if (info.event.endStr == '') {
          document.getElementById('fechaCierre2').textContent = info.event.startStr;
        }else{
          document.getElementById('fechaCierre2').textContent = info.event.endStr;
        }
        document.getElementById('descripcion2').textContent = info.event.extendedProps.descripcion;
        document.getElementById('comentarioPastor').value = info.event.extendedProps.comentario;

      } else if (permisos.consultarUsuario) {
        $('#verEventoUsuario').modal('show');
        document.getElementById('nombre3').textContent = info.event.title;
        document.getElementById('fechaInicio3').textContent = info.event.startStr;
        if (info.event.endStr == '') {
          document.getElementById('fechaCierre3').textContent = info.event.startStr;
        }else{
          document.getElementById('fechaCierre3').textContent = info.event.endStr;
        }
        document.getElementById('descripcion3').textContent = info.event.extendedProps.descripcion;

      }

      if (permisos.eliminar) {


        $('#eliminar').on('click', function () {

          Swal.fire({
            title: '¿Estas Seguro de Eliminar este Evento?',
            text: "Este evento se eliminara para todas las sedes que esten relacionadas",
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
                url: '/AppwebMVC/Agenda/Index',
                data: {

                  eliminar: 'eliminar',
                  id: info.event.id,
                },
                success: function (response) {


                  $('#editarEvento').modal('hide');


                  Swal.fire({
                    icon: 'success',
                    title: '¡Borrado!',
                    text: 'El evento a sido eliminado',
                    showConfirmButton: false,
                    timer: 2000,
                  })

                  calendar.refetchEvents();


                },
                error: function (jqXHR, textStatus, errorThrown) {
                  if (jqXHR.responseText) {
                    let jsonResponse = JSON.parse(jqXHR.responseText);

                    if (jsonResponse.msj) {
                      Swal.fire({
                        icon: 'error',
                        title: 'DENEGADO',
                        text: jsonResponse.msj,
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



      }

    },
  })




  function listar_sedes_evento(idEvento) {

    if (datatables) {
      datatables.destroy();
    }

    datatables = $('#sedesDatatables').DataTable({
      language: {
       url: '/AppwebMVC/public/lib/datatables/datatable-spanish.json'
      },
      searching: false,
      responsive: true,
      scrollCollapse: true,
    scrollY: '100px',
    info: false,
    ordering: false,
    paging: false,
      ajax: {
        method: "POST",
        url: '/AppwebMVC/Agenda/Index',
        data: {
          cargar_data_sedes: 'cargar_data_sedes',
          idEvento: idEvento
        }
      },
      columns: [
        { data: 'nombre' },

        {
          defaultContent: `

          <div class="d-flex justify-content-end gap-1" >
            <button type="button" id="verComentarioSede" class="btn btn-secondary" title="Eliminar"><i class="fa-solid fa-comment"></i></button>
            <button type="button" id="eliminarSede" class="btn btn-danger" title="Eliminar"><i class="fa-solid fa-trash" ></i></button>
            </div>
            `}
      ],
    })



  }







  $('#sedesDatatables tbody').on('click', '#verComentarioSede', function () {
    const datos = datatables.row($(this).parents()).data();

    Swal.fire({
      title: "Comentario de la sede",
      text: datos.comentario,
      showConfirmButton: true,
      confirmButtonColor: 'grey',
      confirmButtonText: 'Cerrar'
    });
  })

  $('#agregarSedes').on('click', function () {

    const idEvento = document.getElementById('idEvento').textContent;


    $.ajax({

      type: "POST",
      url: "/AppwebMVC/Agenda/Index",
      data: {

        actualizarSedes: 'actualizarSedes',
        arraySedes:  $("#sedes_sin_agregar").val(),
        idEvento: idEvento

      },

      success: function (response) {

        Swal.fire({
          icon: 'success',
          title: 'Sedes agregadas con exito',
          showConfirmButton: false,
          timer: 2000,
        })

        datatables.ajax.reload();
        calendar.refetchEvents();
        sedes_sin_agregar(idEvento);

      },
      error: function (jqXHR, textStatus, errorThrown) {
        if (jqXHR.responseText) {
          let jsonResponse = JSON.parse(jqXHR.responseText);

          if (jsonResponse.msj) {
            Swal.fire({
              icon: 'error',
              title: 'DENEGADO',
              text: jsonResponse.msj,
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

  });


  $('#sedesDatatables tbody').on('click', '#eliminarSede', function () {
    const datos = datatables.row($(this).parents()).data();
    const comentario = datos.comentario;

    if (comentario) {
      Swal.fire({
        title: 'Existe un comentario de la Sede en este evento',
        text: "¿Igual desea eliminar?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Si',
        confirmButtonColor: '#007bff',
        cancelButtonText: 'No',
        reverseButtons: true
      }).then((result) => {
        if (result.isConfirmed) {
          eliminar_sede_evento(datos.idEvento, datos.id)
        }
      });
    } else {
      eliminar_sede_evento(datos.idEvento, datos.id)
    }

  });

  function eliminar_sede_evento(idEvento, id) {


    $.ajax({
      type: "POST",
      url: '/AppwebMVC/Agenda/Index',
      data: {

        eliminarEventoSede: 'eliminarEventoSede',
        id: id

      },
      success: function (response) {

        calendar.refetchEvents();
        datatables.ajax.reload();
        sedes_sin_agregar(idEvento);


      },
      error: function (jqXHR, textStatus, errorThrown) {
        if (jqXHR.responseText) {
          let jsonResponse = JSON.parse(jqXHR.responseText);

          if (jsonResponse.msj) {
            Swal.fire({
              icon: 'error',
              title: 'DENEGADO',
              text: jsonResponse.msj,
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




  function Listar_Sedes() {

    $.ajax({
      type: "GET",
      url: "/AppwebMVC/Agenda/Index",
      data: {
        listaSedes: 'listaSedes',
      },
      success: function (response) {

        let data = JSON.parse(response);

        let selector = document.getElementById('sedes');

        // Destruir la instancia existente si la hay
        if (choices1) {
          choices1.destroy();
        }


        selector.innerHTML = '';

        data.forEach(item => {

          const option = document.createElement('option');
          option.value = item.id;
          option.text = `${item.codigo} ${item.nombre}`;
          selector.appendChild(option);

        });


        choices1 = new Choices(selector, {
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


  function sedes_sin_agregar(idEvento) {

    $.ajax({
      type: "POST",
      url: "/AppwebMVC/Agenda/Index",
      data: {
        sedes_sin_agregar: 'sedes_sin_agregar',
        idEvento: idEvento
      },
      success: function (response) {

        let data = JSON.parse(response);

        let selector = document.getElementById('sedes_sin_agregar');

        // Destruir la instancia existente si la hay
        if (choices_sinSedes) {
          choices_sinSedes.destroy();
        }


        selector.innerHTML = '';

        data.forEach(item => {

          const option = document.createElement('option');
          option.value = item.id;
          option.text = `${item.codigo} ${item.nombre}`;
          selector.appendChild(option);

        });


        choices_sinSedes = new Choices(selector, {
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




  //////////////////////// Registrar
  const expresionesRegulares = {

    titulo: /^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,]{3,100}$/,
    fechaInicial: /^\d{4}-\d{2}-\d{2}$/,
    fechaFinal: /^\d{4}-\d{2}-\d{2}$/,
    descripcion: /^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,]{5,100}$/

  };
  const validacion1 = {

    titulo: false,
    fechaInicio: false,
    fechaFinal: false,
    sedes: false,
    descripcion: false
  };

// Validacion de titulo
$("#titulo").on("keyup", function (event) {
  const titulo = document.getElementById("titulo").value
  $.ajax({
    type: "POST",
    url: "/AppwebMVC/Agenda/Index",
    data: {
      coincidencias: 'coincidencias',
      titulo: titulo
    },
    success: function (response) {

      let data = JSON.parse(response);

      if (data != true) {
        validacion1.titulo = true;
        $("#titulo").removeClass("is-invalid");
        $("#titulo").addClass("is-valid");
        document.getElementById('msj_titulo').textContent = '';
        if (expresionesRegulares.titulo.test(titulo)) {
          validacion1.titulo = true;
          $("#titulo").removeClass("is-invalid");
          $("#titulo").addClass("is-valid");
          document.getElementById('msj_titulo').textContent = '';

        } else {
          validacion1.titulo = false;
          $("#titulo").removeClass("is-valid");
          $("#titulo").addClass("is-invalid");
          document.getElementById('msj_titulo').textContent = 'El titulo es obligatorio y debe poseer mas de 3 caracteres';

        }
      } else {
        validacion1.titulo = false;
        $("#titulo").removeClass("is-valid");
        $("#titulo").addClass("is-invalid");
        document.getElementById('msj_titulo').textContent = 'Ya existe un evento con este titulo';

      }

    },
    error: function (jqXHR, textStatus, errorThrown) {
      // Aquí puedes manejar errores, por ejemplo:
      console.error("Error al enviar:", textStatus, errorThrown);
    }
  })


});

//Validacion de Fecha Inicio
$('#fechaInicio').on("change", function (event) {

  const fechaInicio = document.getElementById("fechaInicio").value

  if (expresionesRegulares.fechaInicial.test(fechaInicio)) {
    validacion1.fechaInicio = true;
    $("#fechaInicio").removeClass("is-invalid");
    $("#fechaInicio").addClass("is-valid");
    document.getElementById('msj_fechaInicio').textContent = '';
  } else {
    validacion1.fechaInicio = false;
    $("#fechaInicio").removeClass("is-valid");
    $("#fechaInicio").addClass("is-invalid")
    document.getElementById('msj_fechaInicio').textContent = 'Este campo es obligatorio';
  }

});

//Validacion de Fecha Final
$('#fechaFinal').on("change", function (event) {

  const fechaFinal = document.getElementById("fechaFinal").value

  if (expresionesRegulares.fechaFinal.test(fechaFinal)) {
    validacion1.fechaFinal = true;
    $("#fechaFinal").removeClass("is-invalid");
    $("#fechaFinal").addClass("is-valid");
    document.getElementById('msj_fechaFinal').textContent = '';
  } else {
    validacion1.fechaFinal = false;
    $("#fechaFinal").removeClass("is-valid");
    $("#fechaFinal").addClass("is-invalid");
    document.getElementById('msj_fechaFinal').textContent = 'Este campo es obligatorio';
  }

});


//Validacion de Sedes

sedes.addEventListener('change', (e) => {
  const sedes = document.querySelector("#sedes");

  let selectedValues;
  selectedValues = Array.from(sedes.selectedOptions).map(option => option.value);
  if (selectedValues.length === 0) {
    validacion1.sedes = false;
    document.getElementById("msj_sedes").classList.remove("d-none");
    document.getElementById('msj_sedes').textContent = 'Debe escoger al menos una Sede';
  } else {
    validacion1.sedes = true;
    document.getElementById("msj_sedes").classList.add("d-none");
    document.getElementById('msj_sedes').textContent = '';
  }

})




//Validacion de Descripcion
$('#descripcion').on("keyup", function (event) {

  const descripcion = document.getElementById("descripcion").value

  if (expresionesRegulares.descripcion.test(descripcion)) {
    validacion1.descripcion = true;
    $("#descripcion").removeClass("is-invalid");
    $("#descripcion").addClass("is-valid");
    document.getElementById('msj_descripcion').textContent = '';
  } else {
    validacion1.descripcion = false;
    $("#descripcion").removeClass("is-valid");
    $("#descripcion").addClass("is-invalid")
    document.getElementById('msj_descripcion').textContent = 'Este campo es obligatorio y debe poseer mas de 4 caracteres';
  }

});



const form = document.getElementById("formulario1");

form.addEventListener("submit", (e) => {
  e.preventDefault();

  if (Object.values(validacion1).every(status => status === true)) {

    const datos1 = {
      registroEventos: 'registroEventos',
      titulo: document.getElementById("titulo").value,
      fechaInicio: document.getElementById("fechaInicio").value,
      fechaFinal: document.getElementById("fechaFinal").value,
      descripcion: document.getElementById("descripcion").value,
      sedes: $('#sedes').val()
    };


    $.ajax({

      type: "POST",
      url: "/AppwebMVC/Agenda/Index",
      data: datos1,
      success: function (response) {
        console.log(response);
        let data = JSON.parse(response);


        // Aquí puedes manejar una respuesta exitosa, por ejemplo:
        console.log("Respuesta del servidor:", data);
        Swal.fire({
          icon: 'success',
          title: data.msj,
          showConfirmButton: false,
          timer: 2000,
        })
        calendar.refetchEvents();
        document.getElementById('formulario1').reset();
        $('#agregar').modal('hide');
        for (const key in validacion1) {
          validacion1[key] = false;
        }
        $("#titulo").removeClass("is-valid");
        $("#fechaInicio").removeClass("is-valid");
        $("#fechaFinal").removeClass("is-valid");
        $("#descripcion").removeClass("is-valid");
      },
      error: function (jqXHR, textStatus, errorThrown) {
        if (jqXHR.responseText) {
          let jsonResponse = JSON.parse(jqXHR.responseText);

          if (jsonResponse.msj) {
            Swal.fire({
              icon: 'error',
              title: 'DENEGADO',
              text: jsonResponse.msj,
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
      title: 'Formulario invalido. Verifique sus datos',
      showConfirmButton: false,
      timer: 2000,
    });
  }
});


 

// // // // // Editar Evento

const validacionEditar = {

  titulo: true,
  fechaInicio: true,
  fechaFinal: true,
  descripcion: true
};



 // Validacion de tituloEditar
 $("#tituloEditar").on("keyup", function (event) {
  const tituloEditar = document.getElementById("tituloEditar").value
  $.ajax({
    type: "POST",
    url: "/AppwebMVC/Agenda/Index",
    data: {
      coincidencias: 'coincidencias',
      titulo: tituloEditar,
      id : document.getElementById('idEvento').textContent,

    },
    success: function (response) {

      let data = JSON.parse(response);

      if (data != true) {
        validacionEditar.titulo = true;
        $("#tituloEditar").removeClass("is-invalid");
        $("#tituloEditar").addClass("is-valid");
        document.getElementById('msj_tituloEditar').textContent = '';
        if (expresionesRegulares.titulo.test(tituloEditar)) {
          validacionEditar.titulo = true;
          $("#tituloEditar").removeClass("is-invalid");
          $("#tituloEditar").addClass("is-valid");
          document.getElementById('msj_tituloEditar').textContent = '';

        } else {
          validacionEditar.titulo = false;
          $("#tituloEditar").removeClass("is-valid");
          $("#tituloEditar").addClass("is-invalid");
          document.getElementById('msj_tituloEditar').textContent = 'El titulo es obligatorio y debe poseer mas de 3 caracteres';

        }
      } else {
        validacionEditar.titulo = false;
        $("#tituloEditar").removeClass("is-valid");
        $("#tituloEditar").addClass("is-invalid");
        document.getElementById('msj_tituloEditar').textContent = 'Ya existe un evento con este titulo';

      }

    },
    error: function (jqXHR, textStatus, errorThrown) {
      // Aquí puedes manejar errores, por ejemplo:
      console.error("Error al enviar:", textStatus, errorThrown);
    }
  })


});

//Validacion de Fecha InicioEditar
$('#fechaInicioEditar').on("change", function (event) {

  const fechaInicioEditar = document.getElementById("fechaInicioEditar").value

  if (expresionesRegulares.fechaInicial.test(fechaInicioEditar)) {
    validacionEditar.fechaInicio = true;
    $("#fechaInicioEditar").removeClass("is-invalid");
    $("#fechaInicioEditar").addClass("is-valid");
    document.getElementById('msj_fechaInicio').textContent = '';
  } else {
    validacionEditar.fechaInicio = false;
    $("#fechaInicioEditar").removeClass("is-valid");
    $("#fechaInicioEditar").addClass("is-invalid")
    document.getElementById('msj_fechaInicioEditar').textContent = 'Este campo es obligatorio';
  }

});

//Validacion de Fecha FinalEditar
$('#fechaFinalEditar').on("change", function (event) {

  const fechaFinalEditar = document.getElementById("fechaFinalEditar").value

  if (expresionesRegulares.fechaFinal.test(fechaFinalEditar)) {
    validacionEditar.fechaFinal = true;
    $("#fechaFinalEditar").removeClass("is-invalid");
    $("#fechaFinalEditar").addClass("is-valid");
    document.getElementById('msj_fechaFinalEditar').textContent = '';
  } else {
    validacionEditar.fechaFinal = false;
    $("#fechaFinalEditar").removeClass("is-valid");
    $("#fechaFinalEditar").addClass("is-invalid");
    document.getElementById('msj_fechaFinalEditar').textContent = 'Este campo es obligatorio';
  }

});







//Validacion de Descripcion Ediatr
$('#descripcionEditar').on("keyup", function (event) {

  const descripcionEditar = document.getElementById("descripcionEditar").value

  if (expresionesRegulares.descripcion.test(descripcionEditar)) {
    validacionEditar.descripcion = true;
    $("#descripcionEditar").removeClass("is-invalid");
    $("#descripcionEditar").addClass("is-valid");
    document.getElementById('msj_descripcionEditar').textContent = '';
  } else {
    validacionEditar.descripcion = false;
    $("#descripcionEditar").removeClass("is-valid");
    $("#descripcionEditar").addClass("is-invalid")
    document.getElementById('msj_descripcionEditar').textContent = 'Este campo es obligatorio y debe poseer mas de 4 caracteres';
  }

});







const form2 = document.getElementById("editarFormulario");

form2.addEventListener("submit", (e) => {
  e.preventDefault();

  if (Object.values(validacionEditar).every(status => status === true)) {

    const datos2 = {
      editarEvento: 'editarEvento',
      titulo: document.getElementById("tituloEditar").value,
      idEvento: document.getElementById('idEvento').textContent,
      fechaInicio: document.getElementById("fechaInicioEditar").value,
      fechaFinal: document.getElementById("fechaFinalEditar").value,
      descripcion: document.getElementById("descripcionEditar").value
    };


    $.ajax({

      type: "POST",
      url: "/AppwebMVC/Agenda/Index",
      data: datos2,
      success: function (response) {
        console.log(response);
        let data = JSON.parse(response);


        // Aquí puedes manejar una respuesta exitosa, por ejemplo:
        console.log("Respuesta del servidor:", data);
        Swal.fire({
          icon: 'success',
          title: data.msj,
          showConfirmButton: false,
          timer: 2000,
        })
        calendar.refetchEvents();
  
        $("#tituloEditar").removeClass("is-valid");
        $("#fechaInicioEditar").removeClass("is-valid");
        $("#fechaFinalEditar").removeClass("is-valid");
        $("#descripcionEditar").removeClass("is-valid");
      },
      error: function (jqXHR, textStatus, errorThrown) {
        if (jqXHR.responseText) {
          let jsonResponse = JSON.parse(jqXHR.responseText);

          if (jsonResponse.msj) {
            Swal.fire({
              icon: 'error',
              title: 'DENEGADO',
              text: jsonResponse.msj,
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
      title: 'Formulario invalido. Verifique sus datos',
      showConfirmButton: false,
      timer: 2000,
    });
  }
});


// // // // // // // // Actualizar Comentario
const form3 = document.getElementById("formularioPastor");

form3.addEventListener("submit", (e) => {
  e.preventDefault();
const comentario = document.getElementById("comentarioPastor").value;

  if (expresionesRegulares.descripcion.test(comentario)) {

 
    const id = document.getElementById('idEvento2').textContent;
  


  $.ajax({

    type: "POST",
    url: "/AppwebMVC/Agenda/Index",
    data:{
     actualizarComentario: 'actualizarComentario',
      comentario: comentario,
      id: id
      },
      success: function (response) {
        console.log(response);
        let data = JSON.parse(response);


        // Aquí puedes manejar una respuesta exitosa, por ejemplo:
        console.log("Respuesta del servidor:", data);
        Swal.fire({
          icon: 'success',
          title: data.msj,
          showConfirmButton: false,
          timer: 2000,
        })
        calendar.refetchEvents();
  
        $("#tituloEditar").removeClass("is-valid");
        $("#fechaInicioEditar").removeClass("is-valid");
        $("#fechaFinalEditar").removeClass("is-valid");
        $("#descripcionEditar").removeClass("is-valid");
      },
      error: function (jqXHR, textStatus, errorThrown) {
        if (jqXHR.responseText) {
          let jsonResponse = JSON.parse(jqXHR.responseText);
          console.log(jsonResponse);

          if (jsonResponse.msj) {
            Swal.fire({
              icon: 'error',
              title: 'DENEGADO',
              text: jsonResponse.msj,
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
      title: 'El comentario debe tener mas de cuatro caracteres',
      showConfirmButton: false,
      timer: 2000,
    });
  }
});


  calendar.render();
});
