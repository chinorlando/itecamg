<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">ASIGNACIÓN DE DOCENTES A CADA PARALELO DE MATERIA</h5>
		<div class="heading-elements">
			<ul class="icons-list">
				<li><a data-action="collapse"></a></li>
				<li><a data-action="reload"></a></li>
				<!-- <li><a data-action="close"></a></li> -->
			</ul>
		</div>
	</div>


	<div class="table-responsive">
		<table class="table datatable-basic table-striped">
			<thead>
				<tr>
					<th>Plan</th>
					<th>Carrera</th>
					<th>Sigla</th>
					<th>Materia</th>
					<th>Curso</th>
					<th>Paralelo</th>
					<th>Docente</th>
					<th>Acciones</th>
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
		</div><div class="sa-icon sa-custom" style="display: none;"></div><h2>Confirmar</h2>
		<!-- <p style="display: block;">De enviar el reclamo!</p> -->
		<?php echo form_open("#", array('id'=>'form_parallel', "method"=>"POST")); ?>
			<input type="hidden" name="indice" id="indice">
			<input type="hidden" name="id_pensum" id="id_pensum">
			<!-- <input type="text" name="id_docentes" id="id_docentes"> -->
			<input type="hidden" name="id_paralelo" id="id_paralelo">
			<input type="hidden" name="existe" id="existe">
		</form>
		
		<fieldset>
			<input tabindex="3" placeholder="" type="text">
			<div class="sa-input-error"></div>
		</fieldset><div class="sa-error-container">
			<div class="icon">!</div>
			<p>Not valid!</p>
		</div><div class="sa-button-container">
			<button class="cancel" tabindex="2" style="display: inline-block; box-shadow: none;" data-dismiss="modal">Cancelar</button>
			<div class="sa-confirm-button-container">
				<button class="confirm" tabindex="1" style="display: inline-block; background-color: rgb(255, 112, 67); box-shadow: rgba(255, 112, 67, 0.8) 0px 0px 2px, rgba(0, 0, 0, 0.05) 0px 0px 0px 1px inset;" onclick="guardar_asignacion()">Confirmar</button><div class="la-ball-fall">
					<div></div>
					<div></div>
					<div></div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>

<!-- 


<div id="modal_confirmar" class="modal fade" >
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">×</button>
				<h5 class="modal-title">Vertical form</h5>
			</div>

			<?php echo form_open("#", array('id'=>'form_parallel', "method"=>"POST")); ?>
				<div class="modal-body">
					<legend>Datos personales</legend>
					<div class="form-group">
						<input type="hidden" name="id_materia" id="id_materia">
						<div class="row">
							<div class="col-sm-4">
								<div id='opciones'></div>
					        </div>
						</div>
					</div>
				</div>
			</form>

				<div class="modal-footer">
					<button type="button" class="btn btn-link legitRipple" data-dismiss="modal">Close<span class="legitRipple-ripple" style="left: 45.1122%; top: 28.9474%; transform: translate3d(-50%, -50%, 0px); width: 222.472%; opacity: 0;"></span><span class="legitRipple-ripple" style="left: 37.4199%; top: 23.6842%; transform: translate3d(-50%, -50%, 0px); width: 222.472%; opacity: 0;"></span></button>
					<button type="submit" id="btnSave" onclick="save_parallels()" class="btn btn-primary legitRipple">Guardar menu</button>
				</div>
		</div>
	</div>
</div> -->