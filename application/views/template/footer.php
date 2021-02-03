
					<!-- aqui el contenido -->

					<!-- Footer -->
					<div class="footer text-muted">
						&copy; 2019. <a href="#">Servicios Academicos </a> por <a href="#" target="_blank">ITECA</a>
					</div>
					<!-- /footer -->

				</div>
				<!-- /content area -->

			</div>
			<!-- /main content -->

		</div>
		<!-- /page content -->

	</div>
	<!-- /page container -->

	<!-- Core JS files -->
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/loaders/pace.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/core/libraries/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/core/libraries/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/loaders/blockui.min.js"></script>
	<!-- /core JS files -->

	<!-- scroll del menu -->
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/ui/nicescroll.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/forms/styling/uniform.min.js"></script>
	<!-- scroll del menu -->


<!-- Core JS files -->
<?php if ($this->uri->segment(1) === 'Csv_import'): ?>
	
	<!-- tables -->
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/tables/datatables/datatables.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/forms/selects/select2.min.js"></script>
	<!-- tables -->

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/core/app.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/datatables_data_sources.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/form_layouts.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/layout_fixed_custom.js"></script>

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/ui/ripple.min.js"></script>
	<!-- /theme JS files -->


	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/tables/datatables/datatables.min.js"></script>

	<script src=" <?php echo base_url(); ?>assets/js_templates/csv_import.js"></script>
	<!-- /theme JS files -->

<?php elseif ($this->uri->segment(1) === 'persona'): ?>

	<!-- tables -->
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/tables/datatables/datatables.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/notifications/bootbox.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/notifications/sweet_alert.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/forms/selects/select2.min.js"></script>
	<!-- tables -->

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/core/app.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/datatables_data_sources.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/form_layouts.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/layout_fixed_custom.js"></script>

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/ui/ripple.min.js"></script>
	<!-- /theme JS files -->

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/tables/datatables/datatables.min.js"></script>

	<script src=" <?php echo base_url(); ?>assets/js_templates/admin/v_confirmar.js"></script>

<?php elseif (($this->uri->segment(1) === 'admin') & ($this->uri->segment(2) === 'programaciones') ): ?>

	<!-- tables -->
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/tables/datatables/datatables.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/notifications/bootbox.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/notifications/sweet_alert.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/forms/selects/select2.min.js"></script>
	<!-- tables -->

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/core/app.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/datatables_data_sources.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/form_layouts.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/layout_fixed_custom.js"></script>

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/ui/ripple.min.js"></script>
	<!-- /theme JS files -->

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/tables/datatables/datatables.min.js"></script>

	<script src=" <?php echo base_url(); ?>assets/js_templates/admin/v_programaciones.js"></script>

<?php elseif (($this->uri->segment(1) === 'alumno') & ($this->uri->segment(2) === 'kardex') ): ?>

	
	<!-- /core JS files -->
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/tables/datatables/datatables.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/tables/datatables/extensions/select.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/forms/selects/select2.min.js"></script>
	<!-- Theme JS files -->
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/ui/nicescroll.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/app.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/pages/datatables_data_sources.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/pages/datatables_extension_buttons_print.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/pages/layout_fixed_custom.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/ui/ripple.min.js"></script>
	<!-- /theme JS files -->

	<script src=" <?php echo base_url(); ?>assets/js_templates/alumno/v_kardex.js"></script>

<?php elseif (($this->uri->segment(1) === 'alumno') & ($this->uri->segment(2) === 'libreta') ): ?>

	
	<!-- /core JS files -->
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/tables/datatables/datatables.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/tables/datatables/extensions/select.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/forms/selects/select2.min.js"></script>
	<!-- Theme JS files -->
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/ui/nicescroll.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/app.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/pages/datatables_data_sources.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/pages/datatables_extension_buttons_print.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/pages/layout_fixed_custom.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/ui/ripple.min.js"></script>
	<!-- /theme JS files -->

	<script src=" <?php echo base_url(); ?>assets/js_templates/alumno/v_libreta.js"></script>

<?php elseif (($this->uri->segment(1) === 'alumno') & ($this->uri->segment(2) === 'programarme') ): ?>

	
	<!-- /core JS files -->
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/tables/datatables/datatables.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/tables/datatables/extensions/select.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/forms/selects/select2.min.js"></script>
	<!-- Theme JS files -->
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/ui/nicescroll.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/app.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/pages/datatables_data_sources.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/pages/datatables_extension_buttons_print.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/pages/layout_fixed_custom.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/ui/ripple.min.js"></script>
	<!-- /theme JS files -->

	<script src=" <?php echo base_url(); ?>assets/js_templates/alumno/v_programarme.js"></script>

<?php elseif (($this->uri->segment(1) === 'alumno') & ($this->uri->segment(2) === 'programar_a_alumno') ): ?>

	<!-- /core JS files -->
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/tables/datatables/datatables.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/tables/datatables/extensions/select.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/forms/selects/select2.min.js"></script>
	<!-- Theme JS files -->
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/ui/nicescroll.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/app.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/pages/datatables_data_sources.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/pages/datatables_extension_buttons_print.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/pages/layout_fixed_custom.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/ui/ripple.min.js"></script>
	<!-- /theme JS files -->

	<script src=" <?php echo base_url(); ?>assets/js_templates/alumno/v_programar.js"></script>



<?php elseif (($this->uri->segment(1) === 'teacher') ): ?>

	
	<!-- /core JS files -->
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/tables/datatables/datatables.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/tables/datatables/extensions/select.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/forms/selects/select2.min.js"></script>
	<!-- Theme JS files -->
<!-- 	<script type="text/javascript" src=">assets/js/plugins/ui/nicescroll.min.js"></script> -->
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/app.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/pages/datatables_data_sources.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/pages/datatables_extension_buttons_print.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/pages/layout_fixed_custom.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/ui/ripple.min.js"></script>
	<!-- /theme JS files -->

	<script src=" <?php echo base_url(); ?>assets/js_templates/teacher/v_up_notes.js"></script>


<?php elseif (($this->uri->segment(1) === 'admin') & ($this->uri->segment(2) === 'menu') ): ?>

	<!-- tables -->
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/tables/datatables/datatables.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/notifications/bootbox.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/notifications/sweet_alert.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/forms/selects/select2.min.js"></script>
	<!-- tables -->

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/core/app.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/datatables_data_sources.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/form_layouts.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/layout_fixed_custom.js"></script>

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/ui/ripple.min.js"></script>
	<!-- /theme JS files -->

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/tables/datatables/datatables.min.js"></script>

	<script src=" <?php echo base_url(); ?>assets/js_templates/admin/v_menu_usuarios.js"></script>


<?php elseif (($this->uri->segment(1) === 'admin') & ($this->uri->segment(2) === 'paralelo') ): ?>

	<!-- tables -->
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/tables/datatables/datatables.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/notifications/bootbox.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/notifications/sweet_alert.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/forms/selects/select2.min.js"></script>
	<!-- tables -->

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/core/app.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/datatables_data_sources.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/form_layouts.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/layout_fixed_custom.js"></script>

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/ui/ripple.min.js"></script>
	<!-- /theme JS files -->

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/tables/datatables/datatables.min.js"></script>

	<script src=" <?php echo base_url(); ?>assets/js_templates/admin/v_asignacion_paralelos.js"></script>


<?php elseif (($this->uri->segment(1) === 'admin') & ($this->uri->segment(2) === 'docente') ): ?>

	<!-- tables -->
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/tables/datatables/datatables.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/notifications/bootbox.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/notifications/sweet_alert.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/forms/selects/select2.min.js"></script>
	<!-- tables -->

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/core/app.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/datatables_data_sources.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/form_layouts.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/layout_fixed_custom.js"></script>

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/ui/ripple.min.js"></script>
	<!-- /theme JS files -->

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/tables/datatables/datatables.min.js"></script>

	<script src=" <?php echo base_url(); ?>assets/js_templates/admin/v_asignacion_docentes.js"></script>


<?php elseif (($this->uri->segment(1) === 'admin') & ($this->uri->segment(2) === 'gestionperiodo') ): ?>

	<!-- tables -->
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/tables/datatables/datatables.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/notifications/bootbox.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/notifications/sweet_alert.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/forms/selects/select2.min.js"></script>
	<!-- tables -->

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/core/app.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/datatables_data_sources.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/form_layouts.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/layout_fixed_custom.js"></script>

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/ui/ripple.min.js"></script>
	<!-- /theme JS files -->

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/tables/datatables/datatables.min.js"></script>

	<script src=" <?php echo base_url(); ?>assets/js_templates/admin/v_gestionperiodo.js"></script>

<?php elseif (($this->uri->segment(1) === 'admin') & ($this->uri->segment(2) === 'bimestre') ): ?>

	
	<!-- tables -->
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/tables/datatables/datatables.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/notifications/bootbox.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/notifications/sweet_alert.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/forms/selects/select2.min.js"></script>
	<!-- tables -->

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/core/app.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/datatables_data_sources.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/form_layouts.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/layout_fixed_custom.js"></script>

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/ui/ripple.min.js"></script>
	<!-- /theme JS files -->

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/tables/datatables/datatables.min.js"></script>

	<script src="<?php echo base_url(); ?>assets/js_templates/admin/v_activar_bimestres.js"></script>


<?php elseif (($this->uri->segment(1) === 'admin') & ($this->uri->segment(2) === 'boletin') ): ?>

	
	<!-- tables -->
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/tables/datatables/datatables.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/notifications/bootbox.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/notifications/sweet_alert.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/forms/selects/select2.min.js"></script>
	<!-- tables -->

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/core/app.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/datatables_data_sources.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/form_layouts.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/layout_fixed_custom.js"></script>

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/ui/ripple.min.js"></script>
	<!-- /theme JS files -->

	<script src="<?php echo base_url(); ?>assets/js_templates/admin/v_boletin.js"></script>


<?php elseif (($this->uri->segment(1) === 'admin') & ($this->uri->segment(2) === 'estadistica') ): ?>

	<!-- tables -->
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/tables/datatables/datatables.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/notifications/bootbox.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/notifications/sweet_alert.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/forms/selects/select2.min.js"></script>
	<!-- tables -->

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/core/app.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/datatables_data_sources.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/form_layouts.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/layout_fixed_custom.js"></script>

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/ui/ripple.min.js"></script>
	<!-- /theme JS files -->

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/tables/datatables/datatables.min.js"></script>

	<script src=" <?php echo base_url(); ?>assets/js_templates/admin/v_centralizador.js"></script>

<?php elseif (($this->uri->segment(1) === 'admin') & ($this->uri->segment(2) === 'grafica') ): ?>

	
	<!-- <script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/tables/datatables/datatables.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/forms/selects/select2.min.js"></script>

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/core/app.js"></script>
	
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/form_layouts.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/layout_fixed_custom.js"></script> -->
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/tables/datatables/datatables.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/notifications/bootbox.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/notifications/sweet_alert.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/forms/selects/select2.min.js"></script>
	<!-- tables -->

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/core/app.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/datatables_data_sources.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/form_layouts.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/layout_fixed_custom.js"></script>

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/ui/ripple.min.js"></script>
	<!-- /theme JS files -->

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/tables/datatables/datatables.min.js"></script>

	<script src="<?php echo base_url(); ?>assets/js/my_pie/canvasjs.min.js"></script>

	<!-- <script src=" <?php echo base_url(); ?>assets/js_templates/admin/v_cake.js"></script> -->

<?php elseif (($this->uri->segment(1) === 'login') & ($this->uri->segment(2) === 'register') ): ?>

	<!-- tables -->
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/tables/datatables/datatables.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/forms/selects/select2.min.js"></script>
	<!-- tables -->

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/forms/styling/uniform.min.js"></script>

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/core/app.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/login.js"></script>

	<script src=" <?php echo base_url(); ?>assets/js_templates/admin/v_create_user.js"></script>

<?php elseif (($this->uri->segment(1) === 'centralizador') ): ?>

	<!-- tables -->
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/tables/datatables/datatables.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/forms/selects/select2.min.js"></script>
	<!-- tables -->

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/forms/styling/uniform.min.js"></script>

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/core/app.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/login.js"></script>

	<script src="<?php echo base_url(); ?>assets/js/my_pie/jquery.canvasjs.min.js"></script>
	<!-- <script src="<?php echo base_url(); ?>assets/js/my_pie/canvasjs.min.js"></script> -->

	<script src=" <?php echo base_url(); ?>assets/js_templates/v_centralizador.js"></script>


<?php elseif (($this->uri->segment(1) === 'admin') & ($this->uri->segment(2) === 'uploadnotes') ): ?>

	<!-- tables -->
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/tables/datatables/datatables.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/notifications/bootbox.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/notifications/sweet_alert.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/forms/selects/select2.min.js"></script>
	<!-- tables -->

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/core/app.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/datatables_data_sources.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/form_layouts.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/layout_fixed_custom.js"></script>

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/ui/ripple.min.js"></script>
	<!-- /theme JS files -->

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/tables/datatables/datatables.min.js"></script>

	<script src=" <?php echo base_url(); ?>assets/js_templates/admin/v_upload_note_teacher.js"></script>

<?php elseif (($this->uri->segment(1) === 'profile') & ($this->uri->segment(2) === 'person') ): ?>

	<!-- tables -->
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/tables/datatables/datatables.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/forms/selects/select2.min.js"></script>
	<!-- tables -->

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/forms/styling/uniform.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/notifications/sweet_alert.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/components_modals.js"></script>

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/core/app.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/login.js"></script>

	<script src=" <?php echo base_url(); ?>assets/js_templates/v_profile_update.js"></script>

<?php elseif (($this->uri->segment(1) === 'profile') ): ?>

	<!-- tables -->
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/tables/datatables/datatables.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/notifications/bootbox.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/notifications/sweet_alert.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/forms/selects/select2.min.js"></script>
	<!-- tables -->

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/core/app.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/datatables_data_sources.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/form_layouts.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/layout_fixed_custom.js"></script>

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/ui/ripple.min.js"></script>
	<!-- /theme JS files -->

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/tables/datatables/datatables.min.js"></script>

	<script src=" <?php echo base_url(); ?>assets/js_templates/v_profile.js"></script>

<?php elseif (($this->uri->segment(1) === 'admin') & ($this->uri->segment(2) === 'efectivo') ): ?>

	<!-- tables -->
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/tables/datatables/datatables.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/forms/selects/select2.min.js"></script>
	<!-- tables -->

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/forms/styling/uniform.min.js"></script>

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/core/app.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/login.js"></script>

	<script src=" <?php echo base_url(); ?>assets/js_templates/admin/v_alumnos_efectivos.js"></script>

<?php elseif (($this->uri->segment(1) === 'admin') & ($this->uri->segment(2) === 'program_students') ): ?>

	<!-- tables -->
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/tables/datatables/datatables.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/notifications/bootbox.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/notifications/sweet_alert.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/forms/selects/select2.min.js"></script>
	<!-- tables -->

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/core/app.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/datatables_data_sources.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/form_layouts.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/layout_fixed_custom.js"></script>

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/ui/ripple.min.js"></script>
	<!-- /theme JS files -->

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/tables/datatables/datatables.min.js"></script>

	<script src=" <?php echo base_url(); ?>assets/js_templates/admin/v_programar_alumnos.js"></script>


<?php elseif (($this->uri->segment(1) === 'admin') ): ?>
	<!-- tables -->
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/tables/datatables/datatables.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/forms/selects/select2.min.js"></script>
	<!-- tables -->

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/forms/styling/uniform.min.js"></script>

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/core/app.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/login.js"></script>

	<script src="<?php echo base_url(); ?>assets/js/my_pie/jquery.canvasjs.min.js"></script>
	<!-- <script src="<?php echo base_url(); ?>assets/js/my_pie/canvasjs.min.js"></script> -->

	<script src=" <?php echo base_url(); ?>assets/js_templates/v_principal.js"></script>



<?php else: ?>
	<!-- eston son los que estaban en la cabecera -->
	<!-- Core JS files -->
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/core/libraries/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/core/libraries/bootstrap.min.js"></script>
	<!-- /core JS files -->

	<!-- Theme JS files -->
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/ui/nicescroll.min.js"></script>

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/core/app.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/layout_fixed_custom.js"></script>

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/ui/ripple.min.js"></script>
	<!-- eston son los que estaban en la cabecera -->
<?php endif; ?>



<!-- todos los cripts que necesito -->
<script type="text/javascript">
    var CFG = {
        url: '<?php echo $this->config->item("base_url");?>',
        name: '<?php echo $this->security->get_csrf_token_name();?>',
        token: '<?php echo $this->security->get_csrf_hash();?>'
    };
</script>


<?php if ($this->uri->segment(2) === 'lista'): ?>
    <script src=" <?php echo base_url(); ?>assets/js_templates/v_alumno.js"></script>
  <?php endif ?>
  <?php if ($this->uri->segment(2) === 'add_alumno_form'): ?>
    <script src=" <?php echo base_url(); ?>assets/js_templates/v_add_alumno.js"></script>
<?php endif ?>

<!-- todos los cripts que necesito -->



</body>
</html>
