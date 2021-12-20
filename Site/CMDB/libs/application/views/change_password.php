<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>

<p>The new password will be validated to ensure that it is sufficiently complex and
	MUST meet the following conditions:</p>
<ul>
	<li>Minimum 8 characters</li>
	<li>Maximum 16 characters</li>
	<li>Contain at least 1 lower case letter</li>
	<li>Contain at least 1 upper case letter</li>
	<li>Contain at least 1 digit</li>
	<li>Contain at least 1 punctuation character</li>
</ul>

<form id="change_password" method="post" action="/user/change">
	<div class="login-error">
		<?php if (isset($error)) echo $error; ?>
	</div>
	<fieldset class="login">
		<legend>Change User Password</legend>
		<div class="form-row">
			<label for="curr_username">User Name</label>
			<input id="curr_username" name="username" type="text" value="<?php echo $logon ?>" />
		</div>
		<div class="form-row">
			<label for="curr_password">Current Pasword</label>
			<input id="curr_password" name="curr_password" type="password""/>
		</div>
		<div class="form-row">
			<label for="new_password">New Password</label>
			<input id="new_password" name="new_password" type="password""/>
		</div>
		<div class="form-row">
			<label for="confirm_password">Confirm Password</label>
			<input id="confirm_password" name="confirm_password" type="password""/>
		</div>
		<div class="form-row">
			<input id="password_change_submit" name="password_change_submit" type="submit" value="Change Password" class="submit" />
		</div>
	</fieldset>
</form>