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

	$.get(CFG.url+"admin/retirados", function(data) {
		var tr = $.parseJSON(data);
		console.log(tr);
		$('#programarme_tbody').html(tr.lib);
	});

});