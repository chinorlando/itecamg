<?php if ($vacio == true): ?>
	<div class="alert bg-info alert-styled-left">
		<!-- <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Cerrar</span></button> -->
		<span class="text-semibold"> <h3><?php echo $mensajevacio;?></h3>  </span>
	</div>
<?php else: ?>
	



	<div class="page-header page-header-default">
		<div class="page-header-content">
			<div class="page-title">
				<h1>
					<!-- <i class="icon-arrow-left52 position-left"></i> -->
					Carrera: 
					<span class="text-bold"><?php echo $nombre_carrera ?></span> - Paralelo: <?php echo $nombre_paralelo ?></h1>
			<a class="heading-elements-toggle"><i class="icon-more"></i></a></div>
		</div>
	</div>

	<div class="row">
		<!-- <?php echo $materias[0]['id_materia']; ?> -->
		<?php foreach($materias as $u){?>

		<div class="col-md-6">
			<div class="panel panel-flat">
				<div class="panel-heading">
					<!-- <h5 class="panel-title">Horizontal form<a class="heading-elements-toggle"><i class="icon-more"></i></a></h5>
					<div class="heading-elements">
						<ul class="icons-list">
	                		<li><a data-action="collapse"></a></li>
	                		<li><a data-action="close"></a></li>
	                	</ul>
	            	</div> -->
	        	</div>

				<div class="panel-body">
					<div id="chartContainer<?php echo $u['id_materia']; ?>" style="height: 370px; width: 100%;"></div>
				</div>
			</div>

		</div>
		<?php } ?>
	</div>






	<script>
		window.onload = function () {
		<?php $i=0; ?>

		<?php foreach($materias as $u){?>
			<?php $i++; ?>
			var chart<?php echo $u['id_materia'] ?> = new CanvasJS.Chart("chartContainer<?php echo $u['id_materia'] ?>", {
				animationEnabled: true,
				theme: "light2",
				title:{
					text: <?php echo json_encode($u['nombre_materia']); ?>+' ('+<?php echo json_encode($u['sigla']); ?>+') ' +<?php echo json_encode($u['docente']); ?>,
				},
				legend:{
					cursor: "pointer",
					verticalAlign: "bottom",
					horizontalAlign: "center",
					itemclick: toggleDataSeries<?php echo $u['id_materia'] ?>
				},
				data: [{
					type: "column",
					click: onClick<?php echo $u['id_materia'];?>,
					name: "Aprobados",
					indexLabel: "{y}",
					yValueFormatString: "#0.##",
					showInLegend: true,
					dataPoints: <?php echo json_encode($dataPoints1[$u['id_materia']]); ?>
				},{
					type: "column",
					click: onClick<?php echo $u['id_materia'];?> ,
					name: "Reprobados",
					indexLabel: "{y}",
					yValueFormatString: "#0.##",
					showInLegend: true,
					dataPoints: <?php echo json_encode($dataPoints2[$u['id_materia']]); ?>
				}]
			});
			chart<?php echo $u['id_materia'] ?>.render();
			function toggleDataSeries<?php echo $u['id_materia'] ?>(e){
				if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
					e.dataSeries.visible = false;
				}
				else{
					e.dataSeries.visible = true;
				}
				chart<?php echo $u['id_materia'] ?>.render();
			}
			function onClick<?php echo $u['id_materia']; ?> (e) {
				// $('#modal_large').modal('show'); // show bootstrap modal when complete loaded
				$('#modal_confirmar').modal('show'); // show bootstrap modal when complete loaded

		 	$.ajax({
				url: CFG.url + 'admin/list_alumnos_aprobados_reprobados',
				type: "POST",
				cache: true,
				data: {
					id_materia: <?php echo $u['id_materia']; ?>,
					paralelo: "<?php echo $nombre_paralelo; ?>",
					bim: e.dataPoint.x,
					a_r: e.dataPoint.a_r,
					csrf_test_name: CFG.token,
				},
				success: function(data) {
					var tr = $.parseJSON(data);
					$('.titulo').text('Lista de alumnos '+tr.estado);
					$('.materia').text('Materia: '+tr.materia);
					$('.paralelo').text('Paralelo: '+tr.paralelo);
					$('.bimestre').text(tr.bim + 'º bimestre');
					$('#alumnos_aprobados').html(tr.note);
				},
				error: function (jqXHR, textStatus, errorThrown)
				{
				    alert('Error al mostrar datos');
				}
			});




			}
		<?php } ?>
		}
	</script>


	<div id="modal_confirmar" class="modal fade" >
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">×</button>
					<h3 class="modal-title titulo">Basic modal</h3>
				</div>

				<div class="modal-body">
					<h5 class="materia"><a class="heading-elements-toggle"><i class="icon-more"></i></a></h5>
					<h5 class="paralelo"><a class="heading-elements-toggle"><i class="icon-more"></i></a></h5>
					<div class="table-responsive">
						<table class="table table-xxs">
							<thead>
								<tr>
									<th>#</th>
									<th>Nombres</th>
									<th class="bimestre"></th>
								</tr>
							</thead>
							<tbody id="alumnos_aprobados">
							</tbody>
						</table>
					</div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-link legitRipple" data-dismiss="modal">Cerrar<span class="legitRipple-ripple" style="left: 45.1122%; top: 28.9474%; transform: translate3d(-50%, -50%, 0px); width: 222.472%; opacity: 0;"></span><span class="legitRipple-ripple" style="left: 37.4199%; top: 23.6842%; transform: translate3d(-50%, -50%, 0px); width: 222.472%; opacity: 0;"></span></button>
					<!-- <button type="submit" id="btnSave" onclick="save()" class="btn btn-primary legitRipple"><i class="icon-printer2"></i> Imprimir</button> -->
				</div>
			</div>
		</div>
	</div>

<?php endif ?>