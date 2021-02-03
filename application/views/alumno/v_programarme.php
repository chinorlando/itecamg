<!-- <form id="form_programacion"> -->
<?php echo form_open("#", array('id'=>'form_programacion', 'method'=>'POST')); ?>
	<!-- <div class="panel panel-flat">
		<div class="panel-body">
			<div class="form-group">
				<h5 class="panel-title">Seleccione un paralelo<a class="heading-elements-toggle"><i class="icon-more"></i></a></h5>
				<select id="paralelo" name="paralelo" class="form-control">
					<option value="-1">Seleccione...</option>
	             </select>
			</div>
		</div>
	</div> -->

	<div class="panel panel-flat">
		<!-- <div class="panel-body"> -->
			
				<div class="table-responsive">
					<table id="table" class="table">
						<thead>
							<tr class="bg-success">
								<th rowspan="2">#</th>
								<th rowspan="2">Sigla</th>
								<th rowspan="2">Asignatura</th>
								<th rowspan="2">Curso</th>
								<th rowspan="2">Paralelo</th>
								<!-- <th colspan="1">Cabecera Múltiples Columnas</th>
								<th colspan="1">Cabecera Múltiples Columnas</th> -->
								<th colspan="1" scope="col">Programarse</th>
								<th colspan="1" scope="col">Confirmar</th>
							</tr>
							<tr class="bg-success">
								<th scope="col"><input class="switchery" type="checkbox" id="check-all-programarse"></th>
								<th scope="col"><input class="switchery" type="checkbox" id="check-all-confirmar"></th>
							</tr>
						</thead>
						<tbody id="programarme_tbody">
							<!-- <tr>
								<td>1</td>
								<td>Matematicas</td>
								<td><input type="checkbox" name=id_materia value="1"></td>
								<td><input type="checkbox" name=confirmar value="1"></td>
							</tr> -->
						</tbody>
					</table>
				</div>
				
			
		<!-- </div> -->
	</div>

	<div class="text-center">
	    <button type="submit" class="btn btn-danger legitRipple">Cancelar</button>
		<button id="btnSaveProgramacion" type="submit" class="btn btn-primary legitRipple">Enviar</button>
	</div>
</form>

<!-- <script type="text/javascript">
    $(document).ready(function() {
      // table = $('#table').DataTable();
      var table = $('#table').DataTable();
 
		$('#table tbody').on( 'click', 'td', function () {
		    alert( table.cell( this ).data() );
		} );

	});
</script> -->

	