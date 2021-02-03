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
	// $.fn.dataTable.ext.order['dom-text-numeric-plan'] = function  ( settings, col )
	// {
	//     return this.api().column( col, {order:'index'} ).nodes().map( function ( td, i ) {
	//     	console.log($('input', td).val());
	//         return $('input', td).val();
	//     } );
	// }

	// $.fn.dataTable.ext.order['dom-text-carrera'] = function  ( settings, col )
	// {
	//     return this.api().column( col, {order:'index'} ).nodes().map( function ( td, i ) {
	//     	console.log($('input', td).val());
	//         return $('input', td).val();
	//     } );
	// }
	 
	// /* Create an array with the values of all the input boxes in a column, parsed as numbers */
	// $.fn.dataTable.ext.order['dom-text-numeric-sigla'] = function  ( settings, col )
	// {
	//     return this.api().column( col, {order:'index'} ).nodes().map( function ( td, i ) {
	//     	console.log($('input', td).val());
	//         return $('input', td).val() * 1;
	//     } );
	// }

	// $.fn.dataTable.ext.order['dom-text-materia'] = function  ( settings, col )
	// {
	//     return this.api().column( col, {order:'index'} ).nodes().map( function ( td, i ) {
	//     	console.log($('input', td).val());
	//         return $('input', td).val();
	//     } );
	// }

	// $.fn.dataTable.ext.order['dom-text-numeric-curso'] = function  ( settings, col )
	// {
	//     return this.api().column( col, {order:'index'} ).nodes().map( function ( td, i ) {
	//     	console.log($('input', td).val());
	//         return $('input', td).val() * 1;
	//     } );
	// }

	// $.fn.dataTable.ext.order['dom-text-numeric-paralelo'] = function  ( settings, col )
	// {
	//     return this.api().column( col, {order:'index'} ).nodes().map( function ( td, i ) {
	//     	console.log($('input', td).val());
	//         return $('input', td).val() * 1;
	//     } );
	// }
	 
	// /* Create an array with the values of all the select options in a column */
	// $.fn.dataTable.ext.order['dom-select-docente'] = function  ( settings, col )
	// {
	//     return this.api().column( col, {order:'index'} ).nodes().map( function ( td, i ) {
	//     	console.log($('input', td).val());
	//         return $('select', td).val();
	//     } );
	// }

// 	$.fn.dataTableExt.afnSortData['dom-select-docente'] = function  ( oSettings, iColumn )
// {
//     var aData = [];
//     $( 'td:eq('+iColumn+') select', oSettings.oApi._fnGetTrNodes(oSettings) ).each( function () {
//     	console.log($('input', td).val());
//     	console.log($(this).val());
    	
//         aData.push( $(this).val() );
//     } );
//     return aData;
// }
	 
	/* Create an array with the values of all the checkboxes in a column */
	// $.fn.dataTable.ext.order['dom-checkbox-opciones'] = function  ( settings, col )
	// {
	//     return this.api().column( col, {order:'index'} ).nodes().map( function ( td, i ) {
	//         return $('input', td).prop('checked') ? '1' : '0';
	//     } );
	// }

	table = $('.table').DataTable({ 
		"columnDefs": [
			{ "targets": 1, "orderDataType": "dom-text-numeric-plan" },
            { "targets": 2, "orderDataType": "dom-text-carrera", type: 'numeric' },
            { "targets": 3, "orderDataType": "dom-text-numeric-sigla" },
            { "targets": 4, "orderDataType": "dom-text-materia", type: 'numeric' },
            { "targets": 5, "orderDataType": "dom-text-numeric-curso" },
            { "targets": 6, "orderDataType": "dom-text-numeric-paralelo" },
            { "targets": 7, "orderDataType": "dom-select-docente" },
            // { "orderDataType": "dom-text", type: 'string' },
            null,
        ],
		"searching": true,
		"destroy": true,
	    "processing": true, //Feature control the processing indicator.
	    "serverSide": true, //Feature control DataTables' server-side processing mode.
	    "order": [], //Initial no order.

	    // Load data for the table's content from an Ajax source
	    "ajax": {
	        'url': CFG.url+"admin/list_teacher_subjects",
	        "type": "POST"
	    },

	    //Set column definition initialisation properties.
	    "columnDefs": [{ 
		        "targets": [ -1, -2 ], //last column
		        "orderable": false, //set not orderable
		}],
		"language": {
            "info" : "Mostrar _START_ al _END_ de _TOTAL_ docentes asignados",
            "loadingRecords":  "No hay docentes...",
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
		  //   "lengthMenu": 'Mostrar <select>'+
				// '<option value="10">10</option>'+
				// '<option value="25">25</option>'+
				// '<option value="50">50</option>'+
				// '<option value="100">100</option>'+
				// '<option value="-1">All</option>'+
				// '</select> Registros'
        },
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]]
    });




});

function edit_parallel(indice, id_pensum, id_docente, id_paralelo, existe)
{
	// alert(id_paralelo);
	$('#indice').val(indice);
    $('#id_pensum').val(id_pensum);
    // $('#id_docente').val(id_docente);
    $('#id_paralelo').val(id_paralelo);
    $('#existe').val(existe);
	$('#modal_confirmar').modal('show'); // show bootstrap modal when complete loaded
 //    $.ajax({
	// 	url : CFG.url + 'Admin/list_teacher_subjects/',
	// 	type: 'POST',
	// 	dataType: 'JSON',
	// 	data: {id_pensum: id_pensum, id_docente: id_docente, id_paralelo: id_paralelo },
	// })
	// .done(function(data) {
	// 	console.log("Se guardo con éxito");
	// 	$('#opciones').html(data);
	// })
	// .fail(function() {
	// 	console.log("Error al obtener los menús.");
	// });
}

function guardar_asignacion()
{
	$('#modal_confirmar').modal('hide');
	// var id_pensum = $('#id_pensum').val();
 //    var id_docente = $('#id_docente').val();
    // console.log(id_pensum);
    var indice = $('#indice').val();
    var id_docente = document.getElementById("docente"+indice).value;

	if($('#existe').val() == 0) {
		url = CFG.url + 'admin/add_asignacion_docente';
	} else {
		url = CFG.url + 'admin/update_asignacion_docente';
	}

	data = new FormData($('#form_parallel')[0]);
	data.append('id_docente', id_docente);

	if (id_docente != -1) {

	    $.ajax({
	        url: url,
			type: "POST",
			cache: true,
			dataType: 'JSON',
			data: data,
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
	            	alert('Error al guardar paralelos.');
	            }
	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	            alert('Error al guardar paralelos.');
	        }
	    });
	} else {
		alert('Debe asignar al docente antes de guardar');
	}
	
	
}

function reload_table(){
    table.ajax.reload(null,false); //reload datatable ajax 
}


// obtener el valor de un select en una tabla->>>>>>>>importante
// function imprimirValor(id_paralelo){
//   var select = document.getElementById("docente"+id_paralelo);
//   var options=document.getElementsByTagName("option");
//   console.log(select.value);
//   console.log(options[select.value-1].innerHTML);
//   // console.log($('.table').children().find("select").find(":selected").val());
// }