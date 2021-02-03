<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">HABILITAR PARALELOS A CADA MATERIA</h5>
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
					<th>Curso</th>
					<th>Materia</th>
					<th>Paralelos</th>
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
				<h5 class="modal-title">PARALELOS</h5>
			</div>
			<input class="switchery check-all-paralelos" type="checkbox">
			<?php echo form_open("#", array('id'=>'form_parallel', "method"=>"POST")); ?>
				<div class="modal-body">
					<legend></legend>
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
					<button type="submit" id="btnSave" onclick="save_parallels()" class="btn btn-primary legitRipple">Guardar asignación</button>
				</div>
		</div>
	</div>
</div>