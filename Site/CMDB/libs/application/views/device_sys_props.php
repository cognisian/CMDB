<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>

<?php $sysProp = $device->SystemProperty[0]?>

<form action="/device/update/system-props" method="post">
	<input type="hidden" id="deviceName" name = "deviceName" value="<?php echo $device->name; ?>">

	<div class="props-row">

		<span class="left">
			<label for="type">Type</label>
			<select id="type" name="type" title="Device Type"
					class="wide
						<?php
							$test = NULL;
							if (isset($device->FunctionalArea)) {
								$test = $device->FunctionalArea->name;
							}
							echo inputelems::enable($test, $userFuncAreas);
						?>" >
				<?php
					foreach ($sysTypes as $sysType) {
							echo "<option value=\"{$sysType['code']}\" ";
							if ($sysProp->type == $sysType['code']) {
								echo 'selected="selected"';
							}
							echo " >";

							echo $sysType['name'];
							echo "</option>";
					}
				?>
			</select>
		</span><!-- END .left -->
	</div><!-- END .props-row -->

	<div class="props-row-double">

		<span class="left">
			<label for="os">Operating System</label>
			<input type="text" id="os" name="os"
					class="wide
						<?php
							$test = NULL;
							if (isset($device->FunctionalArea)) {
								$test = $device->FunctionalArea->name;
							}
							echo inputelems::enable($test, $userFuncAreas);
						?>"
					value="<?php echo $sysProp->os; ?>" />
		</span><!-- END .left -->

		<span class="right">&nbsp;</span>

		<span class="left">
			<label for="os_version">OS Version</label>
			<input type="text" id="os_version" name="os_version"
					class="wide
						<?php
							$test = NULL;
							if (isset($device->FunctionalArea)) {
								$test = $device->FunctionalArea->name;
							}
							echo inputelems::enable($test, $userFuncAreas);
						?>"
					value="<?php echo $sysProp->os_version; ?>" />
		</span><!-- END .left -->

		<span class="right">
			<label for="os_patch_version">OS Patch Version</label>
			<input type="text" id="os_patch_version" name="os_patch_version"
					class="wide
						<?php
							$test = NULL;
							if (isset($device->FunctionalArea)) {
								$test = $device->FunctionalArea->name;
							}
							echo inputelems::enable($test, $userFuncAreas);
						?>"
					value="<?php echo $sysProp->os_patch_version; ?>" />
		</span><!-- END .right -->
	</div><!-- END .props-row-double -->

	<div class="props-row-triple">

		<span class="left">
			<label for="num_cpu">Num of CPU</label>
			<input type="text" id="num_cpu" name="num_cpu"
					class="wide
						<?php
							$test = NULL;
							if (isset($device->FunctionalArea)) {
								$test = $device->FunctionalArea->name;
							}
							echo inputelems::enable($test, $userFuncAreas);
						?>"
					value="<?php echo $sysProp->num_cpu; ?>"	/>
		</span><!-- END .left -->

		<span class="right">
			<label for="cpu_type">CPU Type</label>
			<input type="text" id="cpu_type" name="cpu_type"
					class="wide
						<?php
							$test = NULL;
							if (isset($device->FunctionalArea)) {
								$test = $device->FunctionalArea->name;
							}
							echo inputelems::enable($test, $userFuncAreas);
						?>"
					value="<?php echo $sysProp->cpu_type; ?>" />
		</span><!-- END .right -->

		<span class="left">
			<label for="ram">RAM</label>
			<input type="text" id="ram" name="ram"
					class="wide
						<?php
							$test = NULL;
							if (isset($device->FunctionalArea)) {
								$test = $device->FunctionalArea->name;
							}
							echo inputelems::enable($test, $userFuncAreas);
						?>"
					value="<?php echo $sysProp->ram; ?>" />
		</span><!-- END .left -->

		<span class="right">&nbsp;</span>

		<span class="left">
			<label for="internal_disks">Num Internal Disks</label>
			<input type="text" id="internal_disks" name="internal_disks"
					class="wide
						<?php
							$test = NULL;
							if (isset($device->FunctionalArea)) {
								$test = $device->FunctionalArea->name;
							}
							echo inputelems::enable($test, $userFuncAreas);
						?>"
					value="<?php echo $sysProp->internal_disks; ?>" />
		</span><!-- END .left -->

		<span class="right">
			<label for="internal_storage">Total Internal Storage</label>
			<input type="text" id="internal_storage" name="internal_storage"
					class="wide
						<?php
							$test = NULL;
							if (isset($device->FunctionalArea)) {
								$test = $device->FunctionalArea->name;
							}
							echo inputelems::enable($test, $userFuncAreas);
						?>"
					value="<?php echo $sysProp->internal_storage; ?>" />
		</span><!-- END .right -->
	</div><!-- END .props-row-triple -->

	<div class="props-row-comment">
		<div class="handle">
			<div class="handle-title">Comments</div>
			<div class="handle-icons">
				<img class="minimize" src="/gfx/down.gif" onclick="Effect.BlindDown('sys-comment-container'); return false;" />
				<img class="maximize" src="/gfx/up.gif" onclick="Effect.BlindUp('sys-comment-container'); return false;" />
			</div>
		</div><!-- END .handle -->

		<div id="sys-comment-container" class="comment-container" style="display: none">
			<span class="left">
				<label for="system_comment">New Comment</label><br />
				<textarea id="system_comment" name="system_comment" class="input"></textarea>
			</span><!-- END .left -->

			<span class="right">
				<label for="system_comments">Comments</label><br />
				<textarea id="system_comments" name="system_comments" class="collection"><?php echo $comments; ?></textarea>
			</span><!-- END .right -->
		</div>
	</div><!-- END .props-row -->

	<div class="button-row">
		<input type="submit" id="submit-dev-sys-props" name="submit-dev-sys-props"
				class="med" value="Update System Properties"
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