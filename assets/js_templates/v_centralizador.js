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

  $.get(CFG.url + 'centralizador/get_carreras', function(data) {
    var carrera = $.parseJSON(data);
    $.each(carrera, function(index, val) {
      $('#carreras').append('<option value="'+val.id_carrera+'">'+val.nombre_carrera+'</option>');
    });
  });

  $.get(CFG.url + 'centralizador/get_plan', function(data) {
    var plan = $.parseJSON(data);
    $.each(plan, function(index, val) {
      $('#plan').append('<option value="'+val.id_plan+'">'+val.nombre+'</option>');
    });
  });

  $.get(CFG.url + 'centralizador/get_year', function(data) {
    var plan = $.parseJSON(data);
    $.each(plan, function(index, val) {
      $('#year').append('<option value="'+val.id_gestionperiodo+'">'+val.gestion+'</option>');
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
    url: CFG.url + 'centralizador/get_student_with_notes_and_carreras',
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
        // alert('Error al obtener los datos');
    }
  });
});


function pdf_cent(){
  var id_carrera = $('#carreras').val();
  var id_paralelo = $('#paralelos').val();
  var bimestre = $('#bimestre').val();
  var id_plan = $('#plan').val();
  var id_year = $('#year').val();
  window.open(CFG.url + 'centralizador/pdf_centralizador/'+id_carrera+'/'+id_paralelo+'/'+bimestre+'/'+id_plan+'/'+id_year,'_blank'); 
};
// window.onload = function () {

// function function_name() {
//   var options = {
//       title: {
//           text: "Desktop OS Market Share in 2017"
//       },
//       subtitles: [{
//           text: "As of November, 2017"
//       }],
//       animationEnabled: true,
//       data: [{
//           type: "pie",
//           startAngle: 40,
//           toolTipContent: "<b>{label}</b>: {y}%",
//           showInLegend: "true",
//           legendText: "{label}",
//           indexLabelFontSize: 16,
//           indexLabel: "{label} - {y}%",
//           dataPoints: [
//               { y: 48.36, label: "Windows 7" },
//               { y: 26.85, label: "Windows 10" },
//               { y: 1.49, label: "Windows 8" },
//               { y: 6.98, label: "Windows XP" },
//               { y: 6.53, label: "Windows 8.1" },
//               { y: 2.45, label: "Linux" },
//               { y: 3.32, label: "Mac OS X 10.12" },
//               { y: 4.03, label: "Others" }
//           ]
//       }]
//   };
//   $("#chartContainer").CanvasJSChart(options);
// }