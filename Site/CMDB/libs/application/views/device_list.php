<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<div id="left-content">
	<h1>Select A Device</h1>
	<p>
	This page will allow you to select a device from the list devices returned as a
	result of a search.
	</p>
	<p>
	Clicking the "Edit Device" link will take the user to the device editing page.
	</p>

	<h2>Search results for <?php echo $searchType; ?> <?php foreach($searchParams as $searchParam) {echo $searchParam . ' ';} ?></h2>
	<div id="device-list">

		<p>The search returned <span class="result-total"><?php echo count($devices); ?></span> device(s)</p>

		<?php foreach ($devices as $device) : ?>
			<div class="device">

				<div class="handle">
					<div class="handle-title"><?php echo $device['name'] . '  (' . $device['funcArea'] . ')'; ?></div>
					<div class="handle-function">
						<a href="/device/edit/?name=<?php echo $device['name']; ?>">Edit Device</a>
					</div>
				</div><!-- END .handle -->

			</div><!-- END #device -->
		<?php endforeach; ?>

	</div><!-- END #device-list -->

</div><!-- END #left-content -->