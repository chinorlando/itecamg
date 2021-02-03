var bandera;

$(document).ready(function(){

	// y esta es otra manera
	$.ajaxSetup({data: {csrf_test_name: CFG.token}});
    $(document).ajaxSuccess(function(e,x) {
        var result = $.parseJSON(x.responseText);
        // console.log(result.token);
        $('input:hidden[name="csrf_test_name"]').val(result.token);
        // console.log($('input:hidden[name="csrf_test_name"]').val());
        $.ajaxSetup({data: {csrf_test_name: result.token}});
    });
	// token end
	
    $('#btnSaveNotes').attr("disabled", true);

	$.get(CFG.url + 'teacher/get_carreras', function(data) {
		var carrera = $.parseJSON(data);
		// $('#materias').empty();
		$.each(carrera, function(index, val) {
			$('#carreras').append('<option value="'+val.id_carrera+'">'+val.nombre_carrera+'</option>');
		});
	});


	// $("#ocultar").click(function(){
 //        $(".primer_bim").hide();
 //        $(".primer_bim").slice(1).find('input').prop('disabled', true);
 //        // $(".primer_bim").slice(1).find('input').attr('readonly','readonly')
 //    });
 //    $("#mostrar").click(function(){
 //        $('.primer_bim').show();
 //        $(".primer_bim").slice(1).find('input').prop('disabled', true);
 //        // $(".primer_bim").slice(1).find('input').attr('readonly','readonly')
 //    });
	

	



	



});

$('#btnSaveNotes').attr('onClick'," alert('Los datos han sido modificados correctamente')" );

$('#carreras').change(function(e) {
	var id_carrera = $('#carreras').val();
	$('#materias').empty();
	$.ajax({
		url: CFG.url + 'teacher/get_materias',
		type: "POST",
		cache: true,
		data: {id_carrera: id_carrera},
		success: function(data) {
			var paralelos = $.parseJSON(data);
			$.each(paralelos, function(index, val) {
				$('#materias').append('<option value="'+val.id_materia+'">'+val.nombre+' - '+'Plan: '+val.nombre_plan+ '</option>');
			});
			materias();
		},
		error: function (jqXHR, textStatus, errorThrown)
		{
		    alert('Error al insertar los datos');
		}
	});
});

$('#materias').change(function(e) {
	materias();
});

function materias() {
	var id_carrera = $('#carreras').val();
	var id_materia = $('#materias').val();

	$('#paralelos').empty();
	$.post(CFG.url + 'teacher/get_paralelos',
		{
			id_carrera: id_carrera,
			id_materia: id_materia,
		},
		function(data, textStatus, xhr) {
			var paralelos = $.parseJSON(data);
			$.each(paralelos, function(index, val) {
				$('#paralelos').append('<option value="'+val.id_paralelo+'">'+val.nombre+'</option>');
			});
		}
	);
}

$("body").on("submit", "form#form_curso", function(e){
	e.preventDefault();
	var formData = new FormData($('#form_curso')[0]);
	formData.append('csrf_test_name', CFG.token);
	$.ajax({
		url: CFG.url + 'teacher/get_studen_with_notes',
		type: "POST",
		dataType: 'JSON',
		// data: $('#form_curso').serialize(),
		data: formData,
		contentType: false,
		processData: false,
		success: function(data) {
			if (data.status) {
				$('#curso_tbody').html(data.note);
				$(".clase_id_alumno").hide();
				configuraciones();
				$('#btnSaveNotes').attr("disabled", false);
			} else {
				$('#curso_tbody').html('');
				$('#btnSaveNotes').attr("disabled", true);
				alert('No hay alumnos programados en este paralelo.');
			}
		},
		error: function (jqXHR, textStatus, errorThrown)
		{
		    alert('Error al obtener los datos');
		}
	});
});

function pdf(){
    var id_carrera = $('#carreras').val();
	var id_materia = $('#materias').val();
	var paralelos = $('#paralelos').val();
    window.open(CFG.url + 'teacher/pdf_lista/'+id_carrera+'/'+id_materia+'/'+paralelos,'_blank'); 
};


function getNumbersInString(string) {
  var tmp = string.split("");
  var map = tmp.map(function(current) {
    if (!isNaN(parseInt(current))) {
      return current;
    }
  });

  var numbers = map.filter(function(value) {
    return value != undefined;
  });

  return numbers.join("");
}


// aumentado de aqui en adelante
function configuraciones() {

	$('input[type=text]').focus(function() {
		$(this).select();
	});

	$('input[type=text]').focusout(function(ev) {
		ev.preventDefault();
		var id_input = ev.target.id; // devuelve id -> primer_bim58
		var segundo_turno = $('#'+ev.target.id).val(); //valor de primer_bim58
		var floatN = parseFloat(segundo_turno);
		// var numero = getNumbersInString(id_input);
		// var dato = validar(segundo_turno);
		// calcular(numero);
		if ($(this).val() < 0) {
			// calcular(numero);
			$(this).focus();
			$(this).select();
			alert('valor debe estar entre 0 y 100');
		}
		if ($(this).val() > 100) {
			// calcular(numero);
			$(this).focus();
			$(this).select();
			alert('valor debe estar entre 0 y 100');
		}
		if (!isFinite(floatN)) {
			alert('el campo no debe estar vacio');
			$(this).val('0');
			$(this).focus();
			$(this).select();
		}
	});
	$('input[type=text]').change(function(ev) {
		ev.preventDefault();
		var id_input = ev.target.id; // devuelve id -> primer_bim58
		var segundo_turno = $('#'+ev.target.id).val(); //valor de primer_bim58
		var floatN = parseFloat(segundo_turno);
		var numero = getNumbersInString(id_input);
		// var dato = validar(segundo_turno);
		calcular(numero);
// 		console.log('1'+segundo_turno);
// console.log('2'+floatN);
// console.log('3'+numero);
	});

}

// function validar(varObj){
//     var value = 0;
//     if(!isPositiveInteger(varObj)){
//         alert('Dato no valido, debe introducir valor entre: (0..100)');
//         $(this).focus();
//         $(this).select();
//         value = 0;
//     }
//     else
//         value = varObj;
//     return parseFloat(value);
// }

// function isPositiveInteger(n){
//     var floatN = parseFloat(n);
//     console.log(floatN);
//     return isFinite(floatN) && (!(floatN < 0) && !(floatN > 100))&& (floatN % 1 == 0);
// }

function calcular(id_numero){

	var primer_bim = ($("#primer_bim"+id_numero).val()  != undefined)? $("#primer_bim"+id_numero).val() : '0';
	var segundo_bim = ($("#segundo_bim"+id_numero).val()  != undefined)? $("#segundo_bim"+id_numero).val() : '0';
	var tercer_bim = ($("#tercer_bim"+id_numero).val()  != undefined)? $("#tercer_bim"+id_numero).val() : '0';
	var cuarto_bim = ($("#cuarto_bim"+id_numero).val()  != undefined)? $("#cuarto_bim"+id_numero).val() : '0';
	var final = (parseFloat(primer_bim) + parseFloat(segundo_bim) + parseFloat(tercer_bim) + parseFloat(cuarto_bim))/4;
	var segundo_turno = ($("#segundo_turno"+id_numero).val()  != undefined)? $("#segundo_turno"+id_numero).val() : '0';
	document.getElementById('final'+id_numero).value = final;
	

	item = {};
	item["primer_bim"] = primer_bim;
	item["segundo_bim"] = segundo_bim;
	item["tercer_bim"] = tercer_bim;
	item["cuarto_bim"] = cuarto_bim;
	item["final"] = final;
	item["segundo_turno"] = segundo_turno;
	aInfo = JSON.stringify(item);
	
	var formData = new FormData($('#form_notes')[0]);
	var id_carrera = $('#carreras').val();
	var id_materia = $('#materias').val();
	var id_paralelo = $('#paralelos').val();
	formData.append('id_carrera', id_carrera);
    formData.append('id_materia', id_materia);
    formData.append('id_paralelo', id_paralelo);
    formData.append('id_alumno', id_numero);
	formData.append('notas', aInfo);
	formData.append('csrf_test_name', CFG.token);

	$.ajax({
		url: CFG.url + 'teacher/if_save_notes',
		type: "POST",
		dataType: 'JSON',
		cache:false,
		data: formData,
		contentType: false,
		processData: false,
		success: function(data) {
			if (true) {
				console.log('Las notas fueron guardadas correctamente');
			} else {
				console.log(data.error);
			}
		},
		error: function (jqXHR, textStatus, errorThrown)
		{
		    alert('Error al guardar las notas.');
		}
	});
}