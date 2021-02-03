<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Boletin</title>

	<!-- Global stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url();?>assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url();?>assets/css/bootstrap.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url();?>assets/css/core.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url();?>assets/css/components.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url();?>assets/css/colors.css" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->

	<!-- Core JS files -->
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/loaders/pace.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/core/libraries/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/core/libraries/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/loaders/blockui.min.js"></script>
	<!-- /core JS files -->

	<!-- Theme JS files -->
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/forms/validation/validate.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/forms/styling/uniform.min.js"></script>

	<!-- <script type="text/javascript" src="<?php echo base_url();?>assets/js/core/app.js"></script> -->
	<!-- <script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/login_validation.js"></script> -->
	<!-- <script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/form_controls_extended.js"></script> -->
	<!-- <script type="text/javascript" src="<?php echo base_url();?>assets/js/core/libraries/jasny_bootstrap.min.js"></script> -->


	<!-- <script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/ui/ripple.min.js"></script> -->

	<!-- eso  -->
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/core/libraries/jasny_bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/forms/styling/uniform.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/forms/inputs/autosize.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/forms/inputs/formatter.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/forms/inputs/typeahead/typeahead.bundle.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/forms/inputs/typeahead/handlebars.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/forms/inputs/passy.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/forms/inputs/maxlength.min.js"></script>

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/core/app.js"></script>
	<!-- <script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/form_controls_extended.js"></script> -->
	<!-- <script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/login_validation.js"></script> -->

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/ui/ripple.min.js"></script>

</head>

<body class="login-container login-cover-mi-fondo">

	<div class="page-container">

		<div class="page-content">

			<div class="content-wrapper">

				<div class="content pb-20">

					<div class="panel panel-body login-form">
					
						<form class="form-validate" id="frm_boletin">
							<div class="text-center">
								<div class="icon-object border-slate-300 text-slate-300"><i class="icon-reading"></i></div>
								<h5 class="content-group">Busca tu boletin <small class="display-block">CI y fecha de nacimiento</small></h5>
							</div>

							<div class="form-group has-feedback has-feedback-left">
								<label>Carnet de identidad: </label>
								<input type="text" class="form-control" placeholder="CI" name="ci" required="required">
								<div class="form-control-feedback">
									<i class="icon-user text-muted"></i>
								</div>
								<span class="help-block"></span>
							</div>

							<div class="form-group has-feedback has-feedback-left">
								<label>Fecha de nacimiento: </label>
								<input type="text" class="form-control" placeholder="99-99-9999" name="dob" required="required">
								<div class="form-control-feedback">
									<i class="icon-calendar3 text-muted"></i>
								</div>
								<span class="help-block"></span>
							</div>
						</form>
						<div class="form-group">
							<button type="button" onclick="enviar()" class="btn bg-pink-400 btn-block">Buscar <i class="icon-arrow-right14 position-right"></i></button>
						</div>

						<div id="error"></div>
					</div>
				</div>
			</div>
		</div>
	</div>

</body>
</html>

<script type="text/javascript">
	
	$('[name="dob"]').formatter({
        pattern: '{{99}}-{{99}}-{{9999}}'
    });

	var CFG = {
		url: '<?php echo $this->config->item("base_url");?>',
		name: '<?php echo $this->security->get_csrf_token_name();?>',
		token: '<?php echo $this->security->get_csrf_hash();?>'
	};

	$("input").change(function(){
        $(this).parent().removeClass('has-error');
        $(this).next().next().empty();
        // $('#error').remove();
    });

	$("input[type=text]").focus(function(){	   
		this.select();
	});

	function enviar() {
		var formData = new FormData($('#frm_boletin')[0]);
		formData.append('csrf_test_name', CFG.token);

		$.ajax({
			url: CFG.url +'busqueda',
			type: "POST",
			dataType: 'JSON',
			data: formData,
			contentType: false,
			processData: false,
			success: function(data){
				if (data.status) {
					$('#modal_large').modal('show');
					$('#boletin').html(data.lib);
					$('#error').hide();
				} else if (data.error) {
					$('#error').show();
					$('#error').html('<div class="form-group"><div class="alert alert-danger text-center" role="alert">'+data.msg+'</div></div>');
				} else {
					for (var i = 0; i < data.inputerror.length; i++) 
					{
						$('[name="'+data.inputerror[i]+'"]').parent().addClass('has-error');
						$('[name="'+data.inputerror[i]+'"]').next().next().text(data.error_string[i]);
					}
				}
			},
			error: function(){
			}
		});
			
		
	}
</script>



<div id="modal_large" class="modal fade">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h5 class="modal-title">Boletin de notas</h5>
			</div>

			<div class="modal-body">
				<div id="boletin"></div>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>