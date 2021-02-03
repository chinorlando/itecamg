<div class="panel panel-flat">
	<div class="table-responsive">
		<table class="table table-lg">
			<tbody>
				<tr>
					<th colspan="4" class="active">DATOS PERSONALES</th>
				</tr>
				<tr>
					<td>Nombres:</td>
					<td class="text-slate-800 text-bold"><?php echo $person->nombres; ?></td>
					<td>Apellidos:</td>
					<td class="text-slate-800 text-bold"><?php echo $person->apellido_paterno.' '.$person->apellido_materno; ?></td>
				</tr>
				<tr>
					<td>Cedula de Identidad: </td>
					<td class="text-slate-800 text-bold"><?php echo $person->ci.' '.$person->expedido; ?></td>
					<td>Fecha de nacimiento: </td>
					<td class="text-slate-800 text-bold"><?php echo $person->fecha_nacimiento; ?></td>
				</tr>
				<tr>
				</tr>
				<tr>
					<td>Direccion: </td>
					<td class="text-slate-800 text-bold"><?php echo $person->direccion; ?></td>
					<td>Celular: </td>
					<td class="text-slate-800 text-bold"><?php echo $person->celular; ?></td>
				</tr>

				<tr class="border-double">
				<!-- <tr> -->
					<th colspan="4" class="active">CARGO Y ROL QUE CUMPLE</th>
				</tr>
				<tr>
					<td>Cargo: </td>
					<td class="text-slate-800 text-bold"><?php echo $person->nombre_cargo; ?></td>
					<td>Rol: </td>
					<td class="text-slate-800 text-bold"><?php echo $person->nombre; ?></td>
				</tr>

				<!-- <tr class="border-double">
					<th colspan="4" class="active">CONTRASEÑA:</th>
				</tr> -->

				<!-- <tr>
					<td>Contraseña antigua: </td>
					<td class="text-primary">
						<div class="col-lg-12">
							<input type="text" class="form-control">
						</div>
					</td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td>Contraseña nueva: </td>
					<td class="text-primary">
						<div class="col-lg-12">
							<input type="text" class="form-control">
						</div>
					</td>
					<td>Repetir la contraseña:</td>
					<td class="text-primary">
						<div class="col-lg-12">
							<input type="text" class="form-control">
						</div>
					</td>
				</tr> -->
			</tbody>
		</table>
	</div>
</div>



<div class="panel panel-flat">
	<div class="panel-body">
		<form id="form_password" class="form-horizontal form-validate-jquery" action="#" novalidate="novalidate" method="post">
			<legend class="text-bold">FORMULARIO PARA CAMBIAR CONTRASEÑA</legend>

		    <div class="alert alert-warning alert-styled-left">
				<button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
				<span class="text-semibold">Aviso!</span> Debe recordar y no compartir su contraseña con ninguna persona.
		    </div>
	    
			<div class="form-group">
				<div class="col-md-6">
					<div class="row">
						<label class="col-md-4 control-label">Contraseña actual: </label>
						<div class="col-md-8">
							<input id="old_password" name="old_password" type="password" class="form-control" placeholder="Contraseña actual">
							<span class="help-block"></span>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-6">
					<div class="row">
						<label class="col-md-4 control-label">Nueva contraseña: </label>
						<div class="col-md-8">
							<input id="password" name="password" type="password" class="form-control" placeholder="Nueva contraseña">
							<span class="help-block"></span>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="row">
						<label class="col-md-4 control-label">Repetir nueva contraseña: </label>
						<div class="col-md-8">
							<input id="repeat_password" name="repeat_password" type="password" class="form-control" placeholder="Repetir contraseña nueva">
							<span class="help-block"></span>
						</div>
					</div>
				</div>
			</div>

			<div class="text-center">
				<button type="button" class="btn btn-link legitRipple" onclick="btn_cancelar()" data-dismiss="modal">Cancelar <i class="icon-eye-blocked2 position-right"></i><span class="legitRipple-ripple" style="left: 45.1122%; top: 28.9474%; transform: translate3d(-50%, -50%, 0px); width: 222.472%; opacity: 0;"></span><span class="legitRipple-ripple" style="left: 37.4199%; top: 23.6842%; transform: translate3d(-50%, -50%, 0px); width: 222.472%; opacity: 0;"></span><span class="legitRipple-ripple" style="left: 51.5625%; top: 34.2105%; transform: translate3d(-50%, -50%, 0px); width: 222.472%; opacity: 0;"></span></button>
				<button type="submit" class="btn text-success-800 border-success btn-flat legitRipple">Cambiar contraseña <i class="icon-reset position-right"></i></button>
			</div>
		</form>

		<div class="text-center" id="boton_show_hidden">
			<button type="submit" onclick="mostrar_oculatar()" class="btn btn-primary legitRipple">Mostrar campos <i class="icon-eye2 position-right"></i></button>
		</div>
	</div>
</div>

