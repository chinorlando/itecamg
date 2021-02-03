
$(document).ready(function(){
	// token begin
	$.ajaxSetup({data: {csrf_test_name: CFG.token}});
    $(document).ajaxSuccess(function(e,x) {
        var result = $.parseJSON(x.responseText);
        $('input:hidden[name="csrf_test_name"]').val(CFG.token);
        $.ajaxSetup({data: {csrf_test_name: result.token}});
    });
	// token end

	// $.post(CFG.url + 'Ajax/foo/', function(data) {
	//     console.log(data)
	// }, 'json');

	table = $('.table').DataTable({ 
		"paging": false,
		"searching": false,
		"destroy": true,
	    "processing": false, //Feature control the processing indicator.
	    "serverSide": true, //Feature control DataTables' server-side processing mode.
	    "order": [], //Initial no order.

	    // Load data for the table's content from an Ajax source
	    "ajax": {
	        // "url": "<?php echo site_url('Csv_import/load_datauno')?>",
	        'url': CFG.url+"alumno/list_kardex",
	        "type": "POST"
	    },

	    //Set column definition initialisation properties.
	    "columnDefs": [{ 
		        "targets": [ -1 ], //last column
		        "orderable": false, //set not orderable
		}],
		"buttons": [{
            "extend": 'print',
            "text": '<i class="icon-printer position-left"></i> Imprimir Kardex',
            "className": 'btn bg-blue'
        }],
    });	




});