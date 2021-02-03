var table;
$(document).ready(function(){
	// token begin
	$.ajaxSetup({data: {csrf_test_name: CFG.token}});
    $(document).ajaxSuccess(function(e,x) {
        var result = $.parseJSON(x.responseText);
        $('input:hidden[name="csrf_test_name"]').val(result.token);
        $.ajaxSetup({data: {csrf_test_name: result.token}});
    });
	// token end

	// $.post(CFG.url + 'Ajax/foo/', function(data) {
	//     console.log(data)
	// }, 'json');


	table = $('.table').DataTable({ 
		"searching": true,
		"destroy": true,
	    "processing": true, //Feature control the processing indicator.
	    "serverSide": true, //Feature control DataTables' server-side processing mode.
	    "order": [], //Initial no order.

	    // Load data for the table's content from an Ajax source
	    "ajax": {
	        // "url": "<?php echo site_url('Csv_import/load_datauno')?>",
	        'url': CFG.url+"Csv_import/load_data",
	        "type": "POST"
	    },

	    //Set column definition initialisation properties.
	    "columnDefs": [{ 
		        "targets": [ -1 ], //last column
		        "orderable": false, //set not orderable
		}],
    });

	$('#import_csv').on('submit', function(event){
		event.preventDefault();
		var formData = new FormData(this);
		formData.append('csrf_test_name', CFG.token);

		$.ajax({
			url:CFG.url+"Csv_import/import",
			method:"POST",
			data: formData,
			contentType:false,
			cache:false,
			processData:false,
			beforeSend:function(){
				$('#import_csv_btn').html('Importing...');
			},
			success:function(data)
			{
				var data = $.parseJSON(data);
				// switch(data.status) {
				// 	case "TRUE":
				// 		$('#import_csv')[0].reset();
				// 		$('#import_csv_btn').attr('disabled', false);
				// 		$('#import_csv_btn').html('Import Done');
				// 		reload_table();
				// 	break;
				// 	case "FALSE":
				// 		alert('Los datos no fueron guardados.')
				// 	break;
				// 	default:
				// 		alert('El alumno ya existe en la db.');
				// }
				if (data.status) {
					alert('Los datos fueron guardados.');
					$('#import_csv')[0].reset();
					$('#import_csv_btn').attr('disabled', false);
					$('#import_csv_btn').html('Import Done');
					reload_table();
				} else {
					alert('Los datos no fueron guardados.')
				}
			}
		});
	});


});

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}


