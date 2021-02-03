<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">Input group addons<a class="heading-elements-toggle"><i class="icon-more"></i></a></h5>
		<div class="heading-elements">
			<ul class="icons-list">
				<li><a data-action="collapse"></a></li>
				<li><a data-action="reload"></a></li>
			</ul>
		</div>
	</div>

	<div class="panel-body">
		<p class="content-group-lg">Extend form controls by adding text or buttons before, after, or on both sides of any text-based <code>&lt;input&gt;</code>. Use <code>.input-group</code> with an <code>.input-group-addon</code> to prepend or append elements to a single <code>.form-control</code>. Place one add-on or button on either side of an input. You may also place one on both sides of an input. Multiple add-ons on a single side and multiple form-controls in a single input group aren't supported.</p>

		<form class="form-horizontal" action="#">

			<fieldset class="content-group">
				<legend class="text-bold">Icon addon <?php echo $_SESSION['user_id']; ?></legend>

				<div class="form-group">
					<label class="control-label col-lg-2">Default text input</label>
					<div class="col-lg-10">
						<div class="input-group">
							<span class="input-group-addon"><i class="icon-user"></i></span>
							<input type="text" class="form-control" placeholder="Left icon">
						</div>
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-lg-2">Default text input</label>
					<div class="col-lg-10">
						<div class="input-group">
							<input type="text" class="form-control" placeholder="Right icon">
							<span class="input-group-addon"><i class="icon-user"></i></span>
						</div>
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-lg-2">Default text input</label>
					<div class="col-lg-10">
						<div class="input-group">
							<span class="input-group-addon"><i class="icon-menu6"></i></span>
							<input type="text" class="form-control" placeholder="Left and right icons">
							<span class="input-group-addon"><i class="icon-inbox"></i></span>
						</div>
					</div>
				</div>
			</fieldset>
		</form>
	</div>
</div>

