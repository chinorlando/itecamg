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
    "processing": true, //Feature control the processing indicator.
    "serverSide": true, //Feature control DataTables' server-side processing mode.
    "order": [], //Initial no order.
    "lengthChange": true,
    // Load data for the table's content from an Ajax source
    "ajax": {
      "url": CFG.url+"admin/list_alumnos_nuevos",
      "type": "POST"
    },
    success: function(data){
      console.log(data);
    },
    //Set column definition initialisation properties.
    "columnDefs": [
        { 
            "targets": [ 0 ], //first column
            "orderable": false, //set not orderable
        },
        { 
            "targets": [ -1 ], //last column
            "orderable": false, //set not orderable
        },
      ],
  });

  $("#check-all").click(function () {
      $(".data-check").prop('checked', $(this).prop('checked'));
  });


});

function programar_alumno(id_alumno)
{
    if(confirm('Está seguro de programar al alumno?'))
    {
        // ajax delete data to database
        $.ajax({
            url : CFG.url+"admin/programar_al",
            type: "POST",
            data: {id:id_alumno},
            dataType: "JSON",
            success: function(data)
            {
                //if success reload ajax table
                // $('#modal_form').modal('hide');
                reload_table();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error al programar al alumno');
            }
        });

    }
}

function bulk_program_alumno()
{
    var list_id = [];
    $(".data-check:checked").each(function() {
      list_id.push(this.value);
    });
    if(list_id.length > 0)
    {
        if(confirm('Estas seguro de programar a '+list_id.length+' alumnos?'))
        {
            $.ajax({
                type: "POST",
                data: {id:list_id},
                url: CFG.url+'admin/programar_al',
                dataType: "JSON",
                success: function(data)
                {
                    if(data.status)
                    {
                        reload_table();
                    }
                    else
                    {
                        alert('Falló al programar alumnos.');
                    }
                    
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error al intentar programar alumnos');
                }
            });
        }
    }
    else
    {
        alert('No has seleccionado los campos.');
    }
}

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}