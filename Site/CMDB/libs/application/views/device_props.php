<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>

<form action="/device/update/device-props" method="post">
	<input type="hidden" id="deviceName" name = "deviceName" value="<?php echo $device->name; ?>">

	<div class="props-row">
		<span class="left">
			<label for="device_new_name">Device Rename</label>
			<input type="text" id="device_new_name" name="device_new_name" title="Device Rename"
				class="wide
					<?php
						$test = NULL;
						if (isset($device->FunctionalArea)) {
							$test = $device->FunctionalArea->name;
						}
						echo inputelems::enable($test, $userFuncAreas);
					?>"
				value="<?php echo $device->name; ?>"
			/>
		</span><!-- END .left -->
	</div><!-- END .props-row -->

	<?php if (isset($device->OpArea) && $device->OpArea->code == 'CRP') : ?>
		<div class="props-row">
			<span class="left">
				<label for="device_owner">Device Owner</label>
				<input type="text" id="device_owner" name="device_owner"
					title="Device Owner" readonly="readonly"
					class="wide"
					value="<?php
							if (isset($device->Owner)) {
								$first = TRUE;
								foreach ($device->Owner as $owner) {
									if (!$first) {
										echo ', ' . $owner->fname . ' ' . $owner->lname;
									}
									else {
										echo $owner->fname . ' ' . $owner->lname;
										$first = FALSE;
									}
								}
							} ?>"
				/>
			</span><!-- END .left -->
		</div><!-- END .props-row -->
	<?php endif; ?>

	<div class="props-row">
		<span class="left">
			<label for="device_type">Device Type</label>
			<select id="device_type" name="device_type" title="Device Type"
					class="wide
						<?php
							$test = NULL;
							if (isset($device->FunctionalArea)) {
								$test = $device->FunctionalArea->name;
							}
							echo inputelems::enable($test, $userFuncAreas);
						?>" >
				<option value="0">Please Select...</option>
				<?php
					foreach ($deviceTypes as $deviceType) {
						echo "<option value=\"{$deviceType->id}\" ";
						if ($device->DeviceType  && ($device->DeviceType->id == $deviceType->id)) {
							echo 'selected="selected"';
						}
						echo " >";
						echo $deviceType->type;
						echo "</option>";
					}
				?>
			</select>
		</span><!-- END .left -->
	</div><!-- END .props-row -->

	<div class="props-row">
		<span class="left">
			<label for="function_area">Hardware Group</label>
			<select id="function_area" name="function_area" title="Hardware Group"
					class="wide
						<?php
							$test = NULL;
							if (isset($device->FunctionalArea)) {
								$test = $device->FunctionalArea->name;
							}
							echo inputelems::enable($test, $userFuncAreas);
						?>" >
				<option value="0">Please Select...</option>
				<?php
					foreach ($funcAreas as $funcArea) {
						echo "<option value=\"{$funcArea['id']}\" ";
						if (isset($device->FunctionalArea) && $device->FunctionalArea->name == $funcArea['name']) {
							echo 'selected="selected"';
						}
						echo " >";
						echo $funcArea['name'];
						echo "</option>";
					}
				?>
			</select>
		</span><!-- END .left -->

		<span class="right">
			<label for="application_area">Application Area</label>
			<select id="application_area" name="application_area" title="Functional Area"
					class="wide
						<?php
							$test = array();
							if (isset($device->FunctionalArea)) {
								$test[] = $device->FunctionalArea->name;
							}
							if (isset($device->ApplicationArea)) {
								$test[] = $device->ApplicationArea->name;
							}
							echo inputelems::enable($test, $userFuncAreas); ?>" >
				<option value="0">Please Select...</option>
				<?php
					foreach ($appFuncAreas as $funcArea) {
						echo "<option value=\"{$funcArea->id}\" ";
						if (isset($device->ApplicationArea) && $device->ApplicationArea->name == $funcArea->name) {
							echo 'selected="selected"';
						}
						echo " >";
						echo $funcArea->name;
						echo "</option>";
					}
				?>
			</select>
		</span><!-- END .right -->
	</div><!-- END .props-row -->

	<div class="props-row">

		<span class="left">
			<label for="op_area">Operational Area</label>
			<select id="op_area" name="op_area" title="Area"
					class="wide
						<?php
							$test = NULL;
							if (isset($device->FunctionalArea)) {
								$test = $device->FunctionalArea->name;
							}
							echo inputelems::enable($test, $userFuncAreas);
						?>" >
				<?php
					foreach ($opAreas as $opArea) {
						echo "<option value=\"{$opArea->id}\" ";
						if ($device->OpArea && ($device->OpArea->id == $opArea->id)) {
							echo 'selected="selected"';
						}
						echo " >";
						echo $opArea->type;
						echo "</option>";
					}
				?>
			</select>
		</span><!-- END .left -->

		<span class="right">
			<label for="op_status">Operational Status</label>
			<select id="op_status" name="op_status" title="Status"
					class="wide
						<?php
							$test = NULL;
							if (isset($device->FunctionalArea)) {
								$test = $device->FunctionalArea->name;
							}
							echo inputelems::enable($test, $userFuncAreas);
						?>" >
				<?php
					foreach ($opStatuses as $opStatus) {
						echo "<option value=\"{$opStatus->id}\" ";
						if ($device->OpStatus && ($device->OpStatus->id == $opStatus->id)) {
							echo 'selected="selected"';
						}
						echo " >";
						echo $opStatus->status;
						echo "</option>";
					}
				?>
			</select>
		</span><!-- END .right -->
	</div><!-- END .props-row -->

	<div class="props-row-triple">
		<span class="left">
			<label for="site_code">Site</label>
			<select id="site_code" name="site_code" title="Site"
					class="wide
						<?php
							$test = NULL;
							if (isset($device->FunctionalArea)) {
								$test = $device->FunctionalArea->name;
							}
							echo inputelems::enable($test, $userFuncAreas);
						?>" >
				<option value="0">Please Select...</option>
				<?php
					foreach ($sites as $site) {
						echo "<option value=\"{$site->code}\" ";
						if ($device->Location &&$device->Location->Site &&
								($device->Location->Site->code == $site->code)) {

							echo 'selected="selected"';
						}
						echo " >";
						echo $site->name;
						echo "</option>";
					}
				?>
			</select>
		</span><!-- END .left -->

		<span class="right">
			&nbsp;
		</span><!-- END .right -->

		<span class="left">
			<label for="site_floor">Floor</label>
			<input type="text" id="site_floor" name="site_floor" title="Site Floor"
					class="wide
						<?php
							$test = NULL;
							if (isset($device->FunctionalArea)) {
								$test = $device->FunctionalArea->name;
							}
							echo inputelems::enable($test, $userFuncAreas);
						?>"
					value="<?php echo isset($device->Location) ? $device->Location->floor : ''; ?>" />
		</span><!-- END .left -->

		<span class="right">
			<label for="site_room">Room</label>
			<input type="text" id="site_room" name="site_room" title="Site Room"
					class="wide
						<?php
							$test = NULL;
							if (isset($device->FunctionalArea)) {
								$test = $device->FunctionalArea->name;
							}
							echo inputelems::enable($test, $userFuncAreas);
						?>"
					value="<?php echo isset($device->Location) ? $device->Location->room : ''; ?>"  />
		</span><!-- END .right -->

		<span class="left">
			<label for="site_row">Row</label>
			<input type="text" id="site_row" name="site_row" title="Site Row"
					class="wide
						<?php
							$test = NULL;
							if (isset($device->FunctionalArea)) {
								$test = $device->FunctionalArea->name;
							}
							echo inputelems::enable($test, $userFuncAreas);
						?>"
					value="<?php echo isset($device->Location) ? $device->Location->row : ''; ?>"  />
		</span><!-- END .left -->

		<span class="right">
			<label for="site_cabinet">Cabinet</label>
			<input type="text" id="site_cabinet" name="site_cabinet" title="Site Cabinet"
					class="wide
						<?php
							$test = NULL;
							if (isset($device->FunctionalArea)) {
								$test = $device->FunctionalArea->name;
							}
							echo inputelems::enable($test, $userFuncAreas);
						?>"
					value="<?php echo isset($device->Location) ? $device->Location->cabinet : ''; ?>"  />
		</span><!-- END .right -->
	</div><!-- END .props-row-triple -->

	<div class="props-row-double">
		<span class="left">
			<label for="asset_tag">Asset Tag</label>
			<input type="text" id="asset_tag" name="asset_tag"
					class="wide
						<?php
							$test = NULL;
							if (isset($device->FunctionalArea)) {
								$test = $device->FunctionalArea->name;
							}
							echo inputelems::enable($test, $userFuncAreas);
						?>"
					value="<?php echo $device->asset_tag; ?>" />
		</span><!-- END .left -->

		<span class="right">
			&nbsp;
		</span><!-- END .right -->

		<span class="left">
			<label for="manufacturer">Manufacturer</label>
			<input type="text" id="manufacturer" name="manufacturer"
					class="wide
						<?php
							$test = NULL;
							if (isset($device->FunctionalArea)) {
								$test = $device->FunctionalArea->name;
							}
							echo inputelems::enable($test, $userFuncAreas);
						?>"
					value="<?php echo $device->manufacturer; ?>" />
		</span><!-- END .left -->

		<span class="right">
			<label for="model">Model</label>
			<input type="text" id="model" name="model"
					class="wide
						<?php
							$test = NULL;
							if (isset($device->FunctionalArea)) {
								$test = $device->FunctionalArea->name;
							}
							echo inputelems::enable($test, $userFuncAreas);
						?>"
					value="<?php echo $device->model; ?>" />
		</span><!-- END .right -->
	</div><!-- END .props-row -->

	<div class="props-row-comment">
		<div class="handle">
			<div class="handle-title">Comments</div>
			<div class="handle-icons">
				<img class="minimize" src="/gfx/down.gif" onclick="Effect.BlindDown('comment-container'); return false;" />
				<img class="maximize" src="/gfx/up.gif" onclick="Effect.BlindUp('comment-container'); return false;" />
			</div>
		</div><!-- END .handle -->

		<div id="comment-container" class="comment-container" style="display: none">
			<span class="left">
				<label for="device_comment">New Comment</label><br />
				<textarea id="device_comment" name="device_comment" class="input"></textarea>
			</span><!-- END .left -->

			<span class="right">
				<label for="comments">Comments</label><br />
				<textarea id="comments" name="comments" class="collection"><?php echo $comments; ?></textarea>
			</span><!-- END .right -->
		</div>
	</div><!-- END .props-row -->

	<div class="button-row">
		<input type="submit" id="submit-dev-props" name="submit-dev-props"
				class="med" value="Update Device Properties"
				<?php
					$test = NULL;
					if (isset($device->FunctionalArea)) {
						$test = $device->FunctionalArea->name;
					}
					echo inputelems::enable($test, $userFuncAreas, FALSE);
				?>
		/>
	</div><!-- END .button-row -->
</form>
