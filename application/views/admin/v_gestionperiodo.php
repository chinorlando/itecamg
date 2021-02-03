<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">GESTIÓN Y PERIODOS</h5>
		<div class="heading-elements">
			<ul class="icons-list">
				<li><a data-action="collapse"></a></li>
				<li><a data-action="reload"></a></li>
				<!-- <li><a data-action="close"></a></li> -->
			</ul>
		</div>
		<button class="btn btn-success" onclick="add_gestionperiodo()"><i class="glyphicon glyphicon-plus"></i> Añadir gestion / periodo</button>
	</div>


	<div class="table-responsive">
		<table class="table datatable-basic table-striped">
			<thead>
				<tr>
					<th>Gestión</th>
					<th>Periodo</th>
					<th>Tipo</th>
					<th>Estado</th>
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
			<div class="panel panel-flat">
				<div class="panel-heading">
					<!-- <h5 class="panel-title">Input group addons<a class="heading-elements-toggle"><i class="icon-more"></i></a></h5> -->
					<div class="heading-elements">
						<ul class="icons-list">
	                		<li><a data-action="collapse"></a></li>
	                		<li><a data-action="reload"></a></li>
	                	</ul>
                	</div>
				</div>

				<div class="panel-body">
					<!-- <form class="form-horizontal" action="#"> -->
					<?php echo form_open("#", array('id'=>'form_gestionperiodo', "method"=>"POST", 'class'=>"form-horizontal")); ?>
						<fieldset class="content-group">
							<legend class="text-bold">Creación de gestion y periodo</legend>

							<div class="form-group">
								<input  type="hidden" name="id_gestionperiodo">

								<label class="control-label col-lg-2">Gestión</label>
								<div class="col-lg-10">
									<div class="input-group">
										<!-- <span class="input-group-addon">@</span> -->
										<input type="text" placeholder="Ingrese gestión" class="form-control" name="gestion" value="<?php echo date('Y') ?>">
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-lg-2">Perido</label>
								<div class="col-lg-10">
									<div class="input-group">
										<input type="text" placeholder="Ingrese periodo" class="form-control" name="periodo">
										<!-- <span class="input-group-addon">%</span> -->
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-lg-2">Activo/descativado</label>
								<div class="col-lg-10">
									<div class="input-group">
										<!-- <span class="input-group-addon">$</span> -->
										<!-- <input type="text" class="form-control" placeholder="Left and right addons"> -->
										<div class="form-group">
										<div class="radio">
											<label>
												<input type="checkbox" name="estado" id="estado">
												Activar
											</label>
										</div>

										<!-- <div class="radio">
											<label>
												<input type="radio" value="N" name="estado" id="estado">
												Desactivar
											</label>
										</div> -->
									</div>
										<!-- <span class="input-group-addon">.00</span> -->
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-lg-2">Tipo de perido</label>
								<div class="col-lg-10">
									<div class="input-group">
										<!-- <input type="text" class="form-control" placeholder="Right addon"> -->
										<select id="tipogestion" name="tipogestion" class="form-control">
											<option value="">Seleccione...</option>
							            </select>
										<!-- <span class="input-group-addon">%</span> -->
									</div>
								</div>
							</div>
						</fieldset>
					</form>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-link legitRipple" data-dismiss="modal">Cerrar<span class="legitRipple-ripple" style="left: 45.1122%; top: 28.9474%; transform: translate3d(-50%, -50%, 0px); width: 222.472%; opacity: 0;"></span><span class="legitRipple-ripple" style="left: 37.4199%; top: 23.6842%; transform: translate3d(-50%, -50%, 0px); width: 222.472%; opacity: 0;"></span></button>
				<button type="submit" id="btnSave" onclick="save()" class="btn btn-primary legitRipple">Guardar</button>
			</div>
		</div>
	</div>
</div>