<?php
/**
 * index.php [ www.easyphp.org ]
 * PHP version 5
 * @author   Laurent Abbal <laurent@abbal.com>
 * @license  http://www.gnu.org/licenses/gpl-2.0.html  GPL License 2 
 * @version  5.3.8.1
 * @link     http://www.easyphp.org
 */

include("functions.inc.php");

if (isset($HTTP_GET_VARS)){ while(list($name, $value) = each($HTTP_GET_VARS)) { $$name = $value; } }
if (!isset($_GET['to'])) $_GET['to'] = '';
if (!isset($_GET['display'])) $_GET['display'] = '';
if (!isset($_GET['editnotes'])) $_GET['editnotes'] = '';
if (!isset($_POST['to'])) $_POST['to'] = '';
if (!isset($_GET['exts'])) $_GET['exts'] = '';
if (!isset($_GET['exts'])) $directory = '';


if ($_GET['display'] == "phpinfo") {
	ob_start();
	phpinfo();
	$phpinfo = ob_get_contents();
	ob_end_clean();
	preg_match_all("=<body[^>]*>(.*)</body>=siU", $phpinfo, $tab);
	$phpinfo = $tab[1][0];
	$phpinfo = str_replace(";", "; ", $phpinfo);
	$phpinfo = str_replace(",", ", ", $phpinfo);
}

if ($_GET['display'] == "phpcredits") {
	ob_start();
	phpcredits(CREDITS_ALL - CREDITS_FULLPAGE);
	$phpcredits = ob_get_contents();
	ob_end_clean();
	$phpcredits = str_replace('<h1>PHP Credits</h1>', '', $phpcredits);
}


// Port initialization
if ($_SERVER['SERVER_PORT'] == '8887') {	
	$source = "../conf_files/httpd.conf";
	$httpdconf = file_get_contents($source);
	
	// Backup old httpd.conf
	$backup_httpdconf = "../conf_files/httpd_" . date("Y-m-d@U") . ".conf";
	$fp_backup = fopen($backup_httpdconf, 'w');
	fputs($fp_backup, $httpdconf);
	fclose($fp_backup);	
	
	// Search and replace
	if (!check_port(80)) {
		$new_port = 80;
	} elseif (!check_port(8080)) {
		$new_port = 8080;
	} elseif (!check_port(8888)) {
		$new_port = 8888;
	}	
	$search = ':' . $_SERVER['SERVER_PORT'];
	$replace = ':' . $new_port;
	$httpdconf = str_replace($search, $replace, $httpdconf);

	// Save new httpd.conf
	$fp_update = fopen('../conf_files/httpd.conf', 'w');
	fputs($fp_update, $httpdconf);
	fclose($fp_update);	

	$redirect = "http://" . $_SERVER['SERVER_NAME'] . ":" . $new_port . "/home/index.php";
	header("Location: " . $redirect); 
	exit;
}


$www = @opendir($_SERVER["DOCUMENT_ROOT"]);
$www_files = array();
while ($file = @readdir($www)){
	if (($file != '..') && ($file != '.') && ($file != '') && (@is_dir($_SERVER["DOCUMENT_ROOT"]."/".$file))){ 
		// XSS vulnerability fixed
		// http://blog.madpowah.org/archives/2011/07/index.html#e2011-07-20T00_31_36.txt
		$www_files[] = addslashes($file);
	}
	sort($www_files);
}
@closedir($www);
clearstatcache();


$modules = @opendir("../modules");
$modules_files = array();
while ($modules_file = @readdir($modules)){
	if (($modules_file != '..') && ($modules_file != '.') && ($modules_file != '') && (@is_dir("../modules/".$modules_file)) && @file_exists("../modules/".$modules_file."/easyphp+.php")){ 
		$modules_files[] = $modules_file;
	}
	sort($modules_files);
}
@closedir($modules);
clearstatcache();


// Notifications
include("notification.php"); 
if (date('Ymd') != $notification['check_date']) {

	$context = stream_context_create(array('http' => array('timeout' => 1)));
	$content = @file_get_contents('http://www.easyphp.org/notifications/update.txt', 0, $context);
	
	if (!empty($content)) {
		$content_array = explode('#', $content);	
				
		if ($content_array[0] != $notification['date']) {			
			$new_notification = fopen('notification.php', "w");
			$new_content = '<?php $notification = array(\'check_date\'=>\'' . date('Ymd') . '\',\'date\'=>\'' . $content_array[0] . '\',\'status\'=>\'1\',\'link\'=>\'' . $content_array[1] . '\',\'message\'=>\'' . $content_array[2] . '\'); ?>';
			fputs($new_notification,$new_content);
			fclose($new_notification);	
			$redirect = "http://" . $_SERVER['HTTP_HOST'] . "/home/index.php";
			header("Location: " . $redirect); 
			exit;	
		}
	}
	
	$new_notification = fopen('notification.php', "w");
	$new_content = '<?php $notification = array(\'check_date\'=>\'' . date('Ymd') . '\',\'date\'=>\'' . $notification['date'] . '\',\'status\'=>\'' . $notification['status'] . '\',\'link\'=>\'' . $notification['link'] . '\',\'message\'=>\'' . $notification['message'] . '\'); ?>';
	fputs($new_notification,$new_content);
	fclose($new_notification);
};


include("i18n.inc.php");
include("vhosts_functions.php");
include("alias.inc.php");
include("versions.inc.php"); 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>[EasyPHP] - <?php echo $administration ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="shortcut icon" href="images_easyphp/easyphp_favicon.ico" />
<link rel="stylesheet" href="styles.css" type="text/css" />
<body>

<div id="top">
	<div class="container">
		<?php
		// Notification
		if ($notification['status'] == 1) {
			echo '<div class="notification_on"><a href="notification_redirect.php" title="' . $notification['message'] . '" target="_blank">!</a></div>';
		} else {
			echo '<div class="notification_off"><a href="notification_redirect.php" title="' . $notification['message'] . '" target="_blank">!</a></div>';
		}
		?>
		<img src="images_easyphp/top_version.gif" width="81" height="40" alt="version" class="version" />
		<img src="images_easyphp/top_title.gif" width="226" height="40" alt="EasyPHP" border="0" class="title" />
		<div class="website"><a href="http://www.easyphp.org" target="_blank">www.easyphp.org</a></div>
		<div class="help"><a href="<?php echo $lang ?>/index.html" target="_blank"><?php echo $help ?></a></div>
		<?php echo $lang_select; ?>	
		<br style="clear:both;" />
	</div>
</div>

<div id="main">

	<?php 
	//= CONFIGURATION ======================================================================================= 		
	$myini_array = file("../mysql/my.ini");
	$key_datadir =  key(preg_grep("/^datadir/", $myini_array));
	$mysql_datadir_array = explode("\"",$myini_array[$key_datadir]);
	$mysql_datadir = str_replace("/","\\", $mysql_datadir_array[1]);
	//======================================================================================================= ?>
	
	<?php
	// DONATION
	if ($_GET['to'] == "donate") {
		?>
		<div class="backhomepage"><a href="index.php"><?php echo $backhomepage; ?></a></div>
		<div class="menu_display">
			<div class="donation">
				<p><?php echo $donate_text; ?></p>
				<a href="https://sourceforge.net/donate/index.php?group_id=14045&amt=5&type=0" title="Donate 5 USD"><img src="images_easyphp/don_5.png" width="34" height="13" alt="Donate 5 USD" title="Donate 5 USD" border="0" /></a><a href="https://sourceforge.net/donate/index.php?group_id=14045&amt=10&type=0" title="Donate 10 USD"><img src="images_easyphp/don_10.png" width="34" height="13" alt="Donate 10 USD" title="Donate 10 USD" border="0" /></a><a href="https://sourceforge.net/donate/index.php?group_id=14045&amt=20&type=0" title="Donate 20 USD"><img src="images_easyphp/don_20.png" width="34" height="13" alt="Donate 20 USD" title="Donate 20 USD" border="0" /></a><a href="https://sourceforge.net/donate/index.php?group_id=14045&amt=50&type=0" title="Donate 50 USD"><img src="images_easyphp/don_50.png" width="34" height="13" alt="Donate 50 USD" title="Donate 50 USD" border="0" /></a><a href="https://sourceforge.net/donate/index.php?group_id=14045&amt=100&type=0" title="Donate 100 USD"><img src="images_easyphp/don_100.png" width="34" height="13" alt="Donate 100 USD" title="Donate 100 USD" border="0" /></a><a href="https://sourceforge.net/donate/index.php?group_id=14045&amt=250&type=0" title="Donate 250 USD"><img src="images_easyphp/don_250.png" width="34" height="13" alt="Donate 250 USD" title="Donate 250 USD" border="0" /></a>
			</div>
		</div>
		</div></body></html>
		<?php
		exit; // close tags
	}
	
	// ADD VIRTUAL HOSTS
	if ($_GET['to'] == "add_vhost_1") {
		echo '<div class="backhomepage"><a href="index.php">BACK TO HOMEPAGE</a></div>';
		include ("vhosts_add.php"); ?>
		</div></body></html>
		<?php
		exit; // close tags
	}
	
	// ADD ALIAS
	if ($_GET['to'] == "add_alias_1") {
		?>
		<div class="backhomepage"><a href="index.php"><?php echo $backhomepage; ?></a></div>
		<div class='menu_display'>
			<h5><?php echo $menu_alias_add ?></h5>
			<div class="add_alias">
				<form method="post" action="index.php">
					<div>
						<div><span>1.</span> <?php echo $alias_1 ?></div>
						<div><span>2.</span> <?php echo $alias_2 ?></div>
						<input type="text" name="alias_name" class="input" style="width:300px" />
						<div><span>3.</span> <?php echo $alias_3 ?></div>
						<input type="text" name="alias_link" class="input" style="width:428px" />
						<input type="hidden" name="to" value="add_alias_2" />
						
						<div style="width:430px;text-align:center;padding:5px;margin:10px 0px 0px 10px;border:1px solid #EFCE1D;background-color:#FBD825;color:#895902;-moz-border-radius:2px;-khtml-border-radius:2px;-webkit-border-radius:2px;border-radius:2px;">
							<?php echo $t_warning_phpini; ?>
						</div>						
										
						<input type="submit" value="<?php echo $t_warning_save; ?>" class="submit" />
					</div>
				</form>
			</div>
			<br />
		</div>
		</div></body></html>
		<?php
		exit; // close tags				
	} elseif ($_POST['to'] == "add_alias_2") {
		?>
		<div class='menu_display'>
			<?php
			if ($_POST['alias_name'] == "") {
				echo "<div class='add_alias'><a href=\"javascript:history.back()\">&laquo;  $back</a> $alias_warning_1</div>";				
			} elseif ($_POST['alias_link'] == "") {
				echo "<div class='add_alias'><a href=\"javascript:history.back()\">&laquo;  $back</a> $alias_warning_2</div>";						
			} elseif (($_POST['alias_link'] != "") && (!is_dir($_POST['alias_link']))) 	{
				echo "<div class='add_alias'><a href=\"javascript:history.back()\">&laquo;  $back</a> $alias_warning_3</div>";						
			} elseif ($name_test == FALSE) {
				echo "<div class='add_alias'><a href=\"javascript:history.back()\">&laquo;  $back</a> $alias_warning_4</div>";						
			} ?>
		</div>
		</div></body></html>
		<?php
		exit; // close tags	
	}	



	if (($_GET['to'] == "php-page") OR ($_GET['to'] == "database-page") OR ($_GET['to'] == "server-page") OR ($_GET['to'] == "debugging-page")) {echo '<div class="backhomepage"><a href="index.php">' . $backhomepage . '</a></div>';} ?>

	<div id="banner">
	
		<div class="block" style="width:85px;">
			<?php
			$block_style = ($_GET['to'] == "php-page") ? "block_on":"block_off";
			if ($_GET['to'] == "") $block_style = "block_main";
			echo '<span class="' . $block_style . '">' . $t_banner_php . '</span>';
			?>
			<a href='index.php?to=php-page' class='settings'><img src="images_easyphp/edit.png" width="10" height="13" alt="settings" title="settings" border="0" /></a>
			<br />
			<span class='app'><?php echo $t_banner_app_php; ?> <?php echo phpversion() ?></span>
		</div>
		
		<div class="block" style="width:140px;">
			<?php
			$block_style = ($_GET['to'] == "server-page") ? "block_on":"block_off";
			if ($_GET['to'] == "") $block_style = "block_main";
			echo '<span class="' . $block_style . '">' . $t_banner_server . '</span>';
			?>
			<a href='index.php?to=server-page' class='settings'><img src="images_easyphp/edit.png" width="10" height="13" alt="settings" title="settings" border="0" /></a>
			<br />
			<span class='app'><?php echo $t_banner_app_apache; ?> <?php echo $version_apache; ?></span>	
		</div>
		
		<div class="block" style="width:205px;">
			<?php
			$block_style = ($_GET['to'] == "database-page") ? "block_on":"block_off";
			if ($_GET['to'] == "") $block_style = "block_main";
			echo '<span class="' . $block_style . '">' . $t_banner_database . '</span>';
			?>
			<a href='index.php?to=database-page' class='settings'><img src="images_easyphp/edit.png" width="10" height="13" alt="settings" title="settings" border="0" /></a>
			<br />
			<span class='app'><?php echo $t_banner_app_mysql; ?> <?php echo $version_mysql; ?></span>			
		</div>	
		
		<div class="block" style="width:160px;">
			<?php
			$block_style = ($_GET['to'] == "debugging-page") ? "block_on":"block_off";
			if ($_GET['to'] == "") $block_style = "block_main";
			echo '<span class="' . $block_style . '">' . $t_banner_debugging . '</span>';
			?>
			<a href='index.php?to=debugging-page' class='settings'><img src="images_easyphp/edit.png" width="10" height="13" alt="settings" title="settings" border="0" /></a>
			<br />
			<span class='app'><?php echo $t_banner_app_xdebug; ?> <?php echo $version_xdebug; ?></span>			
		</div>		
		
	</div>
	
	<br style="clear:both;" />
	
	<?php
	// ///////////////////////////////////////////////////////////////////////
	// ////  PHP PAGE  ///////////////////////////////////////////////////////
	// ///////////////////////////////////////////////////////////////////////
	if ($_GET['to'] == "php-page") {
		?>
		<div class="settings">
			<ul>
				<li>
					<span class="title"><?php echo $t_php_versions; ?></span><a href='index.php?to=php-page&display=changephpversion' class="settings_link"><?php echo $t_settings_change; ?></a>
				</li>		
				<li>
					<span class="title"><?php echo $t_php_parameters; ?></span>
					<a href='index.php?to=php-page&display=phpinfo' class="settings_link"><?php echo $t_settings_configuration; ?></a>
					<a href='index.php?to=php-page&display=extensions' class="settings_link"><?php echo $t_settings_extensions; ?></a>
				</li>
				<li>
					<span class="title"><?php echo $t_php_conffile; ?></span>		
					<a href='index.php?to=php-page&display=phpconffile' class="settings_link"><?php echo $t_settings_display; ?></a>				
					<a href='index.php?to=php-page&display=phpconfmodify' class="settings_link"><?php echo $t_settings_modify; ?></a>
				</li>
				<li>
					<span class="title"><?php echo $t_php_credits; ?></span>
					<a href='index.php?to=php-page&display=phpcredits' class="settings_link"><?php echo $t_settings_display; ?></a>
				</li>	
			<ul>
		</div>

		<?php
		// DISPLAY : CHANGE PHP VERSION
		if ($_GET['display'] == "changephpversion") {
			?>			
			<div class='menu_display'>
				<h5><?php echo $t_php_versions; ?></h5>
				<div class='changephpversion_add'><?php echo $t_settings_addmoreversions; ?><br /><a href='http://www.easyphp.org/components.php' target='_blank' class='add_link'><?php echo $downloadninstall; ?></a></div>

				<div class='changephpversion_frame'>
				<?php
				$php_dir = @opendir("../php");
				$php_versions = array();
				while ($modules_file = @readdir($php_dir)){
					if (($modules_file != '..') && ($modules_file != '.') && ($modules_file != '') && (@is_dir("../php/".$modules_file)) && @file_exists("../php/".$modules_file."/easyphp.php")){ 
						$php_versions[] = $modules_file;
					}
					sort($php_versions);
				}
				@closedir($php_dir);
				clearstatcache();

				foreach ($php_versions as $phpdir) {
					include("../php/$phpdir/easyphp.php");
					if ($phpversion['status'] == 1) $currentphpdir = $phpdir;
				}
				
				if (count($php_versions) > 1 ){
					foreach ($php_versions as $phpdir) {
						include("../php/$phpdir/easyphp.php");
						if ($phpversion['status'] == 1) $currentphpdir = $phpdir;
					}

					echo '<b>' . $t_settings_availableversions . ' : </b><br />';
					foreach ($php_versions as $phpdir) {
						include("../php/$phpdir/easyphp.php");
						if ($phpversion['status'] == 1) {
							echo '<div class="version"><div class="versiontitle_on">PHP ' . $phpversion['version'] . '</div><div class="versionnotes"><a href="' . $_SERVER['REQUEST_URI'] . '&editnotes=' . $phpdir . '">' . $t_settings_notes . '</a></div><div class="versiondate">' . $phpversion['date'] . '</div><br style="clear:both"></div>';
						} else {
							echo '<div class="version"><div class="versiontitle_off"><a href="change_php_update_step1.php?oldphpdir=' . $currentphpdir . '&newphpdir=' . $phpdir . '">PHP ' . $phpversion['version'] . '</a></div><div class="versionnotes"><a href="' . $_SERVER['REQUEST_URI'] . '&editnotes=' . $phpdir . '">' . $t_settings_notes . '</a></div><div class="versiondate">' . $phpversion['date'] . '</div><br style="clear:both"></div>';	
						}
						if ($_GET['editnotes'] == $phpdir) {
							$action = "http://" . $_SERVER['HTTP_HOST'] . "/home/version_notes_update.php";
							?>
							<div class="editnotes">
								<?php echo $t_settings_notes_descr; ?>
								<form method="post" action="<?php echo $action; ?>">
									<textarea name="version_notes" rows="10"><?php echo $phpversion['notes']; ?></textarea>
									<input type="hidden" name="phpdir" value="<?php echo $phpdir; ?>" />
									<input type="submit" value="<?php echo $t_save; ?>" />
									<a href="index.php?to=php-page&display=changephpversion"><input type="button" value="<?php echo $t_cancel; ?>" /></a>
								</form>
							</div>
							<?php
						}
					}
				}				
				?>
				</div>
			</div>
			<?php
		}			

		// DISPLAY : PHP INFO
		if ($_GET['display'] == "phpinfo") {
			?>
			<div class='menu_display'>
				<h5><?php echo $t_settings_configuration; ?></h5>		
				<div class='phpinfo'><?php echo $phpinfo; ?></div>
			</div>
			<?php
		}
	
		// DISPLAY : PHP EXTENSIONS
		if ($_GET['display']=="extensions") {
			$extensions = @get_loaded_extensions();
			@sort($extensions);
			?>
			<div class='menu_display'>
				<h5><?php echo $t_settings_extensions; ?></h5>
				
				<div class='extensions'>
					<p><?php printf($extensions_nb,count($extensions)); ?></p>
					<?php			
					foreach($extensions as $extension) {
						echo "<a name=$extension></a>";
						echo "<div><img src='/images_easyphp/extension.gif' width='16' height='11' alt='extension' border='0' /><span class='extension_name'>$extension</span>&nbsp;&nbsp;[<a href='index.php?to=php-page&amp;display=extensions&amp;exts=$extension#$extension'>$extensions_functions</a>]</div>";
						if ($_GET['exts']==$extension) {
							$functions = @get_extension_funcs($_GET['exts']);
							if ($functions) {
								echo "<div class='function_name'>" .count($functions). " $extensions_functions :</div>";
								@sort($functions);
								foreach($functions as $function) {
									echo "<div class='function_name'><img src='images_easyphp/function.gif' width='16' height='11' alt='function' border='0' />" . $function . "</div>";
								}
							} else {
								echo "<div class='function_name'>No function found.</div>";
							}
							echo "<br />";	
						}
					} ?>					
				</div>
			</div>
			<?php
		}

		// DISPLAY : DISPLAY PHP CONFIGURATION FILE
		if ($_GET['display'] == "phpconffile") {
			?>			
			<div class='menu_display'>
				<h5><?php echo $t_php_conffile; ?></h5>		
				<pre><?php echo htmlspecialchars(file_get_contents('../conf_files/php.ini', FILE_USE_INCLUDE_PATH)); ?></pre>
			</div>
			<?php
		}
		
		// DISPLAY : MODIFY PHP CONFIGURATION FILE
		if ($_GET['display'] == "phpconfmodify") {
			?>			
			<div class='menu_display'>
				<h5><?php echo $menu_phpconf ?><span class="warningbulle"><a class="info" href="#">!<span><?php echo $ao_warning; ?></span></a></span></h5>
				<div class="phpconf">
					<?php include ("phpinimanager.php"); ?>
				</div>
			</div>
			<?php
		}
		
		// DISPLAY : PHP CREDITS
		if ($_GET['display'] == "phpcredits") {
			?>			
			<div class='menu_display'>
				<h5><?php echo $t_php_credits; ?></h5>		
				<div class='phpinfo'><?php echo $phpcredits; ?></div>
			</div>
			</div></body></html>
			<?php
		}		
		?>
		
	
		</div></body></html>
		<?php
		exit; // close tags
	}
	

	// ///////////////////////////////////////////////////////////////////////
	// ////  DATABASE PAGE  //////////////////////////////////////////////////
	// ///////////////////////////////////////////////////////////////////////
	if ($_GET['to'] == "database-page") {
		?>
		<div class="settings">
			<ul>		
				<li>
					<span class="title"><?php echo $t_mysql_datadir; ?></span>
					<span class="infobulle_settings"><a class="info" href="#">?<span><?php echo $t_mysql_datadir_bulle; ?></span></a></span>
					<span class="path"><?php echo $mysql_datadir; ?></span>
				</li>		
				<li>
					<span class="title"><?php echo $t_mysql_default_param; ?></span>
					<span class="infobulle_settings"><a class="info" href="#">?<span><?php echo $t_mysql_default_param_bulle; ?></span></a></span>
					<span class="configuration_easyphp">
						<span class="config">
							&nbsp;&nbsp;<?php echo $mysql_username; ?> : <span>root</span>
							&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $mysql_password; ?> : <span>&nbsp;&nbsp;</span><a href="#" title="<?php echo $mysql_password_help; ?>"><img src="images_easyphp/info.png" width="14" height="14" alt="help" border="0" /></a>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $mysql_host; ?> : <span><?php echo $_SERVER['SERVER_NAME'] ?></span><a href="#" title="<?php echo $hostname_help; ?>"><img src="images_easyphp/info.png" width="14" height="14" alt="help" border="0" /></a>
						</span>
					</span>
				</li>	
				<li>
					<span class="title"><?php echo $t_mysql_logfile; ?></span>
					<span class="infobulle_settings"><a class="info" href="#">?<span><?php echo $t_mysql_logfile_bulle; ?></span></a></span>
					<a href='index.php?to=database-page&display=mysqlerrorlog' class="settings_link"><?php echo $t_settings_display; ?></a>
					<br /><br />
				</li>
			</ul>
		</div>
			

		<?php
		// DISPLAY : MYSQL ERROR LOG
		if ($_GET['display'] == "mysqlerrorlog") {
			?>			
			<div class='menu_display'>
				<h5><?php echo $t_mysql_logfile; ?></h5>		
				<pre><?php echo file_get_contents('../mysql/data/higgins.err', FILE_USE_INCLUDE_PATH); ?></pre>
			</div>
			<?php
		}
		?>
		
		
		</div></body></html>
		<?php
		exit; // close tags
	}


	// ///////////////////////////////////////////////////////////////////////
	// ////  SERVER PAGE  ////////////////////////////////////////////////////
	// ///////////////////////////////////////////////////////////////////////
	if ($_GET['to'] == "server-page") {
		?>
		<div class="settings">
			<ul>	
				<li>
					<span class="title"><?php echo $t_apache_folder; ?> :</span>
					<span class="path"><?php echo $easyphp_path; ?></span>
				</li>	
				<li>
					<span class="title"><?php echo $t_apache_parameters; ?> :</span>
					<span class="configuration_easyphp">
						<span class="config">
							&nbsp;&nbsp;<?php echo $hostname; ?> :<span><?php echo $_SERVER['SERVER_NAME'] ?></span><a href="#" title="<?php echo $hostname_help; ?>"><img src="images_easyphp/info.png" width="14" height="14" alt="help" border="0" /></a>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $portnum; ?> :<span><?php echo $_SERVER['SERVER_PORT'] ?></span>
						</span>
					</span>
				</li>				
				<li>
					<span class="title"><?php echo $t_apache_conffile; ?></span>
					<span class="infobulle_settings"><a class="info" href="#">?<span><?php echo $t_apache_conffile_bulle; ?></span></a></span>
					<a href='index.php?to=server-page&display=apacheconffile' class="settings_link"><?php echo $t_settings_display; ?></a>				
					<a href='index.php?to=server-page&display=apacheconfmodify' class="settings_link"><?php echo $t_settings_modify; ?></a>
				</li>			
				<li>
					<span class="title"><?php echo $t_apache_logfiles; ?></span>
					<span class="infobulle_settings"><a class="info" href="#">?<span><?php echo $t_apache_logfiles_bulle; ?></span></a></span> :
					<a href='index.php?to=server-page&display=apacheerrorlog' class="settings_link"><?php echo $t_settings_apacheerrorlog; ?></a>
					<a href='index.php?to=server-page&display=apacheaccesslog' class="settings_link"><?php echo $t_settings_apacheaccesslog; ?></a>
				</li>
			</ul>		
		</div>
		
		<?php
		// DISPLAY : MODIFY APACHE CONFIGURATION
		if ($_GET['display'] == "apacheconfmodify") {
			?>	
			<div class='menu_display'>
				<h5><?php echo $menu_apacheconf ?><span class="warningbulle"><a class="info" href="#">!<span><?php echo $ao_warning; ?></span></a></span></h5>
				<div class="apacheconf">
					<?php include ("apacheconfmanager.php"); ?>
				</div>
			</div>
			<?php
		}
	
		// DISPLAY : APACHE CONFIGURATION FILE
		if ($_GET['display'] == "apacheconffile") {
			?>			
			<div class='menu_display'>
				<h5><?php echo $t_apache_conffile; ?></h5>		
				<pre><?php echo htmlspecialchars(file_get_contents('../conf_files/httpd.conf', FILE_USE_INCLUDE_PATH)); ?></pre>
			</div>
			<?php
		}
		
		// DISPLAY : APACHE ERROR LOG
		if ($_GET['display'] == "apacheerrorlog") {
			?>			
			<div class='menu_display'>
				<h5><?php echo $t_settings_apacheerrorlog; ?></h5>		
				<pre><?php echo htmlspecialchars(file_get_contents('../apache/logs/error.log', FILE_USE_INCLUDE_PATH)); ?></pre>
			</div>
			<?php
		}

		// DISPLAY : APACHE ACCESS LOG
		if ($_GET['display'] == "apacheaccesslog") {
			?>			
			<div class='menu_display'>
				<h5><?php echo $t_settings_apacheaccesslog; ?></h5>		
				<pre><?php echo htmlspecialchars(file_get_contents('../apache/logs/access.log', FILE_USE_INCLUDE_PATH)); ?></pre>
			</div>
			<?php
		}		
		?>


		</div></body></html>
		<?php
		exit; // close tags
	}			


	// ///////////////////////////////////////////////////////////////////////
	// ////  DEBUGGING PAGE  /////////////////////////////////////////////////
	// ///////////////////////////////////////////////////////////////////////
	if ($_GET['to'] == "debugging-page") {
		?>
		<div class="settings">
			<div class='intro'><?php echo $debbuging_intro; ?></div>
			<div class='module'><b><?php echo $xdebugmanager; ?></b><br /><?php echo $xdebugmanager_descr; ?><br /><a href='http://www.easyphp.org/modules.php' class='link' target='_blank'><?php echo $downloadninstall; ?></a></div>
			<div class='module'><b><?php echo $webgrind; ?></b><br /><?php echo $webgrind_descr; ?><br /><a href='http://www.easyphp.org/modules.php' class='link' target='_blank'><?php echo $downloadninstall; ?></a></div>
		</div>
			
		
		</div></body></html>
		<?php
		exit; // close tags
	}


	/*
	$nb_db = 0;
	if (is_dir($mysql_datadir)) {
		if ($dh = opendir($mysql_datadir)) {
			while (($file = @readdir($dh)) !== false) {
				if (($file != '..') && ($file != '.') && ($file != '') && ($file != 'mysql') && ($file != 'performance_schema') && ($file != 'phpmyadmin') && (@is_dir($mysql_datadir.$file))) $nb_db++;
			}
			closedir($dh);
		}
	}	
	*/
	?>


	<h3><?php echo $t_yourfiles ?></h3>
	<p class="description"><?php echo $localweb_intro ?></p>
	<?php	
	//= LOCAL WEB ====================================================================================== ?>
	<div class='section'>
	
	<h4><?php echo $localweb; ?></h4>
	
	<div class="infobulle"><a class="info" href="#">?<span><?php echo $t_localweb_bulle; ?></span></a></div>
	
	<div class='localweb'>
		
		<div class='localweb_docs'>
			<span class='localweb_root'><img src='images_easyphp/localweb.gif' width='16' height='11' alt='localweb' /><a href='../' target='_blank'>Root</a></span>
			<span class='localweb_path'><img src='images_easyphp/alias_path.gif' width='16' height='11' alt='alias path' border='0' /><?php echo str_replace("/","\\", $_SERVER["DOCUMENT_ROOT"]); ?></span>
			<br style='clear:both' />
			<?php
			$nbycol = (count($www_files)/4)+1;
			reset($www_files);
			while (key($www_files) !== null){ 
				echo "<div class='localweb_name'>";
				$i = 1;
				while (($i < $nbycol) AND (key($www_files) !== null)) {
					echo "<img src='images_easyphp/localweb_doc.gif' width='30' height='11' alt='localweb' /><a href='../" . current($www_files) . "' target='_blank' title='" . current($www_files) . "'>" . cut(current($www_files),25) . "</a><br />";
					next($www_files);
					$i++;
				}
				echo "</div>";
			}
			if (count($www_files) != 0) echo "<br style='clear:both' />";
			?>
		</div>			
	</div>
	<br style='clear:both;' />
	</div>
	<?php
	//==================================================================================================			

	//== ALIAS ========================================================================================= ?>
	<div class='section'>
	<h4><?php echo $alias_title;?></h4>
	<div class="infobulle"><a class="info" href="#">?<span><?php echo $alias_intro ?></span></a><?php if ($nb_alias != 0) echo "<a href='index.php?to=add_alias_1' class='add_link_plus' title='" . $menu_alias_add . "'>+</a>";?></div>
	<div class="alias_section">
	<?php
	read_alias();
	if ($nb_alias == 0) {
		echo "<div class='alias_none'>" . $alias_none . "<a href='index.php?to=add_alias_1' class='none_add_link'>". $menu_alias_add ."</a></div>";
	} else {
		list_alias();
	}?>
	<br style='clear:both;' />
	</div>
	</div>
	<?php
	//==================================================================================================
		

	//== VIRTUAL HOSTS ================================================================================== ?>
	<div class='section'>
	<h4><?php echo $vhosts_title;?></h4>
	
	<?php
	//== DISPLAY VIRTUALHOSTS ================================================
	$vhosts_array = read_vhosts('easyphp_vhosts');
	?>

	<div class="infobulle"><a class="info" href="#">?<span><?php echo $vhosts_add_vhost_chapo; ?></span></a><?php if (count($vhosts_array) != 0) echo "<a href='index.php?to=add_vhost_1' class='add_link_plus' title='" . $menu_alias_add . "'>+</a>";?></div>

	<div class="vhosts_section">
	<?php
	if (count($vhosts_array) != 0) {
		foreach ($vhosts_array as $key => $vhost) {

			$vhost_name = trim($vhost[2]);
			$port = ($_SERVER['SERVER_PORT'] == '80') ? '' : ':' . $_SERVER['SERVER_PORT'];

			if (in_array($vhost_name, read_hostsfile('hosts'))) {
			
				$vhost_link = str_replace("/","\\", trim($vhost[1]));			
				$hash = (check_hash($vhost_name) == 'on') ? 'on' : 'off';
				$switch_hash = (check_hash($vhost_name) == 'on') ? 'off' : 'on';
				$onoff = (check_hash($vhost_name) == 'on') ? $vhosts_activate : $vhosts_desactivate;			
			
				echo '<div class="row">';
				if ($hash == 'on') echo '<div class="vhost_name_hashon"><img src="images_easyphp/vhost_hashon.png" width="16" height="11" alt="virtualhost" />' . $vhost_name;
				if ($hash == 'off') echo '<div class="vhost_name_hashoff"><img src="images_easyphp/vhost_hashoff.png" width="16" height="11" alt="virtualhost" /><a href="http://' . $vhost_name . $port . '" target="_blank">' . $vhost_name . '</a>';
				if (stripos($vhost_name,'.') !== false) echo ' <span class="warning_url"><a href="' . $_SERVER['PHP_SELF'] . '?to=warning_url#anchor_' . $file . '">!</a></span>';
				echo '</div>';
				
				echo '<div class="vhost_path_hash' . $hash . '"><img src="images_easyphp/path_hash' . $hash . '.png" width="16" height="11" alt="virtualhost path" border="0" />' . $vhost_link . '\</div>';
				if ((isset($_GET['num_virtualhost'])) and ($_GET['to'] == "del_confirm") and ($_GET['num_virtualhost'] == $key)) {
					echo '<div class="vhost_del_confirm_frame">';
					echo '<a href="vhosts_update.php?to=del_virtualhost&amp;num_virtualhost=' . $key . '#anchor_' . $file . '" class="vhost_del_confirm">' . $confirm . '</a><a href="index.php#anchor_' . $file . '" class="vhost_del_cancel">' . $cancel . '</a>';
					echo '</div>';
				} else {
					echo '<div class="vhost_del">';
					echo '<a href="index.php?to=del_confirm&amp;num_virtualhost=' . $key . '#anchor_' . $file . '" title="' . $vhosts_delete . '"><img src="images_easyphp/delete.png" width="9" height="9" alt="' . $vhosts_delete . '" border="0" /></a>';
					echo '</div>';
					
					echo '<div class="vhost_onoff">';
					echo '<a href="vhosts_update.php?to=onoff_host&amp;hash=' . $switch_hash . '&amp;servername=' . $vhost_name . '#anchor_' . $file . '">' . $onoff . '</a>';
					echo '</div>';
				}
				echo '<br style="clear:both;" />';
				echo "</div>";
			}
		}
	} else {
		echo "<div class='alias_none'>" . $vhosts_no_vhost_created . "<a href='index.php?to=add_vhost_1' class='none_add_link'>". $vhosts_add_vhost ."</a></div>";
	}?>
	<br style='clear:both;' />
	</div>
	</div>
	<?php
	//========================================================================	
	

	//==================================================================================================
		
	//= MODULES ======================================================================================== ?>	
	
	<h3><?php echo $t_yourmodules ?></h3>
	<p class="description"><?php echo $module_add; ?><a href='http://www.easyphp.org/modules.php' class='link' target='_blank'><?php echo $downloadninstall; ?></a></p>

	<?php
	if (count($modules_files) == 0) {
		echo "<div class='modules_none'>" . $module_none . "<a href='http://www.easyphp.org' target='_blank' class='add_link'>". $menu_module_add ."</a></div>";
	} else {
		foreach ($modules_files as $file) {
			include("../modules/$file/easyphp+.php");
		}
	}
	?>
	<br style='clear:both;' />

	<?php
	//==================================================================================================
	?>
	
</div>

<div id="bottom">
	<div class="portable">
		<h6><?php echo $portable_title ?></h6>
		<p><?php echo $portable_info ?></p>
	</div>			
	<div class="social">
		<h6>Social</h6>
		<p class='facebook'><a href="http://www.facebook.com/easywamp" target='_blank' alt='facebook' title='facebook'>facebook</a></p>
		<p class='twitter'><a href="http://www.twitter.com/easyphp" target='blank' alt='twitter' title='twitter'>twitter</a></p>
		<p class='googleplus'><a href="https://plus.google.com/109064253798905195298" target='blank' alt='google+' title='google+'>google+</a></p>
	</div>	
	<div class="donation">
		<h6><a href="index.php?to=donate"><?php echo $t_donation; ?></a></h6>
		<p>
		<a href='index.php?to=donate'><?php echo $donate_title; ?></a>
		</p>
	</div>


	<div class="footer_bottom">EasyPHP 2000 - 2012</div>
</div>	


</body>
</html>