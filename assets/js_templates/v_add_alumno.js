
$(document).ready(function(){
	// token begin
  $.ajaxSetup({data: {token: CFG.token}});
  $(document).ajaxSuccess(function(e,x) {
    var result = $.parseJSON(x.responseText);
    $('input:hidden[name="token"]').val(result.token);
    $.ajaxSetup({data: {token: result.token}});
  });
  // token end

  

	$('#btnBuscarAlumno').click(function(){
		searchpersona();
		console.log("hola mundo");
	});
	$('#btnSavealumno').click(function(){
		save_matricula();
	});
	$("body").on("submit", "#form_alumno_nuevo", function(e){
		e.preventDefault();
		$.ajax({
			url: CFG.url+"alumno/ajax_add_form",
			type: "POST",
			dataType: 'JSON',
			data: $('#form_alumno_nuevo').serialize(),
			success: function(d) {
				if (d.status) {
					alert('Los datos fueron guardados correctamente');
          // $('#id_persona').val(d.persona.id_persona);
          // $('#nombrereclamante').val(d.persona.nombres+' '+d.persona.apellidos);
				}else {
					alert("Cédula de Identidad ya existe.");
				}
			},
			error: function (jqXHR, textStatus, errorThrown){
				alert('Error al insertar los datos');
			}
		});
	});


});


function searchpersona(argument) {
	if ($('#ci').val()=="") {
		alert("el campo esta vacio");
	} else {
		var parametros = {
			"ci" : $('#ci').val(),
		};
		$.ajax({
			url: CFG.url+'alumno/alumnoci',
			type: 'POST',
			dataType: 'JSON',
			data: parametros,
			success: function(persona, ui) {
				var personahtml = '';
				var alerta = '';
				// console.log(persona);
				if (persona) {
					personahtml +=
					'<div class="box box-default">'+
            '<div class="box-header with-border">'+
              '<h3 class="box-title">Datos del alumno</h3>'+
              '<div class="box-tools pull-right">'+
                '<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>'+
                '<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>'+
              '</div>'+
            '</div>'+
            '<div class="box-body">'+
              '<div class="row">'+
                '<div class="col-md-4">'+
                  '<div class="form-group">'+
                    '<label for="">Nombres</label>'+
                    '<input type="text" class="form-control" id="" value="'+persona.nombres+'" placeholder="" value="">'+
                  '</div>'+
                '</div>'+
                '<div class="col-md-4">'+
                  '<div class="form-group">'+
                    '<label for="">Apellido paterno</label>'+
                    '<input type="text" class="form-control" id="" value="'+persona.apellido_paterno+'" placeholder="">'+
                  '</div>'+
                '</div>'+
                '<div class="col-md-4">'+
                  '<div class="form-group">'+
                    '<label for="">Apellido materno</label>'+
                          '<input type="text" class="form-control" id="" value="'+persona.apellido_materno+'" placeholder="">'+
                  '</div>'+
                '</div>'+
                '<div class="col-md-4">'+
                  '<div class="form-group">'+
                    '<label for="">Sexo</label>'+
                    '<input type="text" class="form-control" id="" value="'+persona.sexo+'" placeholder="">'+
                  '</div>'+
                '</div>'+
                '<div class="col-md-4">'+
                  '<div class="form-group">'+
                    '<label for="">Estado Civil</label>'+
                    '<input type="text" class="form-control" id="" value="'+persona.estado_civil+'" placeholder="">'+
                  '</div>'+
                '</div>'+
                '<div class="col-md-4">'+
                  '<div class="form-group">'+
                    '<label for="">Carnet de identidad</label>'+
                    '<input type="text" class="form-control" id="" value="'+persona.ci+'" placeholder="">'+
                  '</div>'+
                '</div>'+
                '<div class="col-md-4">'+
                  '<div class="form-group">'+
                    '<label for="">Expedido en</label>'+
                    '<input type="text" class="form-control" id="" value="'+persona.expedido+'" placeholder="">'+
                  '</div>'+
                '</div>'+
                '<div class="col-md-4">'+
                  '<div class="form-group">'+
                    '<label>Carrera a la que postula</label>'+
                    '<input type="text" class="form-control" id="" value="'+persona.nombre_carrera+'" placeholder="">'+
                  '</div>'+
                '</div>'+
                '<div class="col-md-4">'+
                  '<div class="form-group">'+
                    '<label for="">Fecha de nacimiento</label>'+
                    '<input type="text" class="form-control" id="" value="'+persona.fecha_nacimiento+'" placeholder="">'+
                  '</div>'+
                '</div>'+
                '<div class="col-md-4">'+
                  '<div class="form-group">'+
                    '<label for="">Celular</label>'+
                     '<input type="text" class="form-control" id="" value="'+persona.celular+'" placeholder="">'+
                  '</div>'+
                '</div>'+
                '<div class="col-md-4">'+
                  '<div class="form-group">'+
                    '<label for="">Telefono fijo</label>'+
                     '<input type="text" class="form-control" id="" value="'+persona.fijo+'" placeholder="">'+
                  '</div>'+
                '</div>'+
                '<div class="col-md-12">'+
                  '<div class="form-group">'+
                    '<label for="">Dirección</label>'+
                          '<input type="text" class="form-control" id="" value="'+persona.direccion+'" placeholder="">'+
                  '</div>'+
                '</div>'+
              '</div>  '+
            '</div>'+
          '</div>'+

          '<div class="box box-default">'+
            '<div class="box-header with-border">'+
              '<h3 class="box-title">Datos del apoderado</h3>'+
              '<div class="box-tools pull-right">'+
                '<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>'+
                '<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>'+
              '</div>'+
            '</div>'+
            '<div class="box-body">'+
              '<div class="row">'+
                '<div class="col-md-4">'+
                  '<div class="form-group">'+
                    '<label for="">Nombres</label>'+
                    '<input type="text" class="form-control" id="" value="'+persona.nombre_padre+'" placeholder="">'+
                  '</div>'+
                '</div>'+
                '<div class="col-md-4">'+
                  '<div class="form-group">'+
                      '<label for="">Apellido paterno</label>'+
                      '<input type="text" class="form-control" id="" value="'+persona.ocupacion_padre+'" placeholder="">'+
                    '</div>'+
                '</div>'+
                '<div class="col-md-4">'+
                  '<div class="form-group">'+
                    '<label for="">Apellido materno</label>'+
                    '<input type="text" class="form-control" id="" value="'+persona.celular_padre+'" placeholder="">'+
                  '</div>'+
                '</div>'+
                '<div class="col-md-4">'+
                  '<div class="form-group">'+
                    '<label for="">Nombres</label>'+
                    '<input type="text" class="form-control" id="" value="'+persona.nombre_madre+'" placeholder="">'+
                  '</div>'+
                '</div>'+
                '<div class="col-md-4">'+
                  '<div class="form-group">'+
                      '<label for="">Apellido paterno</label>'+
                      '<input type="text" class="form-control" id="" value="'+persona.ocupacion_madre+'" placeholder="">'+
                    '</div>'+
                '</div>'+
                '<div class="col-md-4">'+
                  '<div class="form-group">'+
                    '<label for="">Apellido materno</label>'+
                    '<input type="text" class="form-control" id="" value="'+persona.celular_madre+'" placeholder="">'+
                  '</div>'+
                '</div>'+
              '</div>  '+
            '</div>'+
          '</div>';
					$('#alumno').html(personahtml);
					$('#alumnovacio').html('');
				} else {
					alerta +=
						'<div class="alert bg-warning alert-styled-right">'+
              '<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>'+
              '<span class="text-semibold">Registros no encontrados. INGRESE DATOS DE LA PERSONA</span>'+
            '</div>';
          personahtml += 
	        '<div class="box box-default">'+
	          '<form id="form_alumno_nuevo">'+
	            '<div class="box-header with-border">'+
	              '<h3 class="box-title">Datos del alumno</h3>'+
	              '<div class="box-tools pull-right">'+
	                '<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>'+
	                '<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>'+
	              '</div>'+
	            '</div>'+
	            '<div class="box-body">'+
	              '<div class="row">'+
	                '<div class="col-md-4">'+
	                  '<div class="form-group">'+
	                    '<label for="">Nombres</label>'+
	                    '<input type="text" class="form-control" id="nombres" name="nombres" placeholder="" >'+
	                  '</div>'+
	                '</div>'+
	                '<div class="col-md-4">'+
	                  '<div class="form-group">'+
	                    '<label for="">Apellido paterno</label>'+
	                    '<input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" placeholder="" >'+
	                  '</div>'+
	                '</div>'+
	                '<div class="col-md-4">'+
	                  '<div class="form-group">'+
	                    '<label for="">Apellido materno</label>'+
	                          '<input type="text" class="form-control" id="apellido_materno" name="apellido_materno" placeholder="" >'+
	                  '</div>'+
	                '</div>'+
	                '<div class="col-md-4">'+
	                  '<div class="form-group">'+
	                    '<label for="">Sexo</label>'+
	                    // '<input type="text" class="form-control" id="sexo" name="sexo" placeholder="">'+
	                    '<select id="sexo" name="sexo" class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true">'+
	                      '<option selected="selected"></option>'+
	                      '<option>Femenino</option>'+
	                      '<option>Masculino</option>'+
	                    '</select>'+
	                  '</div>'+
	                '</div>'+
	                '<div class="col-md-4">'+
	                  '<div class="form-group">'+
	                    '<label for="">Estado Civil</label>'+
	                    // '<input type="text" class="form-control" id="estado_civil" name="estado_civil" placeholder="">'+
	                    '<select id="estado_civil" name="estado_civil" class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true">'+
	                      '<option selected="selected">Soltero(a)</option>'+
	                      '<option>Casado(a)</option>'+
	                      '<option>Viudo(a)</option>'+
	                      '<option>Divorciado(a)</option>'+
	                    '</select>'+
	                  '</div>'+
	                '</div>'+
	                '<div class="col-md-4">'+
	                  '<div class="form-group">'+
	                    '<label for="">Carnet de identidad</label>'+
	                    '<input type="text" class="form-control" id="ci" name="ci" placeholder="" >'+
	                  '</div>'+
	                '</div>'+
	                '<div class="col-md-4">'+
	                  '<div class="form-group">'+
	                    '<label for="">Expedido en</label>'+
	                    // '<input type="text" class="form-control" id="expedido" name="expedido" placeholder="">'+
	                    '<select id="expedido" name="expedido" class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true">'+
	                      '<option selected="selected"></option>'+
	                      '<option>Chuquisaca</option>'+
	                      '<option>Beni</option>'+
	                      '<option>Pando</option>'+
	                      '<option>Santa Cruz</option>'+
	                      '<option>Oruro</option>'+
	                      '<option>La Paz</option>'+
	                      '<option>Potosi</option>'+
	                      '<option>Cochabamba</option>'+
	                      '<option>Tarija</option>'+
	                    '</select>'+
	                  '</div>'+
	                '</div>'+
	                '<div class="col-md-4">'+
	                  '<div class="form-group">'+
	                    '<label>Carrera a la que postula</label>'+
	                    // '<input type="text" class="form-control" id="carrera" name="carrera" placeholder="">'+
	                    '<select id="carrera" name="carrera" class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true">'+
	                      '<option selected="selected">Seleccione...</option>'+
	                    '</select>'+
	                  '</div>'+
	                '</div>'+
	                '<div class="col-md-4">'+
	                  '<div class="form-group">'+
	                    '<label for="">Fecha de nacimiento</label>'+
	                          '<input type="text" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" placeholder="">'+
	                  '</div>'+
	                '</div>'+
	                '<div class="col-md-4">'+
	                  '<div class="form-group">'+
	                    '<label for="">Celular</label>'+
	                          '<input type="text" class="form-control" id="celular" name="celular" placeholder="">'+
	                  '</div>'+
	                '</div>'+
	                '<div class="col-md-8">'+
	                  '<div class="form-group">'+
	                    '<label for="">Dirección</label>'+
	                    '<input type="text" class="form-control" id="direccion" name="direccion" placeholder="">'+
	                  '</div>'+
	                '</div>'+
	                '<div class="col-md-8">'+
	                  '<div class="form-group">'+
	                    '<label for="">Email</label>'+
	                    '<input type="text" class="form-control" id="email" name="email" placeholder="">'+
	                  '</div>'+
	                '</div>'+






	                '<div class="col-md-4">'+
	                  '<div class="form-group">'+
	                    '<label for="">Nombre completo del padre</label>'+
	                    '<input type="text" class="form-control" id="nombre_padre" name="nombre_padre" placeholder="">'+
	                  '</div>'+
	                '</div>'+
	                '<div class="col-md-4">'+
	                  '<div class="form-group">'+
	                      '<label for="">Ocupación del padre</label>'+
	                      '<input type="text" class="form-control" id="ocupacion" name="ocupacion" placeholder="">'+
	                    '</div>'+
	                '</div>'+
	                '<div class="col-md-4">'+
	                  '<div class="form-group">'+
	                    '<label for="">Nº de ceelular del padre</label>'+
	                    '<input type="text" class="form-control" id="celular_padre" name="celular_padre" placeholder="">'+
	                  '</div>'+
	                '</div>'+
	                '<div class="col-md-4">'+
	                  '<div class="form-group">'+
	                    '<label for="">Nombre completo de la madre</label>'+
	                    '<input type="text" class="form-control" id="nombre_madre" name="nombre_madre" placeholder="">'+
	                  '</div>'+
	                '</div>'+
	                '<div class="col-md-4">'+
	                  '<div class="form-group">'+
	                    '<label for="">Ocupación de la madre</label>'+
	                    '<input type="text" class="form-control" id="ocupacion_madre" name="ocupacion_madre" placeholder="">'+
	                  '</div>'+
	                '</div>'+
	                '<div class="col-md-4">'+
	                  '<div class="form-group">'+
	                      '<label for="">Nº de ceelular de la madre</label>'+
	                      '<input type="text" class="form-control" id="celular_madre" name="celular_madre" placeholder="">'+
	                    '</div>'+
	                '</div>'+
	              '</div>  '+
	            '</div>'+
	            
	            '<div class="box-footer">'+
	              // '<button type="submit" class="btn btn-default">Cancel</button>'+
	              // '<button type="button" id="btnSavealumno" class="btn btn-info pull-right" id="guardaralumnos">Guardar</button>'+
	              '<button type="submit" class="btn btn-primary" >Matricular</button>'+
	            '</div>'+
	          '</form>'+
	        '</div>';
					cargarcarrera();
					$('#alumnovacio').html(personahtml);
					$('#alumno').html('');
					$('#alumno').html(alerta);
				}
			}
		});
	}
}

function cargarcarrera() {
	$.get(CFG.url+'alumno/get_carrera', function(data) {
		var tr = $.parseJSON(data);
		$.each(tr, function(index, val) {
			$('#carrera').append('<option value="'+val.id_carrera+'">'+val.nombre_carrera+'</option>');
		});
	});
}

function save_alumno() {
	// $('#btnSave').text('guardando...'); //change button text
	// $('#btnSave').attr('disabled',true); //set button disable
	console.log('hola mundillo');
	var url;

	url = CFG.url+"alumno/ajax_add_form";

	// ajax adding data to database
	$.ajax({
		url : url,
		type: "POST",
		data: $('#form_alumno_nuevo').serialize(),
		dataType: "JSON",
		success: function(data){
			if(data.status) //if success close modal and reload ajax table
			{
				// $('#modal_form').modal('hide');
				// reload_table();
				console.log('datos guardados correctamente');
			} else {
				// for (var i = 0; i < data.inputerror.length; i++)
				// {
				//     $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
				//     $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
				// }
			}
		// $('#btnSave').text('save'); //change button text
		// $('#btnSave').attr('disabled',false); //set button enable
		},
		error: function (jqXHR, textStatus, errorThrown){
			alert('Error adding / update data');
			$('#btnSave').text('save'); //change button text
			$('#btnSave').attr('disabled',false); //set button enable
		}
	});
}