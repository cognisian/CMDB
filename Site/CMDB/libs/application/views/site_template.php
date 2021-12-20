<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>

		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

		<link rel="stylesheet" media="all" type="text/css" href="style/reset.css" />
		<link rel="stylesheet" media="all" type="text/css" href="style/layout.css" />
		<link rel="stylesheet" media="all" type="text/css" href="style/cmdb.css" />
		<link rel="stylesheet" media="all" type="text/css" href="style/navigation.css" />

		<script type="text/javascript" src="script/prototype.js"></script>
		<script type="text/javascript" src="script/scriptaculous.js"></script>
		<script type="text/javascript" src="script/utilities.js"></script>

		<?php if (isset($javascript)) echo $javascript; ?>

		<title><?php echo html::specialchars($title) ?></title>

	</head>

	<body id="<?php echo $pageID;  ?>">

		<div id="frame">

			<div id="topcontent">
				<div id="banner">
					<img id="subtitle" src="/gfx/net_seg.png"/>
					<div id="logo">
					</div>
				</div>
			</div><!-- END #topcontent -->

			<div id="navigation-wrapper" >
				<ul id="nav2"  class="clearfloat">
					<li class="first"><a href="/device" class="selected">Device Management</a>
						<ul>
							<li><a href="/device">Edit Device</a></li>
							<li><a href="/device/add">Add Device</a></li>
						</ul>
					</li>
					<li><a href="">Device Search</a>
						<ul>
							<li><a href="/search/device_name">By Device Name</a></li>
							<li><a href="/search/device_addr">By Device IP</a></li>
							<li><a href="/search/device_type">By Device Type</a></li>
						</ul>
					</li>
					<li><a href="#">Device Reports</a>
						<ul>
							<li><a href="/report/device_site">By Site</a></li>
							<li><a href="/report/device_area">By Operational Area</a></li>
							<li><a href="/report/device_switch">By Switch</a></li>
							<li><a href="/report/device_voice">By Voice</a></li>
							<li><a href="/report/device_busunit">By Business Unit</a></li>
							<li><a href="/report/device_area_busunit">By Area and Business Unit</a></li>
						</ul>
					</li>
					<li class="first"><a href="/suspect" class="selected">Suspect Reports</a>
						<ul>
							<li><a href="/suspect/device">Missing Device Properties</a></li>
							<li><a href="/suspect/network">Missing Network Properties</a></li>
							<li><a href="/suspect/services">Missing Service Properties</a></li>
						</ul>
					</li>
					<?php if (!isset($userName) || $userName == '') : ?>
						<li class="last"><a href="/main">Login</a></li>
					<?php else : ?>
						<li class="last"><a href="/logout">Logout <?php echo $userName; ?></a></li>
					<?php endif; ?>
				</ul>
			</div><!-- END #navigation-wrapper -->

			<div id="content">

				<script type="text/javascript">
					sfHover = function() {
						var sfEls = document.getElementById("nav2").getElementsByTagName("LI");
						for (var i=0; i<sfEls.length; i++) {
							sfEls[i].onmouseover=function() {
								this.className+=" sfhover";
							}
							sfEls[i].onmouseout=function() {
								this.className=this.className.replace(new RegExp(" sfhover\\b"), "");
							}
						}
					}
					if (window.attachEvent) window.attachEvent("onload", sfHover);
				</script>

				<?php echo $content; ?>

			</div>

			<div id="footer">
				<p>The Network/Voice tool allows users to manage the properties,
				relevant to their Business Unit, of devices connected to the Symcor network.
				</p>
				<p>Symcor BAM Group &copy; 2009</p>
			</div> <!-- END #footer -->

		</div><!-- END #frame -->

	</body>
</html>
