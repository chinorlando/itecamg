
 
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">Striped rows</h5>
		<div class="heading-elements">
			<ul class="icons-list">
				<li><a data-action="collapse"></a></li>
				<li><a data-action="reload"></a></li>
				<li><a data-action="close"></a></li>
			</ul>
		</div>
	</div>


	<div class="table-responsive">
		<table class="table datatable-basic table-striped">
			<thead>
				<tr>
					<th>Nombres</th>
					<th>Ape. Paterno</th>
					<th>Ape. Materno</th>
					<th>ci</th>
					<th>expedido</th>
					<th>email</th>
					<th>sexo</th>
					<!-- <th>fecha nacimiento</th>
					<th>direccion</th> -->
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</div>

 


 <div class="modal fade" id="modal_confirmar" role="dialog">
	<div class="modal-dialog">
		<div class="sweet-alert showSweetAlert visible" data-custom-class="" data-has-cancel-button="true" data-has-confirm-button="true" data-allow-outside-click="false" data-has-done-function="false" data-animation="pop" data-timer="null" style="display: block; margin-top: -5px;"><div class="sa-icon sa-error" style="display: none;">
			<span class="sa-x-mark">
				<span class="sa-line sa-left"></span>
				<span class="sa-line sa-right"></span>
			</span>
		</div><div class="sa-icon sa-warning pulseWarning" style="display: block;">
			<span class="sa-body pulseWarningIns"></span>
			<span class="sa-dot pulseWarningIns"></span>
		</div><div class="sa-icon sa-info" style="display: none;"></div><div class="sa-icon sa-success" style="display: none;">
			<span class="sa-line sa-tip"></span>
			<span class="sa-line sa-long"></span>

			<div class="sa-placeholder"></div>
			<div class="sa-fix"></div>
		</div><div class="sa-icon sa-custom" style="display: none;"></div><h2>Confirmar matriculación</h2>
		<!-- <p style="display: block;">De enviar el reclamo!</p> -->
		<input type="hidden" id="id_alumno">
		
		<fieldset>
			<input tabindex="3" placeholder="" type="text">
			<div class="sa-input-error"></div>
		</fieldset><div class="sa-error-container">
			<div class="icon">!</div>
			<p>Not valid!</p>
		</div><div class="sa-button-container">
			<button class="cancel" tabindex="2" style="display: inline-block; box-shadow: none;" data-dismiss="modal">Cancelar</button>
			<div class="sa-confirm-button-container">
				<button class="confirm" tabindex="1" style="display: inline-block; background-color: rgb(255, 112, 67); box-shadow: rgba(255, 112, 67, 0.8) 0px 0px 2px, rgba(0, 0, 0, 0.05) 0px 0px 0px 1px inset;" onclick="guardar_matriculacion()">Confirmar</button><div class="la-ball-fall">
					<div></div>
					<div></div>
					<div></div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>