<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>

<?php $sysProp = $device->SystemProperty[0]?>

<form action="/device/update/service-props" method="post">
	<input type="hidden" id="deviceName" name = "deviceName" value="<?php echo $device->name; ?>">

	<div class="multicontent-row-serv-pro">
		<div class="multi-select-serv-pro">
			<label for="list-local-services" class="multi-label-serv-pro">Available Services</label>
			<select multiple="multiple" id="list-local-services" name="list-local-services" size="15"
					class="list-local-services
						<?php
							$test = array();
							if (isset($device->ApplicationArea)) {
								$test[] = $device->ApplicationArea->name;
							}
							echo inputelems::enable($test, $userFuncAreas);
						?>" >
				<?php
					foreach($services as $service) {
						echo "<option value=\"{$service['id']}\"";
						echo " >";
						echo $service['name'] . ' ' . $service['port'] .
							' (' . $service['protocol'] . ')';
						echo "</option>";
					}
				?>
			</select>
		</div><!-- END .multi-select-serv-pro -->

		<div class="mid-col-serv-pro">
				<div id="add-local-service" class="button">
					&nbsp;&gt;&nbsp;
				</div>
				<div id="del-local-service" class="button">
					&nbsp;&lt;&nbsp;
				</div>
		</div><!-- END .mid-col-serv-pro -->

		<div class="multi-select-serv-pro">
			<label for="local-services" class="multi-label-serv-pro">Defined Services</label>
			<select multiple="multiple" id="local-services" name="local-services[]" size="15"
					class="local-services
						<?php
							$test = array();
							if (isset($device->ApplicationArea)) {
								$test[] = $device->ApplicationArea->name;
							}
							echo inputelems::enable($test, $userFuncAreas);
						?>" >
				<?php
					if ($device->ServiceProperty) {
						foreach($device->ServiceProperty as $devService) {
							echo "<option value=\"{$devService->Service->id}\"";
							echo " >";
							echo $devService->Service->name . ' ' . $devService->Service->port .
								' (' . $devService->Service->protocol . ')';
							echo "</option>";
						}
					}
				?>
			</select>
		</div><!-- END .prop-col -->
	</div><!-- END .props-row -->

	<div class="button-row">
		<input type="submit" id="submit-local-services" name="submit-local-services"
				class="med" value="Update Local Services"
				<?php
					$test = array();
					if (isset($device->ApplicationArea)) {
						$test[] = $device->ApplicationArea->name;
					}
					echo inputelems::enable($test, $userFuncAreas, FALSE);
				?>
		/>
	</div><!-- END .button-row -->
</form>