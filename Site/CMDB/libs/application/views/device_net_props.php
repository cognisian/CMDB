<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>

<div class="sub-prop">

	<h2>Interface : <span style="font-variant: normal;">Add New Interface</span></h2>

	<form action="/device/update/network-props" method="post">
		<input type="hidden" id="deviceName" name = "deviceName" value="<?php echo $device->name; ?>">
		<input type="hidden" id="interfaceID" name = "interfaceID" value="">

		<div class="props-row">

			<span class="left">
				<label for="ip_addr">IP Address</label>
				<input type="text" id="ip_addr" name="ip_addr" value="0"
						class="wide
							<?php
								$test = NULL;
								if (isset($device->FunctionalArea)) {
									$test = $device->FunctionalArea->name;
								}
								echo inputelems::enable($test, $userFuncAreas);
							?>"
				/>
			</span><!-- END .left -->
		</div><!-- END .props-row -->

		<div class="props-row">

			<span class="left">
				<label for="nic">NIC Name</label>
				<input type="text" id="nic" name="nic" value=""
						class="wide
							<?php
								$test = NULL;
								if (isset($device->FunctionalArea)) {
									$test = $device->FunctionalArea->name;
								}
								echo inputelems::enable($test, $userFuncAreas);
							?>"
				/>
			</span><!-- END .left -->

			<span class="right">
				<label for="mac">MAC Address</label>
				<input type="text" id="mac" name="mac" value=""
						class="wide <?php
								$test = NULL;
								if (isset($device->FunctionalArea)) {
									$test = $device->FunctionalArea->name;
								}
								echo inputelems::enable($test, $userFuncAreas);
							?>"
				/>
			</span><!-- END .right -->
		</div><!-- END .props-row -->

		<?php if (isset($device->FunctionalArea) && $device->FunctionalArea->name == 'Network') : ?>
		<div class="props-row-triple">

			<span class="left">
				<label for="conn_blade">Blade</label>
				<input type="text" id="conn_blade" name="conn_blade" value=""
						class="wide <?php
								$test = NULL;
								if (isset($device->FunctionalArea)) {
									$test = $device->FunctionalArea->name;
								}
								echo inputelems::enable($test, $userFuncAreas);
							?>"
				/>
			</span><!-- END .left -->

			<span class="right">
				<label for="conn_port">Port</label>
				<input type="text" id="conn_port" name="conn_port" value=""
						class="wide <?php
								$test = NULL;
								if (isset($device->FunctionalArea)) {
									$test = $device->FunctionalArea->name;
								}
								echo inputelems::enable($test, $userFuncAreas);
							?>"
				/>
			</span><!-- END .right -->

			<span class="left">
				<label for="conn_vlan">VLAN</label>
				<input type="text" id="conn_vlan" name="conn_vlan" value=""
						class="wide <?php
								$test = NULL;
								if (isset($device->FunctionalArea)) {
									$test = $device->FunctionalArea->name;
								}
								echo inputelems::enable($test, $userFuncAreas);
							?>"
				/>
			</span><!-- END .left -->

			<span class="right">
				<label for="conn_speed">Connection Speed</label>
				<input type="text" id="conn_speed" name="conn_speed" value=""
						class="wide <?php
								$test = NULL;
								if (isset($device->FunctionalArea)) {
									$test = $device->FunctionalArea->name;
								}
								echo inputelems::enable($test, $userFuncAreas);
							?>"
				/>
			</span><!-- END .right -->

			<span class="left">
				<label for="conn_medium">Connection Medium</label>
				<input type="text" id="conn_medium" name="conn_medium" value=""
						class="wide <?php
								$test = NULL;
								if (isset($device->FunctionalArea)) {
									$test = $device->FunctionalArea->name;
								}
								echo inputelems::enable($test, $userFuncAreas);
							?>"
				/>
			</span><!-- END .left -->

			<span class="right">
				<label for="duplex">Connection Type</label>
				<select id="duplex" name="duplex" title="Duplex"
						class="wide <?php
								$test = NULL;
								if (isset($device->FunctionalArea)) {
									$test = $device->FunctionalArea->name;
								}
								echo inputelems::enable($test, $userFuncAreas);
							?>" >
					<?php
						foreach ($duplexTypes as $duplexType) {
							echo "<option value=\"{$duplexType['code']}\">";
							echo $duplexType['name'];
							echo "</option>";
						}
					?>
				</select>
			</span><!-- END .prop-col -->
		</div><!-- END .props-row -->
		<?php endif ?>

		<div class="props-row-comment">
			<div class="handle">
				<div class="handle-title">Comments</div>
				<div class="handle-icons">
					<img class="minimize" src="/gfx/down.gif" onclick="Effect.BlindDown('new-intf-comment-container'); return false;" />
					<img class="maximize" src="/gfx/up.gif" onclick="Effect.BlindUp('new-intf-comment-container'); return false;" />
				</div>
			</div><!-- END .handle -->

			<div id="new-intf-comment-container" class="comment-container" style="display: none">
				<span class="left">
					<label for="intf_comment">New Comment</label><br />
					<textarea name="intf_comment" class="input"></textarea>
				</span><!-- END .left -->
			</div>
		</div><!-- END .props-row -->

		<div class="button-row">
			<input type="submit" id="submit-new-dev-net-props" name="submit-new-dev-net-props"
					value="Add New Network Interface"
					class="med
						<?php
							$test = array('IT Operations');
							if (isset($device->FunctionalArea)) {
								$test[] = $device->FunctionalArea->name;
							}
							echo inputelems::enable($test, $userFuncAreas, FALSE);
						?>" />
		</div><!-- END .button-row -->
	</form>

</div><!-- END .sub-props -->
<?php foreach ($device->NetworkProperty as $netProp) : ?>

	<div class="sub-prop">

		<h2>Interface : <span style="font-variant: normal;"><?php echo $netProp->nic; ?></span></h2>

		<form action="/device/update/network-props" method="post">
			<input type="hidden" id="deviceName" name = "deviceName" value="<?php echo $device->name; ?>">
			<input type="hidden" id="interfaceID" name = "interfaceID" value="<?php echo $netProp->id; ?>">

			<div class="props-row">

				<span class="left">
					<label for="ip_addr">IP Address</label>
					<input type="text" id="ip_addr" name="ip_addr"
							value="<?php echo long2ip($netProp->ip_addr); ?>"
							class="wide
								<?php
									$test = NULL;
									if (isset($device->FunctionalArea)) {
										$test[] = $device->FunctionalArea->name;
									}
									echo inputelems::enable($test, $userFuncAreas);
								?>" />
				</span><!-- END .left -->
			</div><!-- END .props-row -->

			<div class="props-row">

				<span class="left">
					<label for="nic">NIC Name</label>
					<input type="text" id="nic" name="nic"
							value="<?php echo $netProp->nic; ?>"
							class="wide
								<?php
									$test = NULL;
									if (isset($device->FunctionalArea)) {
										$test = $device->FunctionalArea->name;
									}
									echo inputelems::enable($test, $userFuncAreas);
								?>"
					/>
				</span><!-- END .left -->

				<span class="right">
					<label for="mac">MAC Address</label>
					<input type="text" id="mac" name="mac"
							value="<?php echo $netProp->mac; ?>"
							class="wide
								<?php
									$test = NULL;
									if (isset($device->FunctionalArea)) {
										$test = $device->FunctionalArea->name;
									}
									echo inputelems::enable($test, $userFuncAreas);
								?>" />
				</span><!-- END .right -->
			</div><!-- END .props-row -->

			<?php if (isset($device->FunctionalArea) && $device->FunctionalArea->name == 'Network') : ?>
			<div class="props-row-triple">

				<span class="left">
					<label for="conn_blade">Blade</label>
					<input type="text" id="conn_blade" name="conn_blade"
							value="<?php echo $netProp->conn_blade; ?>"
							class="wide <?php echo inputelems::enable('IT Operations', $userFuncAreas); ?>" />
				</span><!-- END .left -->

				<span class="right">
					<label for="conn_port">Port</label>
					<input type="text" id="conn_port" name="conn_port"
							value="<?php echo $netProp->conn_port; ?>"
							class="wide <?php echo inputelems::enable('IT Operations', $userFuncAreas); ?>" />
				</span><!-- END .right -->

				<span class="left">
					<label for="conn_vlan">VLAN</label>
					<input type="text" id="conn_vlan" name="conn_vlan"
							value="<?php echo $netProp->conn_vlan; ?>"
							class="wide <?php echo inputelems::enable('IT Operations', $userFuncAreas); ?>" />
				</span><!-- END .left -->

				<span class="right">
					<label for="conn_speed">Connection Speed</label>
					<input type="text" id="conn_speed" name="conn_speed"
							value="<?php echo $netProp->conn_speed; ?>"
							class="wide <?php echo inputelems::enable('IT Operations', $userFuncAreas); ?>" />
				</span><!-- END .right -->

				<span class="left">
					<label for="conn_medium">Connection Medium</label>
					<input type="text" id="conn_medium" name="conn_medium"
							value="<?php echo $netProp->conn_medium; ?>"
							class="wide <?php echo inputelems::enable('IT Operations', $userFuncAreas); ?>" />
				</span><!-- END .left -->

				<span class="right">
					<label for="duplex">Connection Type</label>
					<select id="duplex" name="duplex" title="Duplex"
							class="wide <?php echo inputelems::enable('IT Operations', $userFuncAreas); ?>" >
						<?php
							foreach ($duplexTypes as $duplexType) {
								echo "<option value=\"{$duplexType['code']}\" ";
									if ($netProp->duplex == $duplexType['code']) {
										echo 'selected="selected"';
									}
									echo " >";
									echo $duplexType['name'];
									echo "</option>";
							}
						?>
					</select>
				</span><!-- END .right -->
			</div><!-- END .props-row -->
			<?php endif ?>

			<div class="props-row-comment">
				<div class="handle">
					<div class="handle-title">Comments</div>
					<div class="handle-icons">
						<img class="minimize" src="/gfx/down.gif" onclick="Effect.BlindDown('<?php echo $netProp->id . '-'; ?>comment-container'); return false;" />
						<img class="maximize" src="/gfx/up.gif" onclick="Effect.BlindUp('<?php echo $netProp->id . '-'; ?>comment-container'); return false;" />
					</div>
				</div><!-- END .handle -->

				<div id="<?php echo $netProp->id . '-'; ?>comment-container" class="comment-container" style="display: none">
					<span class="left">
						<label for="intf_comment">New Comment</label><br />
						<textarea name="intf_comment" class="input"></textarea>
					</span><!-- END .left -->

					<span class="right">
						<label for="intf_comments">Comments</label><br />
						<textarea name="intf_comments" class="collection"><?php foreach($netProp->Comments as $comment) {
								echo '[' . $comment->created . ']  ';
								echo $comment->comment . "\n";
							}?></textarea>
					</span><!-- END .right -->
				</div>
			</div><!-- END .props-row -->

			<div class="button-row">
				<input type="submit"
						id="submit-net-<?php echo $netProp->nic; ?>-props" name="submit-net-<?php echo $netProp->nic; ?>-props"
						class="med" value="Update Network Properties"
						<?php
							$test = array('IT Operations');
							if (isset($device->FunctionalArea)) {
								$test[] = $device->FunctionalArea->name;
							}
							echo inputelems::enable($test, $userFuncAreas, FALSE);
						?> />
			</div><!-- END .button-row -->
		</form>

	</div><!-- END .sub-props -->
<?php endforeach ?>
