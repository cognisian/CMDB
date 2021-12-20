<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<div id="left-content">
	<h1>Suspect Device Reporting</h1>
	<p>This page will generate a list of devices which currently do not have
	a particular field or range of fields set.  The types of data to determine
	a suspect device are specified based on the suspect menu item selected.
	</p>
	<h2>Suspect Report for: <?php echo $reportType; ?></h2>
	<?php if (isset($errorMsg) && !empty($errorMsg)) : ?>
		<p class="error">
			<?php echo $errorMsg; ?>
		</p>
	<?php endif; ?>
	<?php if (isset($reportGen) && $reportGen) : ?>
		<p>The Suspect Report for Device Properties was generated and emailed to
		the following address: <?php echo $reportEmail; ?>
		</p>
	<?php endif; ?>
	<form id="suspect-devices" name="suspect-devices" method="post" action="/suspect/generate_devices" >

		<div class="props-row last-row">
			<div class="prop-col">
				<label for="device_name">Device Name</label>
				<input type="checkbox" id="device_name" name="device_props[]" value="name" />
			</div><!-- END .prop-col -->
			<div class="prop-col">
				<label for="device_name">Asset Tag</label>
				<input type="checkbox" id="asset_tag" name="device_props[]" value="asset_tag" />
			</div><!-- END .prop-col -->
		</div><!-- END .props-row -->

		<div class="props-row last-row">
			<div class="prop-col">
				<label for="device_name">Device Type</label>
				<input type="checkbox" id="device_type" name="device_props[]" value="device_type_id" />
			</div><!-- END .prop-col -->
			<div class="prop-col">
				<label for="device_name">Operartional Area</label>
				<input type="checkbox" id="op_area" name="device_props[]" value="op_area_id" />
			</div><!-- END .prop-col -->
		</div><!-- END .props-row -->

		<div class="props-row last-row">
			<div class="prop-col">
				<label for="device_name">Operational Status</label>
				<input type="checkbox" id="op_status" name="device_props[]" value="op_status_id" />
			</div><!-- END .prop-col -->

			<div class="prop-col">
				<label for="device_name">Device Functional Area</label>
				<input type="checkbox" id="func_area" name="device_props[]" value="functional_area_id" />
			</div><!-- END .prop-col -->
		</div><!-- END .props-row -->

		<div class="props-row last-row">
			<div class="prop-col">
				<label for="device_name">Application Area</label>
				<input type="checkbox" id="app_area" name="device_props[]" value="application_area_id" />
			</div><!-- END .prop-col -->

			<div class="prop-col">
				<label for="device_name">Site</label>
				<input type="checkbox" id="site" name="device_props[]" value="site_id" />
			</div><!-- END .prop-col -->
		</div><!-- END .props-row -->

		<div class="props-row last-row">
			<div class="prop-col">
				<input type="submit" id="submit-suspect-devices" name="submit-suspect-devices" value="Generate report" />
			</div><!-- END .prop-col -->
		</div><!-- END .props-row -->
	</form>
</div><!-- END #left-content -->