<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>

<div id="left-content">
	<h1>Add A Device</h1>

	<p>This page will allow a user to add a new device to the database.</p>

	<p>To add a device a unique device name must be specified along with the
	Business Unit which will own the device.
	</p>

	<form id="add-device-name" name="add-device-name" method="post" action="/device/insert" >
		<div class="login-error"><?php if (isset($error)) echo $error; ?></div>
		<div class="device">

			<div class="main-handle">
				<div class="main-handle-title">New Device</div>
			</div><!-- END .handle -->

			<div id="device-details" class="device-details">

				<div id="device-props" class="device-details-props">
					<div class="handle">
						<div class="handle-title">Device Properties</div>
					</div><!-- END .handle -->

					<div id="device-props-detail" class="props">

						<div class="props-row">
							<span class="left">
								<label for="name">Device Name</label>
								<input type="text" id="name" name="name" title="Device Name" />
							</span><!-- END .left -->

							<span class="right">
								<label for="type">Device Type</label>
								<select id="type" name="type" title="Device Type" >
									<option value="0">Please Select...</option>
									<?php
										foreach ($deviceTypes as $deviceType) {
											echo "<option value=\"{$deviceType->id}\" ";
											echo " >";
											echo $deviceType->type;
											echo "</option>";
										}
									?>
								</select>
							</span><!-- END .right -->
						</div><!-- END .props-row -->

						<div class="props-row">
							<span class="left">
								<label for="function_area">Hardware Group</label>
								<select id="function_area" name="function_area" title="Device Owner" >
									<option value="0">Please Select...</option>
									<?php
										foreach ($hardwareAreas as $funcArea) {
											echo "<option value=\"{$funcArea['id']}\" ";
											echo " >";
											echo $funcArea['name'];
											echo "</option>";
										}
									?>
								</select>
							</span><!-- END .left -->

							<span class="right">
								<label for="application_area">Application Area</label>
								<select id="application_area" name="application_area" title="Application Area" >
									<option value="0">Please Select...</option>
									<?php
										foreach ($funcAreas as $funcArea) {
											echo "<option value=\"{$funcArea->id}\" ";
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
								<label for="site">Site</label>
								<select id="site" name="site" title="Site" >
									<option value="0">Please Select...</option>
									<?php
										foreach ($sites as $site) {
											echo "<option value=\"{$site->code}\" ";
											echo " >";
											echo $site->name;
											echo "</option>";
										}
									?>
								</select>
							</span><!-- END .left -->

							<span class="right">
								&nbsp;
							</span><!-- END .prop-col -->
						</div><!-- END .props-row -->

						<div class="props-row">
							<span class="left">
								<label for="op_area">Operational Area</label>
								<select id="op_area" name="op_area" title="Operational Area" >
									<option value="0">Please Select...</option>
									<?php
										foreach ($opAreas as $opArea) {
											echo "<option value=\"{$opArea->id}\" ";
											echo " >";
											echo $opArea->type;
											echo "</option>";
										}
									?>
								</select>
							</span><!-- END .left -->

							<span class="right">
								<label for="op_status">Operational Status</label>
								<select id="op_status" name="op_status" title="Status" >
									<option value="0">Please Select...</option>
									<?php
										foreach ($opStatuses as $opStatus) {
											echo "<option value=\"{$opStatus->id}\" ";
											echo " >";
											echo $opStatus->status;
											echo "</option>";
										}
									?>
								</select>
							</span><!-- END .right -->
						</div><!-- END .props-row -->

					</div><!-- END .props -->
				</div><!-- END .device-props -->

				<div id="network-props" class="device-details-props">
					<div class="handle">
						<div class="handle-title">Network Properties</div>
					</div><!-- END .handle -->

					<div id="network-props-detail" class="props">
						<div class="props-row">
							<span class="left">
								<label for="ip_addr">IP Address</label>
								<input type="text" id="ip_addr" name="ip_addr" title="IP Address" value="0.0.0.0"/>
							</span><!-- END .left -->
						</div><!-- END .props-row -->

					</div><!-- END .props -->
				</div><!-- END .network-props -->

			</div><!-- END .device-details -->

		</div><!-- END #device -->

		<div class="button-row">
			<input type="submit" id="submit-add-device" name="submit-add-device" class="med" value="Add New Device" />
		</div><!-- END .button-row -->

	</form>
</div><!-- END #left-content -->