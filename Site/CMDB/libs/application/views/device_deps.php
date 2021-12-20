<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>

<?php $parentDevs = $device->ParentDevice; ?>

<form action="/device/update/device-deps" method="post">
	<input type="hidden" id="deviceName" name = "deviceName" value="<?php echo $device->name; ?>">

	<div class="multicontent-row-dev-dep">

		<div class="multi-select-dev-dep">
			<label for="serv-dep-device-name" class="multi-label-dev-dep">Select Parent Device</label><br />
			<div id="autocomplete-parent-device" class="autocomplete-parent-device">
				<input type="text" id="parent-device-name" name="name" class="parent-device-name autocompleter-text"/>
				<div id="autocompleter-parent-device-wrapper" class="autocompleter-parent-device-wrapper">
					<div id="parent-device-suggestions" class="parent-device-suggestions autocompleter-list"></div>
				</div>
			</div>

			<div id="indicator1" class="indicator1" style="display: none">
				<img src="/gfx/page_wait_28.gif" alt="Working..." />
			</div>
		</div><!--  END .multi-select-dev-dep -->

		<div class="mid-col-dev-dep">
			<div id="add-parent-device" class="button">
				&nbsp;&gt;&nbsp;
			</div>
		</div>

		<div class="multi-select-dev-dep">
			<label for="parent-devices" class="multi-label-dev-dep">Defined Parent Devices</label><br/>
			<select multiple="multiple" id="parent-devices" name="parent-devices[]" size="5" class="enabled parent-devices"
					class="parent-devices
						<?php
							$test = NULL;
							if (isset($device->FunctionalArea)) {
								$test = $device->FunctionalArea->name;
							}
							echo inputelems::enable($test, $userFuncAreas);
						?>" >
				<?php
					foreach ($parentDevs as $parentDev) {
						echo "<option value=\"{$parentDev->name}\"";
						echo " >";
						echo $parentDev->name;
						echo "</option>";
					}
				?>
			</select>
		</div><!-- END .prop-col -->
	</div><!-- END .props-row -->

	<div class="button-row">
		<input type="submit" id="submit-dev-deps" name="submit-dev-deps"
				class="med" value="Update Device Dependencies"
				<?php
					$test = NULL;
					if (isset($device->FunctionalArea)) {
						$test = $device->FunctionalArea->name;
					}
					echo inputelems::enable($test, $userFuncAreas, FALSE);
				?>
		/>
	</div><!-- END .props-row -->
</form>