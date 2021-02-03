<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">Importar estudiantes desde un archivo CSV</h5>
	</div>
	<div class="panel-body">
		<!-- <form role="form" method="POST" id="import_csv" enctype="multipart/form-data"> -->
		<?php echo form_open("#", array('id'=>'import_csv', "method"=>"POST")); ?>
			<div class="panel panel-flat">
				<div class="panel-body">
					<div class="form-group">
						<label class="col-lg-12 control-label">Archivo de Pre-Inscripción</label>
						<div class="col-lg-9">
							<input type="file" name="csv_file" id="csv_file" required accept=".csv" class="file-styled">
							<span class="help-block">Formato en .csv</span>
						</div>
					</div>
					<div class="text-left">
						<button type="submit" name="import_csv" id="import_csv_btn" class="btn btn-primary">importar Archivo <i class="icon-arrow-right14 position-right"></i></button>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

 
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
					<th>fecha nacimiento</th>
					<th>direccion</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</div>

 
<!-- 

<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">Basic table</h5>
		<div class="heading-elements">
			<ul class="icons-list">
        		<li><a data-action="collapse"></a></li>
        		<li><a data-action="close"></a></li>
        	</ul>
    	</div>
	</div>

	<div class="table-responsive">
		<table id="table" class="table cell-border compact stripe">
			<thead>
				<tr>
					<th>#</th>
					<th>First Name</th>
					<th>Last Name</th>
					<th>Username</th>
					<th>Username</th>
					<th>Username</th>
					<th>Username</th>
					<th>Username</th>
					<th>Username</th>
					<th>Username</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>1</td>
					<td>Eugene</td>
					<td>Kopyov</td>
					<td>@Kopyov</td>
				</tr>
				<tr>
					<td>2</td>
					<td>Victoria</td>
					<td>Baker</td>
					<td>@Vicky</td>
				</tr>
				<tr>
					<td>3</td>
					<td>James</td>
					<td>Alexander</td>
					<td>@Alex</td>
				</tr>
				<tr>
					<td>4</td>
					<td>Franklin</td>
					<td>Morrison</td>
					<td>@Frank</td>
				</tr>
			</tbody>
		</table>
	</div>
</div> -->