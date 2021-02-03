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

	// $.get(CFG.url + 'admin/all_gestion_alumno', function(data) {
	//     var plan = $.parseJSON(data);
	//     // $('#all_gestion').append('<option value="'+val.id_gestion+'">'+val.gestion+'</option>');
	//     $.each(plan, function(index, val) {
	//       $('#all_gestion').append('<option value="'+val.id_gestionperiodo+'">'+val.gestion+'</option>');
	//     });
	//   });

	table = $('.table').DataTable({ 
		"searching": true,
		"destroy": true,
	    "processing": true, //Feature control the processing indicator.
	    "serverSide": true, //Feature control DataTables' server-side processing mode.
	    "order": [], //Initial no order.

	    // Load data for the table's content from an Ajax source
	    "ajax": {
	        // "url": "<?php echo site_url('Csv_import/load_datauno')?>",
	        'url': CFG.url+"admin/get_students",
	        "type": "POST"
	    },

	    //Set column definition initialisation properties.
	    "columnDefs": [{ 
		        "targets": [ -1 ], //last column
		        "orderable": false, //set not orderable
		}],
		"language": {
            "info" : "Mostrar _START_ al _END_ de _TOTAL_ alumnos",
            "loadingRecords":  "No hay alumnos...",
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


function boleta(id_alumno) {
	$.ajax({
		url : CFG.url + 'admin/boletin_alumno',
		type: 'POST',
		dataType: 'JSON',
		data: {id_alumno: id_alumno},
	})
	.done(function(data) {
		// $('#opciones').html(data);
		// var tr = $.parseJSON(data);
		alert(data.data);
	})
	.fail(function() {
		console.log("Error al obtener los datos.");
	});
}




// seleccion de año para el boletin
function confirmar_boletin(id){
  // alert (id);
  // ----------------------- limipia la gestion
  const $select = $("#all_gestion");
  $select.empty();
  // -----------------------
  $('#id_persona').val(id);
  $('#all_gestion').val(all_gestion);
  $('#modal_confirmar').modal('show'); // show bootstrap modal when complete loaded
  $("#all_gestion").val($("#all_gestion option:first").val()); //selecciona el primer elemento

  $.get(CFG.url + 'admin/all_gestion_alumno/'+id, function(data) {
    var plan = $.parseJSON(data);
    // $('#all_gestion').append('<option value="'+val.id_gestion+'">'+val.gestion+'</option>');
    $.each(plan, function(index, val) {
      $('#all_gestion').append('<option value="'+val.id_gestionperiodo+'">'+val.gestion+'</option>');
    });
  });
}

function guardar() {
	var id = $('#id_persona').val();
	var id_gp = $('#all_gestion').val();
	$('#modal_confirmar').modal('hide');
	window.open(CFG.url+"admin/boletin_pdf/"+id+"/"+id_gp);
}