<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<div id="left-content">
	<h1>Edit A Device Page</h1>
	<p>
	This page will allow you to edit an existing Symcor network device.
	</p>
	<p>
	All the properties of the device will be displayed, however, only the
	properties relevant to your Business Unit will be enabled and are shown with a
	<span style="background-color: #FFFFCC;">&nbsp;yellow&nbsp;</span> background.
	</p>
	<p>
	Clicking on the UP/DOWN icon will minimize/maximize the device
	details.
	</p>

	<?php
		$devFuncArea = '';
		if (isset($device->FunctionalArea)) $devFuncArea = $device->FunctionalArea->name;
	?>
	<div id="device-edit">

		<div class="device">

			<div class="main-handle">
				<div class="main-handle-title"><?php echo $deviceName . '  (' . $devFuncArea . ')'; ?></div>
				<div class="main-handle-icons">
					<img class="minimize" src="/gfx/down.gif" onclick="Effect.BlindDown('device-details'); return false;" />
					<img class="maximize" src="/gfx/up.gif" onclick="Effect.BlindUp('device-details'); return false;" />
				</div>
			</div><!-- END .handle -->

			<div id="device-details" class="device-details">

				<div id="device-props" class="device-details-props">
					<div class="handle">
						<div class="handle-title">Device Properties</div>
						<div class="handle-icons">
							<img class="minimize" src="/gfx/down.gif" onclick="Effect.BlindDown('device-props-detail'); return false;" />
							<img class="maximize" src="/gfx/up.gif" onclick="Effect.BlindUp('device-props-detail'); return false;" />
						</div>
					</div><!-- END .handle -->

					<div id="device-props-detail" class="props">
						<?php  echo $deviceProps ?>
					</div><!-- END .props -->
				</div><!-- END #device-props -->

				<div id="system-props" class="device-details-props">
					<div class="handle">
						<div class="handle-title">System Properties</div>
						<div class="handle-icons">
							<img class="minimize" src="/gfx/down.gif" onclick="Effect.BlindDown('system-props-detail'); return false;" />
							<img class="maximize" src="/gfx/up.gif" onclick="Effect.BlindUp('system-props-detail'); return false;" />
						</div>
					</div><!-- END .handle -->

					<div id="system-props-detail" class="props">
						<?php echo $deviceSysProps; ?>
					</div><!-- END .props -->
				</div><!-- END #system-props -->

				<div id="service-props" class="device-details-props">
					<div class="handle">
						<div class="handle-title">Service Properties</div>
						<div class="handle-icons">
							<img class="minimize" src="/gfx/down.gif" onclick="Effect.BlindDown('system-props-detail'); return false;" />
							<img class="maximize" src="/gfx/up.gif" onclick="Effect.BlindUp('system-props-detail'); return false;" />
						</div>
					</div><!-- END .handle -->

					<div id="service-props-detail" class="props">
						<?php echo $deviceServProps; ?>
					</div><!-- END .props -->
				</div><!-- END #service-props -->

				<div id="network-props" class="device-details-props">
					<div class="handle">
						<div class="handle-title">Network Properties</div>
						<div class="handle-icons">
							<img class="minimize" src="/gfx/down.gif" onclick="Effect.BlindDown('network-props-detail'); return false;" />
							<img class="maximize" src="/gfx/up.gif" onclick="Effect.BlindUp('network-props-detail'); return false;" />
						</div>
					</div><!-- END .handle -->

					<div id="network-props-detail" class="props">
						<?php echo $deviceNetProps; ?>
					</div><!-- END .props -->
				</div><!-- END #network-props -->

				<div id="device-deps" class="device-details-props">
					<div class="handle">
						<div class="handle-title">Device Dependencies</div>
						<div class="handle-icons">
							<img class="minimize" src="/gfx/down.gif" onclick="Effect.BlindDown('dependency-detail'); return false;" />
							<img class="maximize" src="/gfx/up.gif" onclick="Effect.BlindUp('dependency-detail'); return false;" />
						</div>
					</div>

					<div id="dependency-detail" class="props">
						<?php echo $deviceDeps; ?>
					</div>
				</div><!-- END #device-deps -->

				<div id="network-deps" class="device-details-props">
					<div class="handle">
						<div class="handle-title">Network Dependencies</div>
						<div class="handle-icons">
							<img class="minimize" src="/gfx/down.gif" onclick="Effect.BlindDown('dependency-detail'); return false;" />
							<img class="maximize" src="/gfx/up.gif" onclick="Effect.BlindUp('dependency-detail'); return false;" />
						</div>
					</div>

					<div id="network-dep-detail" class="props">
						<?php echo $networkDeps; ?>
					</div>
				</div><!-- END #network-deps -->

				<div id="service-deps" class="device-details-props">
					<div class="handle">
						<div class="handle-title">Service Dependencies</div>
						<div class="handle-icons">
							<img class="minimize" src="/gfx/down.gif" onclick="Effect.BlindDown('dependency-detail'); return false;" />
							<img class="maximize" src="/gfx/up.gif" onclick="Effect.BlindUp('dependency-detail'); return false;" />
						</div>
					</div>

					<div id="service-deps-detail" class="props">
						<?php echo $serviceDeps; ?>
					</div>
				</div><!-- END #service-deps -->

			</div>
		</div><!-- END #device -->
	</div><!-- END #device-edit -->

</div><!-- END #left-content -->