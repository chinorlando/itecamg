<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">ASIGNACIÓN DE MENÚS A LOS DOCENTES</h5>
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

			<?php echo form_open("#", array('id'=>'form_teacher_menu', "method"=>"POST")); ?>
				<div class="modal-body">
					<legend>Datos personales</legend>
					<div class="form-group">
						<input type="hidden" name="id_persona" id="id_persona">
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
					<button type="submit" id="btnSave" onclick="save_menu()" class="btn btn-primary legitRipple">Guardar menu</button>
				</div>
		</div>
	</div>
</div>