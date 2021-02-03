var save_method; //for save method string
var table;

$(document).ready(function(){
  // token begin
  $.ajaxSetup({data: {csrf_test_name: CFG.token}});
    $(document).ajaxSuccess(function(e,x) {
        var result = $.parseJSON(x.responseText);
        $('input:hidden[name="csrf_test_name"]').val(CFG.token);
        $.ajaxSetup({data: {csrf_test_name: result.token}});
    });
  // token end

  table = $('.table').DataTable({ 
    "searching": true,
    "destroy": true,
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "order": [], //Initial no order.

      // Load data for the table's content from an Ajax source
      "ajax": {
          // "url": "<?php echo site_url('Csv_import/load_datauno')?>",
          'url': CFG.url+"admin/list_uploadnotes",
          "type": "POST"
      },

      //Set column definition initialisation properties.
      "columnDefs": [{ 
            "targets": [ -1 ], //last column
            "orderable": false, //set not orderable
    }],
    "language": {
            "info" : "Mostrar _START_ al _END_ de _TOTAL_ docentes",
            "loadingRecords":  "No hay docentes...",
            "processing" :     "Procesando...",
            "search" :      "Buscar:    ",
            "zeroRecords": "No se encontraron resultados",
            "infoEmpty":  "No hay registros",
            "lengthMenu":  "Mostrar _MENU_ registros",
            "infoFiltered":   "(Filtrado de _MAX_ registros en total)",
            "paginate": {
            "first":      "Primero",
            "last":       "Último",
            "next":       "Siguiente",
            "previous":   "Anterior"
        },
        "aria": {
            "sortAscending":  ": activate to sort column ascending",
            "sortDescending": ": activate to sort column descending"
        },
        "lengthMenu": 'Mostrar <select>'+
        '<option value="10">10</option>'+
        '<option value="25">25</option>'+
        '<option value="50">50</option>'+
        '<option value="100">100</option>'+
        '<option value="-1">All</option>'+
        '</select> Registros'
        },
    });




});

