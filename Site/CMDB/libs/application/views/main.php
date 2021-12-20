<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>

<div id="left-content">
	<h1>Welcome to the Network / Voice Page</h1>
	<p>
	This site will allow a registered user to find and edit discovered devices on the Symcor network.
	</p>
	<p>
	A registered user is associated with a Symcor Business Unit.  This association is required to limit
	a user to only editing the properties of a device relevant to their Business Unit.
	</p>
</div><!-- END #left-content -->

<?php if (isset($loginForm)) { echo $loginForm; } ?>
