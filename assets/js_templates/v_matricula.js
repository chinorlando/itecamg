
$(document).ready(function(){



});

function add_matricula(id_alumno)
    {
       // alert(id_alumno);
        $('#form')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        $('[name="id_alumno"]').val(id_alumno);
        
        $('#modal_form').modal('show'); // show bootstrap modal
        $('.modal-title').text('Matriculacion'); // Set Title to Bootstrap modal title

    }  

function save_matricula()
    {
        $('#btnSave').text('guardando...'); //change button text
        $('#btnSave').attr('disabled',true); //set button disable
  
    
          $.ajax({
            url : CFG.url+"matricular/ajax_add_matricula",
            type: "POST",
            data: $('#form').serialize(),
            dataType: "JSON",
            success: function(data){
              if(data.status) //if success close modal and reload ajax table
              {
                $('#modal_form').modal('hide');
                $('#btnMatricula').attr('disabled',true); //set button disable
               } else {
                for (var i = 0; i < data.inputerror.length; i++) {
                  $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                  $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                }
              }
              $('#btnSave').text('save'); //change button text
              $('#btnSave').attr('disabled',false); //set button enable 
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
              alert('Error adding / update data');
              $('#btnSave').text('save'); //change button text
              $('#btnSave').attr('disabled',false); //set button enable 
            }
          });

        
        
    }
 


