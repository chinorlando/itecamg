var save_method; //for save method string
var table;

$(document).ready(function() {
	// token begin
	$.ajaxSetup({ data: { csrf_test_name: CFG.token } });
	$(document).ajaxSuccess(function(e, x) {
		var result = $.parseJSON(x.responseText);
		$('input:hidden[name="csrf_test_name"]').val(CFG.token);
		$.ajaxSetup({ data: { csrf_test_name: result.token } });
	});
	// token end

	table = $(".table").DataTable({
		searching: true,
		destroy: true,
		processing: true, //Feature control the processing indicator.
		serverSide: true, //Feature control DataTables' server-side processing mode.
		order: [], //Initial no order.

		// Load data for the table's content from an Ajax source
		ajax: {
			// "url": "<?php echo site_url('Csv_import/load_datauno')?>",
			url: CFG.url + "profile/list_teachers",
			type: "POST"
		},

		//Set column definition initialisation properties.
		columnDefs: [
			{
				targets: [-1], //last column
				orderable: false //set not orderable
			}
		],
		language: {
			info: "Mostrar _START_ al _END_ de _TOTAL_ docentes",
			loadingRecords: "No hay docentes...",
			processing: "Procesando...",
			search: "Buscar:    ",
			zeroRecords: "No se encontraron resultados",
			infoEmpty: "No hay docentes",
			lengthMenu: "Mostrar _MENU_ registros",
			infoFiltered: "(Filtrado de _MAX_ registros en total)",
			paginate: {
				first: "Primero",
				last: "Último",
				next: "Siguiente",
				previous: "Anterior"
			},
			aria: {
				sortAscending: ": activate to sort column ascending",
				sortDescending: ": activate to sort column descending"
			},
			lengthMenu:
				"Mostrar <select>" +
				'<option value="10">10</option>' +
				'<option value="25">25</option>' +
				'<option value="50">50</option>' +
				'<option value="100">100</option>' +
				'<option value="-1">All</option>' +
				"</select> Registros"
		}
	});

	$.get(CFG.url + "profile/get_cargo", function(data) {
		var tr = $.parseJSON(data);
		$.each(tr, function(index, val) {
			$("#cargo").append(
				'<option value="' + val.id_cargo + '">' + val.nombre_cargo + "</option>"
			);
		});
	});

	$.get(CFG.url + "profile/get_rol", function(data) {
		var tr = $.parseJSON(data);
		$.each(tr, function(index, val) {
			$("#rol").append(
				'<option value="' + val.id_rol + '">' + val.nombre + "</option>"
			);
		});
	});

	$("#check-all").click(function() {
		$(".cuenta_personal").attr("disabled", !$(this).prop("checked"));
		$(".cuenta_personal")
			.parent()
			.removeClass("has-error");
		$(".cuenta_personal")
			.next()
			.empty();
		// $("").addClass("");
		if ($(this).prop("checked")) {
			$(".cuenta_personal")
				.parent()
				.parent()
				.parent()
				.parent()
				.removeClass("alpha-slate");
		} else {
			$(".cuenta_personal")
				.parent()
				.parent()
				.parent()
				.parent()
				.addClass("alpha-slate");
		}
	});

	$("input").change(function() {
		$(this)
			.parent()
			.removeClass("has-error");
		$(this)
			.next()
			.empty();
	});
	$("select").change(function() {
		$(this)
			.parent()
			.removeClass("has-error");
		$(this)
			.next()
			.empty();
	});

	// $('#form_import').on('submit', function(event){
	//     event.preventDefault();
	//     $.ajax({
	//         url:CFG.url+"admin/import_teacher",
	//         method:"POST",
	//         data:new FormData(this),
	//         contentType:false,
	//         cache:false,
	//         processData:false,
	//         beforeSend:function(){
	//             $('#import_csv_btn').html('Importing...');
	//         },
	//         success:function(data)
	//         {
	//             $('#form_import')[0].reset();
	//             $('#form_import_btn').attr('disabled', false);
	//             $('#form_import_btn').html('Import Done');
	//             reload_table();
	//         }
	//     })
	// });
});

function reload_table() {
	table.ajax.reload(null, false); //reload datatable ajax
}

function add_teacher() {
	save_method = "add";
	$("#form_teacher")[0].reset(); // reset form on modals
	$(".form-group").removeClass("has-error"); // clear error class
	$(".help-block").empty(); // clear error string
	$("#modal_confirmar").modal("show"); // show bootstrap modal when complete loaded
	$(".modal-title").text("Añadir Personal "); // Set Title to Bootstrap modal title
}

function save() {
	$("#btnSave").text("saving..."); //change button text
	$("#btnSave").attr("disabled", true); //set button disable
	var url;

	if (save_method == "add") {
		url = CFG.url + "profile/add_teacher";
	} else {
		url = CFG.url + "profile/update_teacher";
	}
	$.ajax({
		url: url,
		type: "POST",
		data: $("#form_teacher").serialize(),
		dataType: "JSON",
		success: function(data) {
			if (data.status) {
				//if success close modal and reload ajax table
				$("#modal_confirmar").modal("hide");
				reload_table();
			} else {
				for (var i = 0; i < data.inputerror.length; i++) {
					$('[name="' + data.inputerror[i] + '"]')
						.parent()
						.addClass("has-error"); //select parent twice to select div form-group class and add has-error class
					$('[name="' + data.inputerror[i] + '"]')
						.next()
						.text(data.error_string[i]); //select span help-block class set text error string
				}
			}
			$("#btnSave").text("Guardar");
			$("#btnSave").attr("disabled", false);
		},
		error: function(jqXHR, textStatus, errorThrown) {
			alert("Error adding / update data");
			$("#btnSave").text("Guardar"); //change button text
			$("#btnSave").attr("disabled", false); //set button enable
		}
	});
}

function edit_person(id) {
	save_method = "update";
	$("#form_teacher")[0].reset(); // reset form on modals
	$(".form-group").removeClass("has-error"); // clear error class
	$(".help-block").empty(); // clear error string

	//Ajax Load data from ajax
	$.ajax({
		url: CFG.url + "profile/ajax_edit_docente/" + id,
		type: "GET",
		dataType: "JSON",
		success: function(data) {
			$("#check-all").prop("checked", false);
			$(".cuenta_personal").attr("disabled", true);
			$(".cuenta_personal")
				.parent()
				.parent()
				.parent()
				.parent()
				.addClass("alpha-slate");

			$('[name="id_persona"]').val(data.id_persona);
			$('[name="nombres"]').val(data.nombres);
			$('[name="apellido_paterno"]').val(data.apellido_paterno);
			$('[name="apellido_materno"]').val(data.apellido_materno);
			$('[name="ci"]').val(data.ci);
			$('[name="expedido"]').val(data.expedido);
			$('[name="estado_civil"]').val(data.estado_civil);
			$('[name="email"]').val(data.email);
			$('[name="sexo"]').val(data.sexo);
			$('[name="fecha_nacimiento"]').val(data.dob);
			// $('[name="dob"]').datepicker('update',data.dob);
			$('[name="direccion"]').val(data.direccion);
			$('[name="celular"]').val(data.celular);
			$('[name="telefono_fijo"]').val(data.telefono_fijo);
			$('[name="lugar_trabajo"]').val(data.lugar_trabajo);
			$('[name="direccion_trabajo"]').val(data.direccion_trabajo);
			$('[name="telefono_trabajo"]').val(data.telefono_trabajo);

			$('[name="id_cuenta"]').val(data.id_cuenta);
			$('[name="username"]').val(data.username);
			$('[name="password"]').val("");
			$('[name="is_type_user"]').val(data.is_type_user);
			$('[name="email_cuenta"]').val(data.email_cuenta);
			$('[name="repeat_password"]').val("");

			$('[name="rol"]').val(data.id_rol);
			$('[name="cargo"]').val(data.id_cargo);

			$("#modal_confirmar").modal("show");
			$(".modal-title").text("Editar Docente");
			$("#btnSave").text("Actualizar");
		},
		error: function(jqXHR, textStatus, errorThrown) {
			alert("Error get data from ajax");
		}
	});
}

function delete_person(id) {
	if (confirm("Estas seguro de eliminar este dato?")) {
		// ajax delete data to database
		$.ajax({
			url: CFG.url + "profile/ajax_delete_docente/" + id,
			type: "POST",
			dataType: "JSON",
			success: function(data) {
				//if success reload ajax table
				// $('#modal_confirmar').modal('hide');
				reload_table();
			},
			error: function(jqXHR, textStatus, errorThrown) {
				alert("Error al eliminar el registro");
			}
		});
	}
}
