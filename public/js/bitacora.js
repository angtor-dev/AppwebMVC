$(document).ready(function () {



    const dataTable = $('#tabla-bitacora').DataTable({
   
        info: false,
            lengthChange: false,
            pageLength: 15,
            dom: 'ltipB',
            ordering: false,
            searching: true,
            language: {
                url: '/AppwebMVC/public/lib/datatables/datatable-spanish.json'
            },
            // Muestra paginacion solo si hay mas de una pagina
            drawCallback: function (settings) {
                var pagination = $(this).closest('.dataTables_wrapper').find('.dataTables_paginate');
                pagination.toggle(this.api().page.info().pages > 1);
            },
        ajax: {
          method: "GET",
          url: '/AppwebMVC/Seguridad/Bitacora/Index',
          data: { cargar_data: 'cargar_data' }
        },
        columns: [
          { data: 'usuarioDatos' },
          { data: 'registro' },
          { data: 'ruta' },
          {data: 'fecha' },
        ],
    
        
      });
    
     
        $('#search').keyup(function () {
            dataTable.search($(this).val()).draw();
        });
    
        $('#bd').on('click', async (e) => {
          Swal.fire({
              title: '¿Estás seguro?',
              text: 'Se realizará un backup de la base de datos.',
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText:   
       '¡Sí, realizar backup!'
          }).then((result) => {
              if (result.isConfirmed) {
                  $.ajax({
                      type: "POST",
                      url: '/AppwebMVC/Seguridad/Bitacora/Index',
                      data: {
                          getbd: 'getbd'
                      },
                      success: (response) => {
                          Swal.fire(
                              '¡Realizado!',
                              'El backup se ha generado correctamente.',
                              'success'
                          );
                      },
                      error: (jqXHR, textStatus, errorThrown) => {
                          // Manejo de errores más detallado
                          Swal.fire({
                              icon: 'error',
                              title: 'Oops...',
                              text: 'Algo salió mal: ' + errorThrown
                          });
                      }
                  });
              }
          });
      });
        
          
      });
   
   
    
