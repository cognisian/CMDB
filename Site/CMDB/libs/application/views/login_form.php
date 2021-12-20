<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>

<form id="login" method="post" action="/login">
	<fieldset class="login">
		<legend>Logon to Network Segmentation Tool</legend>
		<div class="form-row">
			<label for="username">User Name</label>
			<input id="username" name="username" type="text" />
		</div>
		<div class="form-row">
			<label for="password">Password</label>
			<input id="password" name="password" type="password""/>
		</div>
		<div class="form-row">
			<input type="submit" id="logon_submit" name="logon_submit" value="Logon" class="submit med" />
		</div>
	</fieldset>
</form>