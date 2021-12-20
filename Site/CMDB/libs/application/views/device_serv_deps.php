<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>

<?php $servProps = $device->ServiceProperty; ?>

<form action="/device/update/service-deps" method="post">
	<input type="hidden" id="deviceName" name = "deviceName" value="<?php echo $device->name; ?>">

	<div class="table-row-ser-dep">
		<?php if (isset($depServProps) && count($depServProps) > 0) : ?>
			<table id="defined-service-deps" class="defined-deps">
				<thead>
					<tr>
						<th colspan="2">Defined Service Dependencies</th>
					</tr>
					<tr>
						<th>Local Service</th>
						<th>Remote Dependencies</th>
					</tr>
				</thead>

				<tbody>
					<?php
						foreach ($depServProps as $servProp) {

							$firstRow = TRUE; // changing service starts new rowspan

							echo '<tr>';

							$rowSpan = count($servProp['deps']);
							echo "<td class=\"local\" rowspan=\"{$rowSpan}\">";

							echo $servProp['name'] . ' ' . $servProp['port'] .
								' (' . $servProp['protocol'] . ')';
							echo '</td><!-- END td.local -->';

							foreach ($servProp['deps'] as $dep) {

								if (!$firstRow) {
									echo '<tr>';
								}

								echo '<td class="remote">';
								echo $dep['deviceName'] . ' => ';
								echo $dep['name'] . ' ' . $dep['port'] .
										' (' . $dep['protocol'] . ')';
								echo '</td><!-- END td.local -->';

								echo '</tr>';
								$firstRow = FALSE; // After adding first dep, all new rows added to rowspan
							}
						}
					?>
				</tbody>
			</table>
		<?php endif; ?>
	</div><!-- END .props-row -->

	<div class="multicontent-row-ser-dep">
		<div class="multi-select-ser-dep">

			<label for="list-local-def-services" class="multi-label-ser-dep">Local Defined Service</label><br/>
			<select id="list-local-def-services" name="list-local-def-services"
					class="list-local-def-services
						<?php
								$test = array();
								if (isset($device->ApplicationArea)) {
									$test[] = $device->ApplicationArea->name;
								}
								echo inputelems::enable($test, $userFuncAreas);
							?>" >
					<option value="0">Please Select Service...</option>
					<?php
						foreach($definedServices as $servProp) {
							echo "<option value=\"{$servProp->Service->id}\"";
							echo " >";
							echo $servProp->Service->name . ' ' . $servProp->Service->port .
								' (' . $servProp->Service->protocol . ')';
							echo "</option>";
						}
					?>
			</select>

			<br/><br/>

			<label for="serv-dep-device-name" class="multi-label-ser-dep">Select Remote Device</label><br/>
			<div id="autocomplete-device-serv-dep" class="autocomplete-device-serv-dep">
				<input type="text" id="serv-dep-device-name" name="name" class="serv-dep-device-name autocompleter-text wide"/>
				<div id="autocompleter-device-serv-list-wrapper" class="autocompleter-device-serv-list-wrapper">
					<div id="device-service-suggestions" class="autocompleter-list device-service-suggestions" ></div>
				</div>
			</div>
			<div id="indicator3" class="indicator3" style="display: none">
				<img src="/gfx/page_wait_28.gif" alt="Working..." />
			</div>
		</div><!-- END .multi-select-ser-dep -->

		<div class="multi-select-ser-dep">
			<label for="list-remote-def-services" class="multi-label-ser-dep">Defined Remote Services</label><br/>
			<select multiple="multiple" id="list-remote-def-services" name="list-remote-def-services" size="15"
					class="list-remote-def-services
						<?php
							$test = array();
							if (isset($device->ApplicationArea)) {
								$test[] = $device->ApplicationArea->name;
							}
							echo inputelems::enable($test, $userFuncAreas);
						?>" >
			</select>
		</div><!-- END .multi-select-ser-dep -->

		<div class="mid-col-ser-dep">
			<div id="add-remote-service" class="button">
				&nbsp;&gt;&nbsp;
			</div>
		</div>

		<div class="multi-select-ser-dep">
			<label for="remote-services-deps" class="multi-label-ser-dep">Remote Service Dependencies</label><br/>
			<select multiple="multiple" id="remote-services-deps" name="remote-services-deps[]" size="15"
					class="remote-services-deps
						<?php
							$test = array();
							if (isset($device->ApplicationArea)) {
								$test[] = $device->ApplicationArea->name;
							}
							echo inputelems::enable($test, $userFuncAreas);
						?>" >
			</select>
		</div><!-- END .multi-select-ser-dep -->
	</div><!-- END .multicontent-row-ser-dep -->

	<div class="button-row">
		<input type="submit" id="submit-service-deps" name="submit-service-deps"
				class="med" value="Update Service Dependencies"
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