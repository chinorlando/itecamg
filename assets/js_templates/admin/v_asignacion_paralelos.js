
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
	    "order": [], //Initial no order.

	    // Load data for the table's content from an Ajax source
	    "ajax": {
	        'url': CFG.url+"admin/list_subjects",
	        "type": "POST"
	    },

	    //Set column definition initialisation properties.
	    "columnDefs": [{ 
		        "targets": [ -2, -1 ], //last column
		        "orderable": false, //set not orderable
		}],
		"language": {
            "info" : "Mostrar _START_ al _END_ de _TOTAL_ materias",
            "loadingRecords":  "No hay materias...",
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


	$(".check-all-paralelos").click(function () {
		$(".check_paralelo").prop('checked', $(this).prop('checked'));
	});



});

function reload_table(){
    table.ajax.reload(null,false); //reload datatable ajax 
}

function edit_parallel(id)
{
    $('#id_materia').val(id);
	$('#modal_confirmar').modal('show'); // show bootstrap modal when complete loaded
    $.ajax({
		url : CFG.url + 'Admin/list_parallels/',
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

function save_parallels()
{
    $.ajax({
        url: CFG.url +"admin/add_parallels",
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
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error al guardar paralelos.');
        }
    });
}