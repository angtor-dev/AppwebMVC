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
    


});