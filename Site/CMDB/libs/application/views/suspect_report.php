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
	<?php else : ?>
		<p>The Suspect Report for Device Properties was generated and emailed to
		the following address: <?php echo $reportEmail; ?>
		</p>
	<?php endif; ?>
</div><!-- END #left-content -->