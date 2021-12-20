<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<div id="left-content">
	<div class="page-message">
		<h1>Search For Device By IP Address</h1>
		<p>
		This page will allow you to search for an existing Symcor network devices by
		specifing an IP address, IP address range or a subnet.  When specifing the subnet
		mask the CIDR format is used.
		</p>
	</div>

	<div id="search-device">
		<form id="search-device-addr" name="search-device-addr" method="get" action="/search/list_devices" >
			<div class="form-row">
				<label for="addr_select">IP Address : </label>
				<input type="radio" id="addr_select" name="addr_select" value="IP Address" checked="checked"
						onchange="$('ip_addr').enable();
								$('from_ip_addr').disable(); $('to_ip_addr').disable();
								$('ip_subnet').disable();" />

				<input type="text" id="ip_addr" name="ip_addr" value="0.0.0.0" maxlength="15" size="12" />
			</div>
			<div class="form-row">
				<label for="addr_select">IP Address Range : </label>
				<input type="radio" id="addr_select" name="addr_select" value="IP Address Range"
							onchange="$('from_ip_addr').enable(); $('to_ip_addr').enable();
									$('ip_addr').disable();	$('ip_subnet').disable();" />

				<div class="form-row">
					<label>From : </label>
					<input type="text" id="from_ip_addr" name="from_ip_addr" value="0.0.0.0" maxlength="15" size="12"
								disabled="disabled" />
				</div>
				<div class="form-row">
					<label>To : </label>
					<input type="text" id="to_ip_addr" name="to_ip_addr" value="0.0.0.0" maxlength="15" size="12"
								disabled="disabled" />
				</div>
			</div>
			<div class="form-row">
				<label for="addr_select">IP Subnet : </label>
				<input type="radio" id="addr_select" name="addr_select" value="IP Subnet"
							onchange="$('ip_subnet').enable();
									$('ip_addr').disable(); $('from_ip_addr').disable();
									$('to_ip_addr').disable();" />

				<input type="text" id="ip_subnet" name="ip_subnet" value="0.0.0.0/24" maxlength="18" size="15"
							disabled="disabled" />
			</div><!-- END .form-row -->

			<div class="form-row">
				<input type="submit" id="search-submit" class="submit" value="Find Devices" />
			</div><!-- END #search-device -->

		</form>
	</div><!-- END #search-device -->

</div><!-- END #left-content -->