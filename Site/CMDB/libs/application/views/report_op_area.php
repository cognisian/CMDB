<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<div id="left-content">
	<h1>Suspect Device Reporting</h1>
	<p>This page will generate a list of devices which currently do not have
	a particular field or range of fields set.  The types of data to determine
	a suspect device are specified based on the suspect menu item selected.
	</p>
	<h2>Device Details Report by: <?php echo $reportType; ?></h2>
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
	<form id="device-details-site" name="device-details-site" method="post" action="/report/generate_report" >

		<input type="hidden" id="report-type" name="report-type" value="by_op_area" />

		<div class="props-row last-row">
			<div class="prop-col">
				<label for="site">For which Site (city) to generate Device details</label>
				<select id="site" name="site" >
					<?php
						foreach ($sites as $id => $site) {
							echo "<option value=\"{$id}\" >";
							echo $site['label'];
							echo "</option>";
						}
					?>
				</select>
			</div><!-- END .prop-col -->
		</div><!-- END .props-row -->

		<div class="props-row">
			<div class="prop-col">
				<label for="op_area">For which Operational Area</label>
				<select id="op_area" name="op_area" >
					<option value="0">Please Select ...</option>
					<?php
						foreach ($opAreas as $opArea) {
							echo "<option value=\"{$opArea['id']}\" >";
							echo $opArea['type'];
							echo "</option>";
						}
					?>
				</select>
			</div><!-- END .prop-col -->
		</div><!-- END .props-row -->

		<div class="props-row last-row">
			<div class="prop-col">
				<input type="submit" id="submit-report-devices-site" name="submit-report-devices-site" value="Generate Report" />
			</div><!-- END .prop-col -->
		</div><!-- END .props-row -->
	</form>
</div><!-- END #left-content -->
