var save_method;
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

  $.get(CFG.url + 'centralizador/get_plan', function(data) {
    var plan = $.parseJSON(data);
    $.each(plan, function(index, val) {
      $('#plan').append('<option value="'+val.id_plan+'">'+val.nombre+'</option>');
    });
  });


});

$('#carreras').change(function(e) {
  var id_carrera = $('#carreras').val();
  $('#paralelos').empty();
  $.ajax({
    url: CFG.url + 'centralizador/get_paralelos_by_carrera',
    type: "POST",
    cache: true,
    data: {id_carrera: id_carrera, csrf_test_name: CFG.token,},
    success: function(data) {
      var paralelos = $.parseJSON(data);
      if (paralelos != 'vacio') {
        var paralelos = $.parseJSON(data);
        paralelos.forEach( function(paralelo, index) {
          $.each(paralelo, function(index, val) {
            $('#paralelos').append('<option value="'+val.id_paralelo+'">' + val.nombre + '</option>');
          });
        });
      } else {
        alert('La carrera no tiene paralelos. Elija otra carrera.');
      }
    },
    error: function (jqXHR, textStatus, errorThrown)
    {
        alert('Error al obtener datos.');
    }
  });
});

$("body").on("submit", "form#form_centralizador", function(ev){
  // console.log('Orlando');
  ev.preventDefault();
  var formData = new FormData($('#form_centralizador')[0]);
  // formData.append('csrf_test_name', CFG.token);
  $.ajax({
    url: CFG.url + 'admin/get_alumnos_efectivos',
    type: "POST",
    dataType: 'JSON',
    // data: $('#form_centralizador').serialize(),
    data: formData,
    contentType: false,
    processData: false,
    success: function(data) {
      if (data.status) {
        $('.students').html(data.cent);
        $(".verticalTableHeader").each(function(){$(this).height($(this).width())})
      } else {
        $('.students').html('');
        alert('No hay datos a mostrar.');
      }
      // function_name();
    },
    error: function (jqXHR, textStatus, errorThrown)
    {
      $('.students').html('');
      alert('No hay datos a mostrar.');
    }
  });
});