
var save_method; //for save method string
var table;
$(document).ready(function(){
  // token begin
  $.ajaxSetup({data: {token: CFG.token}});
  $(document).ajaxSuccess(function(e,x) {
    var result = $.parseJSON(x.responseText);
    $('input:hidden[name="token"]').val(result.token);
    $.ajaxSetup({data: {token: result.token}});
  });
  // token end

  table = $('.table').DataTable({ 
    "processing": true, //Feature control the processing indicator.
    "serverSide": true, //Feature control DataTables' server-side processing mode.
    "order": [], //Initial no order.
    "lengthChange": false,
    // Load data for the table's content from an Ajax source
    "ajax": {
      "url": CFG.url+"alumno/ajax_list",
      "type": "POST"
    },
    success: function(data){
      console.log(data);
    },
    //Set column definition initialisation properties.
    "columnDefs": [{ 
      "targets": [ -1 ], //last column
      "orderable": false, //set not orderable
    }],
  });


});

function edit_alumno(id) {    
  save_method = 'update';
  // $('#form_alumno')[0].reset(); // reset form on modals
  cargarselectmodal();
  $('.form-group').removeClass('has-error'); // clear error class
  $('.help-block').empty(); // clear error string
  $.ajax({
    url : CFG.url+"alumno/ajax_edit/" + id,
    type: "GET",
    dataType: "JSON",
    success: function(data){
      $('[name="id_alumno"]').val(data.id_alumno);
      $('[name="id_carrera"]').val(data.id_carrera);
      $('[name="id_tutor"]').val(data.id_tutor);
      $('[name="id_persona"]').val(data.id_persona);
      $('[name="nombres"]').val(data.nombres);
      $('[name="apellido_paterno"]').val(data.apellido_paterno);
      $('[name="apellido_materno"]').val(data.apellido_materno);
      $('[name="ci"]').val(data.ci);
      $('[name="expedido"]').val(data.expedido);
      $('[name="estado_civil"]').val(data.estado_civil);
      $('[name="carrera"]').val(data.id_carrera);
      $('[name="email"]').val(data.email);
      $('[name="sexo"]').val(data.sexo);
      $('[name="fecha_nacimiento"]').val(data.fecha_nacimiento);
      $('[name="direccion"]').val(data.direccion);
      $('[name="celular"]').val(data.celular);
      $('[name="telefono_fijo"]').val(data.telefono_fijo);
      $('[name="lugar_trabajo"]').val(data.lugar_trabajo);
      $('[name="direccion_trabajo"]').val(data.direccion_trabajo);
      $('[name="telefono_trabajo"]').val(data.telefono_trabajo);
      $('[name="nombre_padre"]').val(data.nombre_padre);
      $('[name="ocupacion_padre"]').val(data.ocupacion_padre);
      $('[name="celular_padre"]').val(data.celular_padre);
      $('[name="nombre_madre"]').val(data.nombre_madre);
      $('[name="ocupacion_madre"]').val(data.ocupacion_madre);
      $('[name="celular_madre"]').val(data.celular_madre);
      $('[name="fecha_preinscripcion"]').val(data.fecha_preinscripcion);
      $('[name="colegio_proviene"]').val(data.colegio_proviene);
      $('#modal-default').modal('show'); // show bootstrap modal when complete loaded
      $('.modal-title').text('Editar persona'); // Set title to Bootstrap modal title
    },
    error: function (jqXHR, textStatus, errorThrown){
      alert('Error get data from ajax');
    }
  });
}

function save(){
  $('#btnSave').text('saving...'); //change button text
  $('#btnSave').attr('disabled',true); //set button disable 

  // ajax adding data to database
  $.ajax({
    url : CFG.url+"alumno/ajax_update",
    type: "POST",
    data: $('#form_alumno').serialize(),
    dataType: "JSON",
    success: function(data){
      if(data.status) //if success close modal and reload ajax table
      {
        $('#modal-default').modal('hide');
        reload_table();
      } else {
        for (var i = 0; i < data.inputerror.length; i++) {
          $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
          $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
        }
      }
      $('#btnSave').text('guardar'); //change button text
      $('#btnSave').attr('disabled',false); //set button enable 
    },
    error: function (jqXHR, textStatus, errorThrown)
    {
      alert('Error adding / update data');
      $('#btnSave').text('guardar'); //change button text
      $('#btnSave').attr('disabled',false); //set button enable 
    }
  });
}

function cargarselectmodal() {
  $.get(CFG.url+'alumno/get_carrera', function(data) {
    var tr = $.parseJSON(data);
    $.each(tr, function(index, val) {
      $('#carrera').append('<option value="'+val.id_carrera+'">'+val.nombre_carrera+'</option>');
    });
  });
}

function reload_table() {
  table.ajax.reload(null,false); //reload datatable ajax 
}

// <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>