<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<div id="left-content">
	<h1>Switch Device Report</h1>
	<p>This page will generate a list of devices which are currently plugged into a
	particular Network Device
	</p>
	<h2>Switch Device Details Report by: <?php echo $reportType; ?></h2>
	<?php if (isset($errorMsg) && !empty($errorMsg)) : ?>
		<p class="error">
			<?php echo $errorMsg; ?>
		</p>
	<?php endif; ?>
	<?php if (isset($reportGen) && $reportGen) : ?>
		<p>The Switch Report was generated and emailed to
		the following address: <?php echo $reportEmail; ?>
		</p>
	<?php endif; ?>
	<form id="device-switch-details" name="device-switch-details" method="post" action="/report/generate_switch_report" >

		<input type="hidden" id="report-type" name="report-type" value="by_switch" />

		<div class="props-row last-row">
			<div class="prop-col">
				<label for="switch-name" class="multi-label-net-dep">Select Switch</label><br/>
				<div id="autocomplete-switch">
						<input type="text" id="switch-name" name="name" class="autocompleter-text wide"/>
						<div id="autocompleter-switch-list-wrapper" class="autocompleter-switch-list-wrapper">
							<div id="device-switch-suggestions" class="device-switch-suggestions autocompleter-list" style="display: none;"></div>
						</div>
				</div>
				<div id="indicator2" class="indicator2" style="display: none">
						<img src="/gfx/page_wait_28.gif" alt="Working..." />
				</div>
			</div><!-- END .prop-col -->
		</div><!-- END .props-row -->

		<div class="props-row last-row">
			<div class="prop-col">
				<input type="submit" id="submit-report-device-switch" name="submit-report-device-switch" value="Generate Report" />
			</div><!-- END .prop-col -->
		</div><!-- END .props-row -->
	</form>
</div><!-- END #left-content -->
