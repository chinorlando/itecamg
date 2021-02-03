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
	    "sync": false,
	    "order": [], //Initial no order.

	    // Load data for the table's content from an Ajax source
	    "ajax": {
	        // "url": "<?php echo site_url('Csv_import/load_datauno')?>",
	        'url': CFG.url+"admin/list_gestionperiodo",
	        "type": "POST",
	    },

	    //Set column definition initialisation properties.
	    "columnDefs": [{ 
		        "targets": [ -1 ], //last column
		        "orderable": false, //set not orderable
		}],
		"language": {
            "info" : "Mostrar _START_ al _END_ de _TOTAL_ registros",
            "loadingRecords":  "No hay gestion ni periodo...",
            "processing" :     "Procesando...",
            "search" :      "Buscar:    ",
            "zeroRecords": "No se encontraron resultados",
            "infoEmpty":  "No hay gestion ni periodo",
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

    $.get(CFG.url + 'admin/get_tipogestion', function(data) {
		var tr = $.parseJSON(data);
		$.each(tr, function(index, val) {
			$('#tipogestion').append('<option value="'+val.id_tipogestion+'">'+val.nombre +'</option>');
		});
	});








});


function ShowHideDiv(id_gestionperiodo) {
	// var id_gestion_periodo = $(this).val();
	$.get(
		CFG.url +"admin/update_state",
		{
			'id_gestionperiodo':id_gestionperiodo,
		},
		function(data) {
			console.log(data);
		}
	);
}



function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}

function add_gestionperiodo(){
	save_method = 'add';
    $('#form_gestionperiodo')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_confirmar').modal('show'); // show bootstrap modal when complete loaded
    $('.modal-title').text('Añadir Gestion y Periodo'); // Set Title to Bootstrap modal title

    
}

function save()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    var url;

    if(save_method == 'add') {
        url = CFG.url +"admin/add_gestionperiodo";
    } else {
        url = CFG.url +"admin/update_gestionperiodo";
    }
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form_gestionperiodo').serialize(),
        dataType: "JSON",
        success: function(data)
        {
            if(data.status) //if success close modal and reload ajax table
            {
            	// alert('Los datos fueron guardados correctamente.')
                // $('#modal_confirmar').modal('hide');
                reload_table();
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++) 
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                }
            }
            $('#btnSave').text('Guardar'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 
            $('#modal_confirmar').modal('hide'); // show bootstrap modal when complete loaded

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error al guardar los datos.');
            $('#btnSave').text('Guardar'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 

        },
    });
}

function edit_gestionperiodo(id)
{
    // save_method = 'update';
    $('#form_gestionperiodo')[0].reset(); // reset form on modals
    // $('.form-group').removeClass('has-error'); // clear error class
    // $('.help-block').empty(); // clear error string

	$.ajax({
	    url : CFG.url + 'admin/ajax_edit_update/' + id,
	    type: "GET",
	    dataType: "JSON",
	    success: function(data)
	    {
	        $('[name="id_gestionperiodo"]').val(id);
	        $('[name="gestion"]').val(data.gestion);
	        $('[name="periodo"]').val(data.periodo);
			if (data.estado) {
				$('[name="estado"]').prop('checked',true);
			}
	        $('[name="tipogestion"]').val(data.id_tipogestion);

	        $('#modal_confirmar').modal('show'); // show bootstrap modal when complete loaded
	        $('.modal-title').text('Editar gestion/periodo'); // Set title to Bootstrap modal title
	        
	    },
	    error: function (jqXHR, textStatus, errorThrown)
	    {
	        alert('Error al obtener los datos.');
	    }
	});
}


// function delete_person(id)
// {
//     if(confirm('Estas seguro de eliminar este dato?'))
//     {
//         // ajax delete data to database
//         $.ajax({
//             url : CFG.url + "admin/ajax_delete_docente/" + id,
//             type: "POST",
//             dataType: "JSON",
//             success: function(data)
//             {
//                 //if success reload ajax table
//                 // $('#modal_confirmar').modal('hide');
//                 reload_table();
//             },
//             error: function (jqXHR, textStatus, errorThrown)
//             {
//                 alert('Error al eliminar el registro');
//             }
//         });

//     }
// }