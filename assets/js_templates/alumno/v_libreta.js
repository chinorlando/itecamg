
$(document).ready(function(){
	// token begin
	// $.ajaxSetup({
	// 	data: {
	// 		'csrf_test_name': CFG.token
	// 	}
	// });

	// y esta es otra manera
	$.ajaxSetup({data: {csrf_test_name: CFG.token}});
    $(document).ajaxSuccess(function(e,x) {
        var result = $.parseJSON(x.responseText);
        $('input:hidden[name="csrf_test_name"]').val(result.token);
        // console.log($('input:hidden[name="csrf_test_name"]').val());
        $.ajaxSetup({data: {csrf_test_name: result.token}});
    });
	// token end

	$.post(CFG.url + 'Ajax/foo/', function(data) {
	    console.log(data);
	}, 'json');

	$.get(CFG.url + 'alumno/get_gestionperiodo', function(data) {
		var tr = $.parseJSON(data);
		console.log(tr);
		$.each(tr, function(index, val) {
			$('#gestionperiodo').append('<option value="'+val.id_gestionperiodo+'">'+val.gestion +'</option>');
		});
	});


	



});

$('#gestionperiodo').change(function(e) {
	var gestion = $('#gestionperiodo').val();
	$.post(CFG.url+'alumno/notas',
		{gestion: gestion}, 
		function(data, textStatus, xhr) {
			var tr = $.parseJSON(data);
			$('#libreta_tbody').html(tr.lib);
		}
	)
});