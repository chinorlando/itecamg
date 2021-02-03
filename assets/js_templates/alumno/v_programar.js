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

	$.post(CFG.url + 'Ajax/foo/', function(data) {
	    // console.log(data)
	}, 'json');


	


	

	$.get(CFG.url +'alumno/estado_programacion_alumno/'+$('.id_alumno').val(), function(data) {
		var d = $.parseJSON(data);
		if (d.status) {
			// console.log(d.status);
			update_programation();
			$('#btnSaveProgramacion').text('Reprogramar'); //change button text
		} else {
			$.get(CFG.url +'alumno/materias_a_programar/'+$('.id_alumno').val(), function(data) {
				var tr = $.parseJSON(data);
				$('#programarme_tbody').html(tr.mapro);
			});
		}
	});

	$("#check-all-programarse").click(function () {
		$(".asignaturas").prop('checked', $(this).prop('checked'));
	});

	$("#check-all-confirmar").click(function () {
		$(".confirm").prop('checked', $(this).prop('checked'));
	});

	


	







	// table = $('#table').DataTable({
	// 	"paging": false,
	// 	"searching": false,
	// 	"destroy": true,
	// 	"ordering": false,
	// 	"processing": false,
	// 	"serverSide": false,
	// 	"buttons": [],
	// 	// "columns": columns,
	// });


//////////-------------------------------------------------------
	// $('#table tbody').on( 'click', 'td', function () {

	// 	var rowIndex = $(this).parent().index('#table tbody tr');
 //      	var tdIndex = $(this).index('#table tbody tr:eq('+rowIndex+') td');

	// 	console.log($('#table tbody tr:eq('+rowIndex+') td:eq('+tdIndex+')').text());
	// 	console.log('fila: '+rowIndex);
	// 	console.log('columna: '+tdIndex);

	// 	$("<tr>").attr("disabled",true); 

	// } );
//////////-------------------------------------------------------
	

	// $('#table tbody').on('click', 'td', function () {
	// 	var visIdx = $(this).index();
	// 	console.log('columna: '+visIdx);
	// });

	// $('#table tbody').on('click', 'tr', function () {
	// 	var rowIndex = $('#table tbody tr').index(this);
	// 	console.log('Fila: '+rowIndex);
	// });


	$('#table tbody').on( 'click', 'td', function () {

		var rowIndex = $(this).parent().index('#table tbody tr');
      	var tdIndex = $(this).index('#table tbody tr:eq('+rowIndex+') td');

		console.log($('#table tbody tr:eq('+rowIndex+') td:eq('+tdIndex+')').text());
		console.log('fila: '+rowIndex);
		console.log('columna: '+tdIndex);

		$("<tr>").attr("disabled",true); 

	} );

    $("#table").on('change', 'tr td',function(evt){
    	// elememto = this;
    	// console.log(elememto.next());
    	eso = $(this).parent();
    	console.log(eso);
  	});




});

function update_programation() {
	// alert('Usted ya esta programado');
	$.ajax({
	    url : CFG.url + 'alumno/a_edit_programation_admin_modificado/'+$('.id_alumno').val(),
	    type: "GET",
	    dataType: "JSON",
	    success: function(data)
	    {
	        // console.log(data);
	        // var tr = $.parseJSON(data);
			$('#programarme_tbody').html(data.mapro);

			// $.get(CFG.url +'alumno/materias_a_reprogramar/'+$('.id_alumno').val(), function(data) {
			// 	var tr = $.parseJSON(data);
			// 	console.log(tr);
			// 	$('#programarme_tbody').html(tr.mapro);

				var cantsr = data.rep_materia.length;
				// console.log(cantsr);
				$("input#asignaturas").each(function(){
					for (var i = 0; i < cantsr; i++) {
						if (parseInt($(this).val()) == data.rep_materia[i].id_materia) {
							$(this).prop('checked',true);
						}
					}
				});
				$("input#confirmar").each(function(){
					for (var i = 0; i < cantsr; i++) {
						if (parseInt($(this).val()) == data.rep_materia[i].id_materia) {
							$(this).prop('checked',true);
						}
					}
				});


				// $.get(CFG.url + 'alumno/get_paralelo/'+$('.id_alumno').val(), function(data_paralelo) {
				// 	var tr = $.parseJSON(data_paralelo);
				// 	// console.log(tr.paralelo.length);
				// 	$("select#paralelo option").each(function(){
				// 		for (var i = 0; i < tr.paralelo.length; i++) {
				// 			var eso = $(this).val();
				// 			// console.log(eso);
				// 			if (parseInt($(this).val()) == tr.paralelo[i].id_paralelo) {
				// 				$(this).val(tr.paralelo[i].nombre);
				// 				$(this).attr("selected","selected");
				// 				// console.log(eso);
				// 			} else {
				// 				$(this).val(-1);
				// 			}
				// 		}
				// 	});
				// });

			// });
	  //       $('#btnSaveProgramacion').text('Reprogramarme'); //change button text
	    },
	    error: function (jqXHR, textStatus, errorThrown)
	    {
	        alert('Error al obtener datos');
	    }
	});

}

// sin tocar
// function update_programation() {
// 	// alert('Usted ya esta programado');
// 	$.ajax({
// 	    url : CFG.url + 'alumno/a_edit_programation_admin/'+$('.id_alumno').val(),
// 	    type: "GET",
// 	    dataType: "JSON",
// 	    success: function(all_data)
// 	    {
// 	        // $('#paralelo').val(all_data[0].id_paralelo);

// 			$.get(CFG.url +'alumno/materias_a_reprogramar/'+$('.id_alumno').val(), function(data) {
// 				var tr = $.parseJSON(data);
// 				console.log(tr);
// 				$('#programarme_tbody').html(tr.mapro);

// 				var cantsr = all_data.length;
// 				$("input#asignaturas").each(function(){
// 					for (var i = 0; i < cantsr; i++) {
// 						if (parseInt($(this).val()) == all_data[i].id_materia) {
// 							$(this).prop('checked',true);
// 						}
// 					}
// 				});
// 				$("input#confirmar").each(function(){
// 					for (var i = 0; i < cantsr; i++) {
// 						if (parseInt($(this).val()) == all_data[i].id_materia) {
// 							$(this).prop('checked',true);
// 						}
// 					}
// 				});


// 				// $.get(CFG.url + 'alumno/get_paralelo', function(data_paralelo) {
// 				// 	var tr = $.parseJSON(data_paralelo);
// 				// 	// console.log(tr);
// 				// 	$("select#paralelo").each(function(){
// 				// 		for (var i = 0; i < tr.paralelo.length; i++) {
// 				// 			var eso = $('#paralelo').val();
// 				// 			if (parseInt($('#paralelo').val()) == tr.paralelo[i].id_paralelo) {
// 				// 				// $('#paralelo').val(tr.paralelo[i].nombre);
// 				// 				// $('#paralelo').val(2);
// 				// 				console.log(eso);
// 				// 			} else {
// 				// 				$('#paralelo').val(-1);
// 				// 			}
// 				// 		}
// 				// 	});
// 				// });
// 			});
// 	        $('#btnSaveProgramacion').text('Reprogramarme'); //change button text
// 	    },
// 	    error: function (jqXHR, textStatus, errorThrown)
// 	    {
// 	        alert('Error al obtener datos');
// 	    }
// 	});

// }


$("body").on("submit", "#form_programacion", function(e){
	e.preventDefault();
	var url;
	// console.log($('#btnSaveProgramacion').text());
	if($('#btnSaveProgramacion').text().trim() == 'Enviar') {
		url = CFG.url + 'alumno/ajax_add_programacion_admin/'+$('.id_alumno').val();
 	} else {
		url = CFG.url + 'alumno/ajax_update_programacion_admin/'+$('.id_alumno').val();
	}

	// if (!$('#paralelo').prop("selected")) {
	// 	alert('debes seleccionar el paralelo.');
	// }

    var formData = new FormData($('#form_programacion')[0]);

	var selected = [];
	$(":checkbox[id=asignaturas]").each(function() {
		if (this.checked) {
			selected.push($(this).val());
		}
	});
	
	if (!selected.length) {
		alert('Debes seleccionar por lo menos una materia para programar.');
	} 
	else {
		$.ajax({
			url: url,
			type: "POST",
			cache: true,
			dataType: 'JSON',
			data: formData,
			contentType: false,
			processData: false,
			success: function(d) {
				if (d.status) {
					if (d.status == 'no') {
						alert('No hay nada que programar.');
					} else {
						alert('Los datos fueron guardados correctamente');
						update_programation();
					}
				}
				else {
					alert(d.error);
				}
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
			    // alert(jqXHR.error);
			    alert('Error al guardar los datos');
			}
		});
	}
});

