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

    $('#form_password').hide();

    $("input").focus(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
});


function mostrar_oculatar() {
    
    $('#form_password').show();
    $('#boton_show_hidden').hide();
}

function btn_cancelar() {
    $('#boton_show_hidden').show();
    $('#form_password').hide();
    $('#form_password')[0].reset();
}

$("body").on("submit", "form#form_password", function(e){
    e.preventDefault();
    var formData = new FormData($('#form_password')[0]);
    formData.append('csrf_test_name', CFG.token);
    $.ajax({
        url: CFG.url + 'profile/ajax_update_pas',
        type: "POST",
        dataType: 'JSON',
        data: formData,
        contentType: false,
        processData: false,
        success: function(data) {
            if(data.status) //if success close modal and reload ajax table
            {
                $('#form_password')[0].reset(); // reset form on modals
                btn_cancelar();
                swal({
                    title: "Contraseña cambiada!",
                    text: "Se redireccionara al inicio de sesión!",
                    confirmButtonColor: "#66BB6A",
                    type: "success"
                },
                function() {
                    // setTimeout(function() {
                    //     swal({
                    //         title: "Ajax request finished!",
                    //         confirmButtonColor: "#2196F3"
                    //     });
                    // }, 2000);
                    $.get(CFG.url + 'login/logout', function(data) {
                        var tr = $.parseJSON(data);
                        window.location.replace(tr.miurl);
                    });
                });
            }
            else
            { 
                if (data.error == 'error') {
                    $('[name="old_password"]').parent().parent().addClass('has-error'); 
                    $('[name="old_password"]').next().text('La contraseña actual es incorrecta'); 
                } else {
                    for (var i = 0; i < data.inputerror.length; i++) 
                    {
                        $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                        $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                    }
                }
            }
            
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error al obtener los datos');
        }
    });
});


// $('#sweet_success').on('click', function() {
//     swal({
//         title: "Contraseña cambiada!",
//         text: "Se redireccionara al inicio de sesión!",
//         confirmButtonColor: "#66BB6A",
//         type: "success"
//     });
// });