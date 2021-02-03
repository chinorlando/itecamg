<?php if (validation_errors()) : ?>
	<div class="col-md-12">
		<div class="alert alert-danger" role="alert">
			<?= validation_errors() ?>
		</div>
	</div>
<?php endif; ?>
<?php if (isset($error)) : ?>
	<div class="col-md-12">
		<div class="alert alert-danger" role="alert">
			<?= $error ?>
		</div>
	</div>
<?php endif; ?>

<?php if (isset($success)) : ?>
	<div class="col-md-12">
		<div class="alert alert-success" role="alert">
			<?= $success ?>
		</div>
	</div>
<?php endif; ?>

<?php if ($this->session->mark_as_flash('flash')): ?>
	<div class="col-md-12">
		<div class="alert alert-success" role="alert">
			<?php echo $_SESSION['flash']; ?>
		</div>
	</div>
<?php endif ?>

<?= form_open() ?>
	<div class="row">
		<div class="col-lg-12 col-lg-offset">
			<div class="panel registration-form">
				<div class="panel-body">
					<div class="text-center">
						<div class="icon-object border-success text-success"><i class="icon-plus3"></i></div>
						<h5 class="content-group-lg">Create cuenta <small class="display-block">Todos los campos son obligatorios</small></h5>
					</div>
					<div class="form-group has-feedback">
						<input type="text" name="username" id="username" class="form-control" placeholder="Elegir nombre de usuario">
						<div class="form-control-feedback">
							<i class="icon-user-plus text-muted"></i>
						</div>
					</div>
<!-- 					<div class="row">
						<div class="col-md-6">
							<div class="form-group has-feedback">
								<input type="text" name="nombres" id="nombres" class="form-control" placeholder="Nombres">
								<div class="form-control-feedback">
									<i class="icon-user-check text-muted"></i>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group has-feedback">
								<input type="text" name="apellidos" id="apellidos" class="form-control" placeholder="Apellidos">
								<div class="form-control-feedback">
									<i class="icon-user-check text-muted"></i>
								</div>
							</div>
						</div>
					</div> -->
					<div class="row">
						<div class="col-md-6">
							<div class="form-group has-feedback">
								<input type="email" name="email" id="email" class="form-control" placeholder="Correo electrónico">
								<div class="form-control-feedback">
									<i class="icon-mention text-muted"></i>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group has-feedback">
								<input type="email" name="email_confirm" id="email_confirm" class="form-control" placeholder="Confirmar correo electrónico">
								<div class="form-control-feedback">
									<i class="icon-mention text-muted"></i>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group has-feedback">
								<input type="password" name="password" id="password" class="form-control" placeholder="Contraseña">
								<div class="form-control-feedback">
									<i class="icon-user-lock text-muted"></i>
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group has-feedback">
								<input type="password" name="password_confirm" id="password_confirm" class="form-control" placeholder="Confirmar contraseña">
								<div class="form-control-feedback">
									<i class="icon-user-lock text-muted"></i>
								</div>
							</div>
						</div>
					</div>
					<div class="text-right">
						<!-- <button type="submit" class="btn btn-link legitRipple"><i class="icon-arrow-left13 position-left"></i> Back to login form</button> -->
						<button type="submit" class="btn bg-teal-400 btn-labeled btn-labeled-right ml-10 legitRipple"><b><i class="icon-plus3"></i></b> Crear cuenta</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>