<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">Bolet&iacute;n de calificaciones y Carnet de estudiante<a class="heading-elements-toggle"><i class="icon-more"></i></a></h5>
		<div class="heading-elements">
			<ul class="icons-list">
        		<li><a data-action="collapse"></a></li>
        		<li><a data-action="reload"></a></li>
        	</ul>
    	</div>
	</div>

	<div class="panel-body">
		<p class="content-group">Realice la busqueda mediante  <code>el n&uacute;mero de C.I. </code> del estudiante:</p>

		<div class="table-responsive">
			<table class="table table-bordered table-framed">
				<thead>
					<tr>
						<th>Apellido paterno</th>
						<th>Apellido Materno</th>
						<th>Nombres</th>
						<th>CI</th>
						<th>Expedido</th>
						<!-- <th>Fecha nacimiento</th> -->
						<th>Acciones</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div id="modal_confirmar" class="modal fade" role="dialog">
	<div class="modal-dialog modal-xs">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h5 class="modal-title">Seleccionar gestión</h5>
			</div>
			<legend></legend>
			
			<?php echo form_open("#", array('id'=>'form_boletin', "method"=>"POST", 'class'=>"form-horizontal")); ?>
			<!-- <form id="form_boletin" method="POST" class="form-horizontal"> -->
				<div class="modal-body">
					<input type="hidden" name="id_persona" id="id_persona" class="form-control">
	                <div class="form-group">
						<label class="control-label col-lg-5">Gestión: </label>
						<div class="col-lg-5">
							<select id="all_gestion" name="all_gestion" class="form-control"></select>
						</div>
					</div>
				</div>
			</form>
			<legend></legend>

			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Cerrar</button>
				<!-- <button type="button" class="btn btn-primary">Boletin</button> -->
				<button type="submit" id="btnSave" onclick="guardar()" class="btn btn-primary legitRipple">Boletín</button>
			</div>
		</div>
	</div>
</div>