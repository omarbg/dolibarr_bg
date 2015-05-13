<?php
/* Copyright (C) 2009-2010 Regis Houssin <regis.houssin@capnetworks.com>
 * Copyright (C) 2011-2013 Laurent Destailleur <eldy@users.sourceforge.net>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

header('Cache-Control: Public, must-revalidate');
header("Content-type: text/html; charset=".$conf->file->character_set_client);

if (GETPOST('dol_hide_topmenu')) $conf->dol_hide_topmenu=1;
if (GETPOST('dol_hide_leftmenu')) $conf->dol_hide_leftmenu=1;
if (GETPOST('dol_optimize_smallscreen')) $conf->dol_optimize_smallscreen=1;
if (GETPOST('dol_no_mouse_hover')) $conf->dol_no_mouse_hover=1;
if (GETPOST('dol_use_jmobile')) $conf->dol_use_jmobile=1;

// If we force to use jmobile, then we reenable javascript
if (! empty($conf->dol_use_jmobile)) $conf->use_javascript_ajax=1;

$arrayofjs=array('/core/js/dst.js'.(empty($conf->dol_use_jmobile)?'':'?version='.urlencode(DOL_VERSION)));					// Javascript code on logon page only to detect user tz, dst_observed, dst_first, dst_second
$titleofloginpage=$langs->trans('Login').' @ '.$title;	// title is defined by dol_loginfunction in security2.lib.php. We must keep the @, some tools use it to know it is login page.

print top_htmlhead('',$titleofloginpage,0,0,$arrayofjs);
?>
<!-- BEGIN PHP TEMPLATE LOGIN.TPL.PHP -->

<body class="body bodylogin">

<?php if (empty($conf->dol_use_jmobile)) { ?>
<script type="text/javascript">
$(document).ready(function () {
	// Set focus on correct field
	<?php if ($focus_element) { ?>$('#<?php echo $focus_element; ?>').focus(); <?php } ?>		// Warning to use this only on visible element
});
</script>
<?php } ?>

<center>
<div class="login_vertical_align">

<form id="login" name="login" method="post" action="<?php echo $php_self; ?>">
<input type="hidden" name="token" value="<?php echo $_SESSION['newtoken']; ?>" />
<input type="hidden" name="loginfunction" value="loginfunction" />
<!-- Add fields to send local user information -->
<input type="hidden" name="tz" id="tz" value="" />
<input type="hidden" name="tz_string" id="tz_string" value="" />
<input type="hidden" name="dst_observed" id="dst_observed" value="" />
<input type="hidden" name="dst_first" id="dst_first" value="" />
<input type="hidden" name="dst_second" id="dst_second" value="" />
<input type="hidden" name="screenwidth" id="screenwidth" value="" />
<input type="hidden" name="screenheight" id="screenheight" value="" />
<input type="hidden" name="dol_hide_topmenu" id="dol_hide_topmenu" value="<?php echo $dol_hide_topmenu; ?>" />
<input type="hidden" name="dol_hide_leftmenu" id="dol_hide_leftmenu" value="<?php echo $dol_hide_leftmenu; ?>" />
<input type="hidden" name="dol_optimize_smallscreen" id="dol_optimize_smallscreen" value="<?php echo $dol_optimize_smallscreen; ?>" />
<input type="hidden" name="dol_no_mouse_hover" id="dol_no_mouse_hover" value="<?php echo $dol_no_mouse_hover; ?>" />
<input type="hidden" name="dol_use_jmobile" id="dol_use_jmobile" value="<?php echo $dol_use_jmobile; ?>" />

<table class="login_table_title center" summary="<?php echo dol_escape_htmltag($title); ?>">
<tr class="vmenu"><td align="center"><?php echo dol_escape_htmltag($title); ?></td></tr>
</table>
<br>

<div class="login_table">

<div id="login_line1">

<div id="login_left">

<table class="left" summary="Login pass">
<!-- Login -->
<tr>
<td valign="middle" class="loginfield"><strong><label for="username"><?php echo $langs->trans('Login'); ?></label></strong></td>
<td valign="middle" class="nowrap">
<span class="span-icon-user">
<input type="text" id="username" name="username" class="flat input-icon-user" size="15" maxlength="40" value="<?php echo dol_escape_htmltag($login); ?>" tabindex="1" autofocus="autofocus" />
</span>
</td>
</tr>
<!-- Password -->
<tr><td valign="middle" class="loginfield nowrap"><strong><label for="password"><?php echo $langs->trans('Password'); ?></label></strong></td>
<td valign="middle" class="nowrap">
<span class="span-icon-password">
<input id="password" name="password" class="flat input-icon-password" type="password" size="15" maxlength="30" value="<?php echo dol_escape_htmltag($password); ?>" tabindex="2" autocomplete="off" />
</span>
</td></tr>
<?php
if (! empty($hookmanager->resArray['options'])) {
	foreach ($hookmanager->resArray['options'] as $format => $option)
	{
		if ($format == 'table') {
			echo '<!-- Option by hook -->';
			echo $option;
		}
	}
}
?>
<?php
	if ($captcha) {
		// TODO: provide accessible captha variants
?>
	<!-- Captcha -->
	<tr><td valign="middle" class="loginfield nowrap"><label for="securitycode"><b><?php echo $langs->trans('SecurityCode'); ?></b></label></td>
	<td valign="top" class="nowrap none" align="left">

	<table class="login_table_securitycode" style="width: 100px;"><tr>
	<td>
	<span class="span-icon-security">
	<input id="securitycode" class="flat input-icon-security" type="text" size="6" maxlength="5" name="code" tabindex="4" />
	</span>
	</td>
	<td><img src="<?php echo DOL_URL_ROOT ?>/core/antispamimage.php" border="0" width="80" height="32" id="img_securitycode" /></td>
	<td><a href="<?php echo $php_self; ?>"><?php echo $captcha_refresh; ?></a></td>
	</tr></table>

	</td></tr>
<?php } ?>
</table>

</div> <!-- end div left -->

<div id="login_right">

<img alt="Logo" title="" src="<?php echo $urllogo; ?>" id="img_logo" />

</div>
</div>

<div id="login_line2" style="clear: both">

<!-- Button Connection -->
<br><input type="submit" class="button" value="&nbsp; <?php echo $langs->trans('Connection'); ?> &nbsp;" tabindex="5" />

<?php
if ($forgetpasslink || $helpcenterlink)
{
	$moreparam='';
	if ($dol_hide_topmenu)   $moreparam.=(strpos($moreparam,'?')===false?'?':'&').'dol_hide_topmenu='.$dol_hide_topmenu;
	if ($dol_hide_leftmenu)  $moreparam.=(strpos($moreparam,'?')===false?'?':'&').'dol_hide_leftmenu='.$dol_hide_leftmenu;
	if ($dol_no_mouse_hover) $moreparam.=(strpos($moreparam,'?')===false?'?':'&').'dol_no_mouse_hover='.$dol_no_mouse_hover;
	if ($dol_use_jmobile)    $moreparam.=(strpos($moreparam,'?')===false?'?':'&').'dol_use_jmobile='.$dol_use_jmobile;

	echo '<br>';
	echo '<div align="center" style="margin-top: 4px;">';
	if ($forgetpasslink) {
		echo '<a class="alogin" href="'.DOL_URL_ROOT.'/user/passwordforgotten.php'.$moreparam.'">(';
		echo $langs->trans('PasswordForgotten');
		if (! $helpcenterlink) echo ')';
		echo '</a>';
	}

	if ($forgetpasslink && $helpcenterlink) echo '&nbsp;-&nbsp;';

	if ($helpcenterlink) {
		$url=DOL_URL_ROOT.'/support/index.php'.$moreparam;
		if (! empty($conf->global->MAIN_HELPCENTER_LINKTOUSE)) $url=$conf->global->MAIN_HELPCENTER_LINKTOUSE;
		echo '<a class="alogin" href="'.dol_escape_htmltag($url).'" target="_blank">';
		if (! $forgetpasslink) echo '(';
		echo $langs->trans('NeedHelpCenter');
		echo ')</a>';
	}
	echo '</div>';
}

if (isset($conf->file->main_authentication) && preg_match('/openid/',$conf->file->main_authentication))
{
	$langs->load("users");

	//if (! empty($conf->global->MAIN_OPENIDURL_PERUSER)) $url=
	echo '<br>';
	echo '<div align="center" style="margin-top: 4px;">';

	$url=$conf->global->MAIN_AUTHENTICATION_OPENID_URL;
	if (! empty($url)) print '<a class="alogin" href="'.$url.'">'.$langs->trans("LoginUsingOpenID").'</a>';
	else
	{
		$langs->load("errors");
		print '<font class="warning">'.$langs->trans("ErrorOpenIDSetupNotComplete",'MAIN_AUTHENTICATION_OPENID_URL').'</font>';
	}

	echo '</div>';
}


?>

</div>

</div>

</form>




<?php if (! empty($_SESSION['dol_loginmesg']))
{
?>
	<div class="center login_main_message" style="max-width: 500px; margin-left: 10px; margin-right: 10px;"><div class="error">
	<?php echo $_SESSION['dol_loginmesg']; ?>
	</div></div>
<?php
}
?>

<?php if ($main_home)
{
?>
	<div class="center login_main_home" style="max-width: 80%">
	<?php echo $main_home; ?>
	</div><br>
<?php
}
?>

<!-- authentication mode = <?php echo $main_authentication ?> -->
<!-- cookie name used for this session = <?php echo $session_name ?> -->
<!-- urlfrom in this session = <?php echo isset($_SESSION["urlfrom"])?$_SESSION["urlfrom"]:''; ?> -->

<!-- Common footer is not used for login page, this is same than footer but inside login tpl -->

<?php if (! empty($conf->global->MAIN_HTML_FOOTER)) print $conf->global->MAIN_HTML_FOOTER; ?>

<?php
// Google Analytics (need Google module)
if (! empty($conf->google->enabled) && ! empty($conf->global->MAIN_GOOGLE_AN_ID))
{
	if (empty($conf->dol_use_jmobile))
	{
		print "\n";
		print '<script type="text/javascript">'."\n";
		print '  var _gaq = _gaq || [];'."\n";
		print '  _gaq.push([\'_setAccount\', \''.$conf->global->MAIN_GOOGLE_AN_ID.'\']);'."\n";
		print '  _gaq.push([\'_trackPageview\']);'."\n";
		print ''."\n";
		print '  (function() {'."\n";
		print '    var ga = document.createElement(\'script\'); ga.type = \'text/javascript\'; ga.async = true;'."\n";
		print '    ga.src = (\'https:\' == document.location.protocol ? \'https://ssl\' : \'http://www\') + \'.google-analytics.com/ga.js\';'."\n";
		print '    var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(ga, s);'."\n";
		print '  })();'."\n";
		print '</script>'."\n";
	}
}
?>

<?php
// Google Adsense
if (! empty($conf->google->enabled) && ! empty($conf->global->MAIN_GOOGLE_AD_CLIENT) && ! empty($conf->global->MAIN_GOOGLE_AD_SLOT))
{
	if (empty($conf->dol_use_jmobile))
	{
?>
	<div align="center"><br>
		<script type="text/javascript"><!--
			google_ad_client = "<?php echo $conf->global->MAIN_GOOGLE_AD_CLIENT ?>";
			google_ad_slot = "<?php echo $conf->global->MAIN_GOOGLE_AD_SLOT ?>";
			google_ad_width = <?php echo $conf->global->MAIN_GOOGLE_AD_WIDTH ?>;
			google_ad_height = <?php echo $conf->global->MAIN_GOOGLE_AD_HEIGHT ?>;
			//-->
		</script>
		<script type="text/javascript"
			src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
		</script>
	</div>
<?php
	}
}
?>


</div>
</center>	<!-- end of center -->


</body>
</html>
<!-- END PHP TEMPLATE -->