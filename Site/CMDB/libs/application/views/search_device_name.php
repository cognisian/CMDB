<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<div id="left-content">
	<h1>Search For Device By Name</h1>
	<p>
	This page will allow you to search for an existing Symcor network device by
	its device name.
	</p>

	<p>
	The search uses an auto complete combo box.  Start by typing the first 3
	characters of a host name and a list of potential matches will be shown.
	Continue typing and the list will further be reduced.  If no items are listed
	then there is no device which begins with those characters.
	</p>

	<div id="search-device">
		<form id="search-device-name" name="search-device-name" method="get" action="/device/edit" >
			<div class="form-row">
				<label for="name">Device Name : </label>
				<div id="autocomplete-device" class="autocompleter">
					<input type="text" id="name" name="name" class="autocompleter-text" autocomplete="off" />
						<div id="autocompleter-list-wrapper">
							<div id="name-suggestions" class="autocompleter-list"></div>
						</div>
				</div><!-- END #autocomplete-device -->
				<div id="indicator" style="display: none">
					<img src="/gfx/page_wait_28.gif" alt="Working..." />
				</div>
			</div><!-- END .form-row -->
			<div class="form-row">
				<input type="submit" id="search-submit" class="submit med" value="Find Device" tabindex="3" />
			</div><!-- END .form-row -->
		</form>
	</div><!-- END #search-device -->

</div><!-- END #left-content -->