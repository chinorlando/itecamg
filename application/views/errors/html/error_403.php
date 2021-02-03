<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>ITECA</title>

	<!-- Global stylesheets -->
	<link href="<?php echo base_url();?>https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url();?>assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url();?>assets/css/bootstrap.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url();?>assets/css/core.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url();?>assets/css/components.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url();?>assets/css/colors.css" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->

	<!-- Core JS files -->
	<script type="text/javascript" src=" <?php echo base_url();?>assets/js/plugins/loaders/pace.min.js"></script>
	<script type="text/javascript" src=" <?php echo base_url();?>assets/js/core/libraries/jquery.min.js"></script>
	<script type="text/javascript" src=" <?php echo base_url();?>assets/js/core/libraries/bootstrap.min.js"></script>
	<script type="text/javascript" src=" <?php echo base_url();?>assets/js/plugins/loaders/blockui.min.js"></script>
	<!-- /core JS files -->


	<!-- Theme JS files -->
	<script type="text/javascript" src=" <?php echo base_url();?>assets/js/core/app.js"></script>

	<script type="text/javascript" src=" <?php echo base_url();?>assets/js/plugins/ui/ripple.min.js"></script>
	<!-- /theme JS files -->

</head>

<body class="login-container">

	<!-- Main navbar -->
	<div class="navbar navbar-inverse bg-indigo">
		<div class="navbar-header">
			<a class="navbar-brand" href="index.html"><img src="assets/images/logo_light.png" alt=""></a>

			<ul class="nav navbar-nav pull-right visible-xs-block">
				<li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
			</ul>
		</div>

		<div class="navbar-collapse collapse" id="navbar-mobile">
			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown dropdown-user">
					<a class="dropdown-toggle" data-toggle="dropdown">
						<img src="assets/images/placeholder.jpg" alt="">
						<span><?php echo $_SESSION['username'];?></span>
						<i class="caret"></i>
					</a>

					<ul class="dropdown-menu dropdown-menu-right">
						<?php if ($_SESSION['is_type_user'] == 'is_admin'): ?>
							<li><a href=" <?php echo base_url();?>admin/backup#"><i class="icon-database-export"></i> Backup DB</a></li>
							<li><a href=" <?php echo base_url();?>Login/logout"><i class="icon-switch2"></i> Logout</a></li>
						<?php else: ?>
							<li><a href=" <?php echo base_url();?>Login/logout"><i class="icon-switch2"></i> Logout</a></li>
						<?php endif ?>
					</ul>
				</li>
			</ul>
		</div>
	</div>
	<!-- /main navbar -->


	<!-- Page container -->
	<div class="page-container">

		<!-- Page content -->
		<div class="page-content">

			<!-- Main content -->
			<div class="content-wrapper">

				<!-- Content area -->
				<div class="content">

					<!-- Error title -->
					<div class="text-center content-group">
						<h1 class="error-title">403</h1>
						<h5>¡Vaya! ha ocurrido un error. ¡Prohibido!</h5>
					</div>
					<!-- /error title -->


					<!-- Error content -->
					<div class="row">
						<div class="col-lg-4 col-lg-offset-4 col-sm-6 col-sm-offset-3">
							<form action="#" class="main-search panel panel-body">
								<div class="text-center">
									<!-- <a href="" class="btn bg-pink-400"><i class="icon-circle-left2 position-left"></i> Regresar</a> -->
									<?php echo '<a href="javascript:history.back(1)" class="btn bg-pink-400"><i class="icon-circle-left2 position-left"></i> Regresar</a>'; ?>
								</div>
							</form>
						</div>
					</div>
					<!-- /error wrapper -->


					<!-- Footer -->
					<div class="footer text-muted text-center">
						&copy; 2015. <a href="#">Limitless Web App Kit</a> by <a href="http://themeforest.net/user/Kopyov" target="_blank">Eugene Kopyov</a>
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

</body>
</html>
