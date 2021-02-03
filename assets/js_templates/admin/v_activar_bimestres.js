$(document).ready(function(){
  // token begin
  $.ajaxSetup({data: {csrf_test_name: CFG.token}});
    $(document).ajaxSuccess(function(e,x) {
        var result = $.parseJSON(x.responseText);
        $('input:hidden[name="csrf_test_name"]').val(CFG.token);
        $.ajaxSetup({data: {csrf_test_name: result.token}});
    });
  // token end

  $.get(CFG.url +'admin/list_bimestres', function(data) {
    var tr = $.parseJSON(data);
    $('#active_bimestres').html(tr.lib);
  });


  table = $('.list_by_teacher_object').DataTable({ 
    "searching": true,
    "destroy": true,
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "order": [], //Initial no order.

      // Load data for the table's content from an Ajax source
      "ajax": {
          'url': CFG.url+"admin/list_teacher_subjects_active_bimestre",
          "type": "POST"
      },

      //Set column definition initialisation properties.
      "columnDefs": [{ 
            "targets": [ -1, -2 ], //last column
            "orderable": false, //set not orderable
    }],
    "language": {
            "info" : "Mostrar _START_ al _END_ de _TOTAL_ docentes asignados",
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
      },
      "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]]
  });





});

function hide(id_activebim) {
  // alert(id_activebim+100);
  $.post(CFG.url + 'admin/hide_bimestre',
    {
      id_activebim: id_activebim,
      // id_materia: id_materia,
    },
    function(data, textStatus, xhr) {
      // var paralelos = $.parseJSON(data);
      console.log('asdfa');
    }
  );
}

function show(id_activebim) {
  // alert(id_activebim);
  $.post(CFG.url + 'admin/show_bimestre',
    {
      id_activebim: id_activebim,
      // id_materia: id_materia,
    },
    function(data, textStatus, xhr) {
      // var paralelos = $.parseJSON(data);
      console.log('asdfa');
    }
  );
}

function reload_table(){
    table.ajax.reload(null,false); //reload datatable ajax 
}

function edit_activebimestre(id)
{
  $('#id_materia').val(id);
  $('#modal_confirmar').modal('show'); // show bootstrap modal when complete loaded
    $.ajax({
    url : CFG.url + 'Admin/list_activebimestre/',
    type: 'POST',
    dataType: 'JSON',
    data: {id_materia: $('#id_materia').val()},
  })
  .done(function(data) {
    // console.log("Se guardo con éxito");
    $('#opciones').html(data);
  })
  .fail(function() {
    console.log("Error al obtener los menús.");
  });
}

function save_activebimestre()
{
  $.ajax({
    url: CFG.url +"admin/add_activebimestre",
    type: "POST",
    cache: false,
    dataType: 'JSON',
    data: new FormData($('#form_parallel')[0]),
    contentType: false,
    processData: false,
        success: function(data)
        {
            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal_confirmar').modal('hide');
                reload_table();
            }
            else
            {
              $('#modal_confirmar').modal('hide');
              reload_table();
              alert('No se puede activar/desactivar los bimestres.')
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error al guardar bimestres activos.');
        }
    });
}