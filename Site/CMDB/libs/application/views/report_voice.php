<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<div id="left-content">
	<h1>Voice Device Reporting</h1>
	<p>This page will generate a list of voice (Phone or IP Phone) devices.
	</p>
	<h2>Device Details Report by: <?php echo $reportType; ?></h2>
	<?php if (isset($errorMsg) && !empty($errorMsg)) : ?>
		<p class="error">
			<?php echo $errorMsg; ?>
		</p>
	<?php endif; ?>
	<?php if (isset($reportGen) && $reportGen) : ?>
		<p>The Voice Device Report for Device Properties was generated and emailed to
		the following address: <?php echo $reportEmail; ?>
		</p>
	<?php endif; ?>
	<form id="device-details-voice" name="device-details-voice" method="post" action="/report/generate_voice_report" >

		<input type="hidden" id="report-type" name="report-type" value="by_voice" />

		<div class="props-row last-row">
			<div class="prop-col">
				<label for="voice">For which Phone type</label>
				<select id="voice" name="voice" >
					<?php
						foreach ($voices as $id => $voice) {
							echo "<option value=\"{$id}\" >";
							echo $voice;
							echo "</option>";
						}
					?>
				</select>
			</div><!-- END .prop-col -->
		</div><!-- END .props-row -->

		<div class="props-row last-row">
			<div class="prop-col">
				<input type="submit" id="submit-report-devices-voice" name="submit-report-devices-voice" value="Generate Report" />
			</div><!-- END .prop-col -->
		</div><!-- END .props-row -->
	</form>
</div><!-- END #left-content -->
