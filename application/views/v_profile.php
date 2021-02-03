<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">PLANTEL DOCENTE Y ADMINISTRATIVO</h5>
		<div class="heading-elements">
			<ul class="icons-list">
				<li><a data-action="collapse"></a></li>
				<li><a data-action="reload"></a></li>
				<!-- <li><a data-action="close"></a></li> -->
			</ul>
		</div>
		<button class="btn btn-success" onclick="add_teacher()"><i class="glyphicon glyphicon-plus"></i> Añadir Personas</button>
	</div>


	<div class="table-responsive">
		<table class="table datatable-basic table-striped">
			<thead>
				<tr>
					<th>Nombres</th>
					<th>Apellidos</th>
					<th>CI</th>
					<th>Expedido</th>
					<th>Cargo</th>
					<th>Email</th>
					<th>Acciones</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</div>

<div id="modal_confirmar" class="modal fade" >
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">×</button>
				<h5 class="modal-title">Vertical form</h5>
			</div>

			<?php echo form_open("#", array('id'=>'form_teacher', "method"=>"POST")); ?>
				<div class="modal-body">
					<div class="panel panel-body border-orange-600 border-lg">
						<legend>USUARIOS Y CONTRASEÑAS 
							<input type="checkbox" id="check-all" name="check-all" class="switchery">
						</legend>
						<div class="form-group">
							<div class="row">
								<input type="hidden" name="id_cuenta">
								<div class="col-sm-6">
									<label class="control-label text-semibold">Nombre de usuario:</label>
									<input type="text" placeholder="Ingres nombres" class="cuenta_personal form-control" name="username">
									<span class="help-block"></span>
								</div>
								<div class="col-sm-6">
									<label class="control-label text-semibold">Correo electrónico:</label>
									<input type="email" placeholder="Ingrese un email" class="cuenta_personal form-control" name="email">
									<span class="help-block"></span>
								</div>
								<div class="col-sm-6">
									<label class="control-label text-semibold">Contraseña: </label>
									<input type="password" placeholder="Contraseña" class="cuenta_personal form-control" name="password">
									<span class="help-block"></span>
								</div>
								<div class="col-sm-6">
									<label class="control-label text-semibold">Repetir contraseña: </label>
									<input type="password" placeholder="Repite la contraseña" class="cuenta_personal form-control" name="repeat_password">
									<span class="help-block"></span>
								</div>
							</div>
						</div>
					</div>
					<div class="panel panel-body border-orange-600 border-lg">
						<legend>Datos personales</legend>
						<div class="form-group">
							<div class="row">
								<input type="hidden" name="id_persona">
								<div class="col-sm-4">
									<label class="control-label text-semibold">Nombres</label>
									<input type="text" placeholder="Ingres nombres" class="form-control" name="nombres">
									<span class="help-block"></span>
								</div>
								<div class="col-sm-4">
									<label class="control-label text-semibold">Apellido Paterno</label>
									<input type="text" placeholder="ingrese apellido paterno" class="form-control" name="apellido_paterno">
									<span class="help-block"></span>
								</div>
								<div class="col-sm-4">
									<label class="control-label text-semibold">Apellido Materno</label>
									<input type="text" placeholder="Ingres apellido materno" class="form-control" name="apellido_materno">
									<span class="help-block"></span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-4">
									<label class="control-label text-semibold">Cédula de indentidad</label>
									<input type="text" placeholder="" class="form-control" name="ci" id="ci">
									<span class="help-block"></span>
								</div>
								<div class="col-sm-4">
									<label class="control-label text-semibold">Expedido</label>
									<select id="expedido" name="expedido" class="form-control">
										<option value=""></option>
										<option value="Beni">Beni</option>
										<option value="Chuquisaca">Chuquisaca</option>
										<option value="Cochabamba">Cochabamba</option>
										<option value="La Paz">La Paz</option>
										<option value="Oruro">Oruro</option>
										<option value="Pando">Pando</option>
										<option value="Potosi">Potosi</option>
										<option value="Santa">Santa Cruz</option>
										<option value="Tarija">Tarija</option>
						            </select>
									<span class="help-block"></span>
								</div>
								<div class="col-sm-4">
									<label class="control-label text-semibold">Sexo</label>
									<select id="sexo" name="sexo" class="form-control">
										<option value=""></option>
										<option value="Masculino">Masculino</option>
										<option value="Femenino">Femenino</option>
						            </select>
									<span class="help-block"></span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<!-- <div class="col-sm-4">
									<label class="control-label text-semibold">Email</label>
									<input type="email" placeholder="iteca@itecaamericano.com" class="form-control" name="email" id="email">
								</div> -->
								<div class="col-sm-4">
									<label class="control-label text-semibold">Estado civil</label>
									<select id="estado_civil" name="estado_civil" class="form-control">
										<option value=""></option>
										<option value="Casado">Casado</option>
										<option value="Divorciado">Divorciado</option>
										<option value="Soltero">Soltero</option>
						            </select>
						            <span class="help-block"></span>
								</div>

								<div class="col-sm-4">
									<label class="control-label text-semibold">fecha de nacimiento</label>
									<input type="text" placeholder="1985-12-30" class="form-control" name="fecha_nacimiento">
									<span class="help-block"></span>
								</div>

								
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-4">
									<label class="control-label text-semibold">Dirección</label>
									<input type="text" placeholder="1031" class="form-control" name="direccion">
								</div>
								<div class="col-sm-4">
									<label class="control-label text-semibold">Celular</label>
									<input type="text" placeholder="" class="form-control" name="celular">
									<!-- <span class="help-block">name@domain.com</span> -->
								</div>
								<div class="col-sm-4">
									<label class="control-label text-semibold">Teléfono fijo</label>
									<input type="text" placeholder="" data-mask="+99-99-9999-9999" class="form-control" name="telefono_fijo">
									<!-- <span class="help-block">+99-99-9999-9999</span> -->
								</div>
								
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-4">
									<label class="control-label text-semibold">Lugar de trabajo</label>
									<input type="text" placeholder="" class="form-control" name="lugar_trabajo">
									<span class="help-block"></span>
								</div>
								<div class="col-sm-4">
									<label class="control-label text-semibold">Dirección de trabajo</label>
									<input type="text" placeholder="" class="form-control" name="direccion_trabajo">
									<span class="help-block"></span>
								</div>
								<div class="col-sm-4">
									<label class="control-label text-semibold">Telefono del trabajo</label>
									<input type="text" placeholder="" class="form-control" name="telefono_trabajo">
									<span class="help-block"></span>
								</div>
							</div>
						</div>
					</div>
					<div class="panel panel-body border-orange-600 border-lg">
						<legend>Cargo y rol que cumple</legend>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-4">
									<label class="control-label text-semibold">Rol</label>
									<select id="rol" name="rol" class="form-control">
										<option value="">Seleccione...</option>
						            </select>
									<span class="help-block"></span>
								</div>
								<div class="col-sm-4">
									<label class="control-label text-semibold">Cargo</label>
									<select id="cargo" name="cargo" class="form-control">
										<option value="">Seleccione...</option>
						            </select>
									<span class="help-block"></span>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-link legitRipple" data-dismiss="modal">Close<span class="legitRipple-ripple" style="left: 45.1122%; top: 28.9474%; transform: translate3d(-50%, -50%, 0px); width: 222.472%; opacity: 0;"></span><span class="legitRipple-ripple" style="left: 37.4199%; top: 23.6842%; transform: translate3d(-50%, -50%, 0px); width: 222.472%; opacity: 0;"></span></button>
					<button type="submit" id="btnSave" onclick="save()" class="btn btn-primary legitRipple">Guardar</button>
				</div>
			</form>
		</div>
	</div>
</div>