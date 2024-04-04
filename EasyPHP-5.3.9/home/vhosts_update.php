<?php
/**
 * Virtual Hosts Manager for EasyPHP
 * virtualhostsmanager_update.php
 * PHP version 5
 * @author   Laurent Abbal <laurent@abbal.com>
 * @license  http://www.gnu.org/licenses/gpl-2.0.html  GPL License 2 
 * @version  5.3.x
 * @link     http://www.easyphp.org
 */
?>

<style type="text/css" media="all">
	.add_vhost_warning {font-family:arial;font-size:12px;color:black;margin:10px 0px 0px 0px;padding:0px 0px 0px 0px;}
	.add_vhost_warning a {text-decoration:none;font-size:11px;color:#E4E4E4;background-color:#808080;margin:0px 10px 0px 0px;padding:0px 5px 0px 5px;-moz-border-radius:2px;-khtml-border-radius:2px;-webkit-border-radius:2px;border-radius:2px;}
	.add_vhost_warning a:hover {color:white;background-color:black;}
</style>

<?php
include("i18n.inc.php");
include_once('vhosts_functions.php');


//== ACTIVATE OR DESACTIVATE HOST NAME =======================================
if ((isset($_GET['to'])) and ($_GET['to'] == "onoff_host")) {
	$hostsfile_array = read_hostsfile('file');
	
	$hash = ($_GET['hash'] == 'on') ? '#':'';
	$new_hostfile_content = '';
	foreach ($hostsfile_array as $line) {
	
		if((stripos($line,"127.0.0.1") !== false) and (stripos($line,$_GET['servername']) !== false)) {
			$new_hostfile_content .= $hash . "127.0.0.1  " . $_GET['servername'] . "\n";
		} else {
			$new_hostfile_content .= $line . "\n";
		}
	}	

	// Backup old hosts file
	rename(get_hostsfile_dir() . "\hosts", get_hostsfile_dir() . "\hosts_" . date("Y-m-d@U"));
	
	// Save new hosts file
	$new_hostsfile = fopen(get_hostsfile_dir() . "\hosts", "w");
	fputs($new_hostsfile,trim($new_hostfile_content));
	fclose($new_hostsfile);	
	$redirect = "http://" . $_SERVER['HTTP_HOST'] . "/home/index.php";
	header("Location: " . $redirect); 
	exit;	
}
//============================================================================


//== DELETE VIRTUAL HOST AND HOST NAME =======================================
if ((isset($_GET['to'])) and ($_GET['to'] == "del_virtualhost")) {
	
	$vhosts_array = read_vhosts('easyphp_vhosts');
	$httpdconf_array = read_vhosts('file');	
	$hostsfile_array = read_hostsfile('file');	
	
	// Delete host in hosts file
	$new_hostfile_content = '';
	// array_unique — Removes duplicate values from an array
	foreach ($hostsfile_array as $line) {
		if((stripos($line,"127.0.0.1") === false) or (stripos($line,$vhosts_array[$_GET['num_virtualhost']][2]) === false)) {
			$new_hostfile_content .= trim($line) . "\n";
		}
	}
		
	// Backup old hosts file
	rename(get_hostsfile_dir() . "\hosts", get_hostsfile_dir() . "\hosts_" . date("Y-m-d@U"));
	
	// Save new hosts file
	$new_hostsfile = fopen(get_hostsfile_dir() . "\hosts", "w");
	fputs($new_hostsfile,trim($new_hostfile_content));
	fclose($new_hostsfile);	

	// 	Delete vhost in httpd.conf file
	unset($vhosts_array[$_GET['num_virtualhost']]);
	$new_vhosts = '';
	foreach ($vhosts_array as $vhost_data) {
		$new_vhosts .=  $vhost_data[0] . "\n";	
	}

	$new_apacheconf_content = $httpdconf_array[0] . '#virtualhost' . "\n" . $new_vhosts . '#virtualhost' . $httpdconf_array[2];
	
	// Backup old httpd.conf
	rename(dirname($_SERVER['DOCUMENT_ROOT']) . '/conf_files/httpd.conf', dirname($_SERVER['DOCUMENT_ROOT']) . '/conf_files/httpd_' . date("Y-m-d@U") . '.conf');

	// Save new httpd.conf
	$new_httpdfile = fopen(dirname($_SERVER['DOCUMENT_ROOT']) . '/conf_files/httpd.conf', 'w');
	fputs($new_httpdfile,trim($new_apacheconf_content));
	fclose($new_httpdfile);
	$redirect = "http://" . $_SERVER['HTTP_HOST'] . "/home/index.php";
	header("Location: " . $redirect); 
	exit;		
}
//============================================================================


//== ADD VITRTUAL HOST AND HOST NAME =========================================
if ((isset($_POST['to'])) and ($_POST['to'] == "add_vhost_2")) {

	$vhosts_array = read_vhosts('easyphp_vhosts');
	$httpdconf_array = read_vhosts('file');
	
	/*  virtualhost name tests  */
	
	$name_test = true;
	$lang = $_POST['lang'];
	
	if ($_POST['vhost_name'] == "") {
		echo "<div class='add_vhost_warning'><a href=\"javascript:history.back()\">&laquo; " . $t_back . "</a>" . $t_add_vhost_warning_1 . "</div>";
		$name_test = false;
		exit;
	}
	elseif ($_POST['vhost_link'] == "") {
		echo "<div class='add_vhost_warning'><a href=\"javascript:history.back()\">&laquo; " . $t_back . "</a>" . $t_add_vhost_warning_2 . "</div>";
		$name_test = false;
		exit;
	}
	elseif (($_POST['vhost_link'] != "") && (!is_dir($_POST['vhost_link']))) {
		echo "<div class='add_vhost_warning'><a href=\"javascript:history.back()\">&laquo; " . $t_back . "</a>" . $t_add_vhost_warning_3 . "</div>";
		$name_test = false;
		exit;
	}
	elseif (!preg_match('/^[-a-zA-Z0-9_.]+$/i', trim($_POST['vhost_name']))) {
		echo "<div class='add_vhost_warning'><a href=\"javascript:history.back()\">&laquo; " . $t_back . "</a>" . $t_add_vhost_warning_4 . "</div>";
		$name_test = false;
		exit;
	}
	elseif (in_array(trim($_POST['vhost_name']), read_vhosts('servernames'))) {
		echo "<div class='add_vhost_warning'><a href=\"javascript:history.back()\">&laquo; " . $t_back . "</a>" . $t_add_vhost_warning_5 . "</div>";
		$name_test = false;
		exit;
	} 	
	

	if (($_POST['vhost_name'] != "") && ($_POST['vhost_link'] != "") && (is_dir($_POST['vhost_link'])) && ($name_test == true)) {
		
		// Write httpd.con
		$vhost_link = str_replace("\\","/", $_POST['vhost_link']);
		$vhost_link = str_replace("//","/", $vhost_link);
				
		if (substr($vhost_link, -1) == "/"){$vhost_link = substr($vhost_link,0,strlen($vhost_link)-1);}
		$new_vhost = "<VirtualHost 127.0.0.1>\n";
		$new_vhost .= "\tDocumentRoot \"" . $vhost_link . "\"\n";
		$new_vhost .= "\tServerName " . $_POST['vhost_name'] . "\n";
		$new_vhost .= "\t<Directory \"" . $vhost_link . "\">\n";
		$new_vhost .= "\t\tOptions FollowSymLinks Indexes\r\n";
		$new_vhost .= "\t\tAllowOverride All\r\n";
		$new_vhost .= "\t\tOrder deny,allow\r\n";
		$new_vhost .= "\t\tAllow from 127.0.0.1\r\n";
		$new_vhost .= "\t\tdeny from all\r\n";
		$new_vhost .= "\t</Directory>\r\n";
		$new_vhost .= "</VirtualHost>\n";
		
		$new_apacheconf_content = $httpdconf_array[0] . '#virtualhost' . "\n" . $httpdconf_array[1] . $new_vhost . '#virtualhost' . $httpdconf_array[2];
		
		// Backup old httpd.conf
		rename(dirname($_SERVER['DOCUMENT_ROOT']) . '/conf_files/httpd.conf', dirname($_SERVER['DOCUMENT_ROOT']) . '/conf_files/httpd_' . date("Y-m-d@U") . '.conf');

		// Save new httpd.conf
		$new_httpdfile = fopen(dirname($_SERVER['DOCUMENT_ROOT']) . '/conf_files/httpd.conf', 'w');
		fputs($new_httpdfile,trim($new_apacheconf_content));
		fclose($new_httpdfile);
	
		// Write hosts file
		$hostsfile_array = read_hostsfile('file');
		$new_hostfile_content = '';
		$vhost_exists = false;
		// array_unique — Removes duplicate values from an array
		foreach ($hostsfile_array as $line) {
			if (stristr($line,$_POST['vhost_name']) AND (stristr($line,"127.0.0.1"))) {
				$new_hostfile_content = $new_hostfile_content . "\n" . "127.0.0.1  " . $_POST['vhost_name'];
				$vhost_exists = true;
			} else {
				$new_hostfile_content = $new_hostfile_content . "\n" . $line;
			}
		}
		if (!$vhost_exists) {
			$new_hostfile_content = $new_hostfile_content . "\n" . "127.0.0.1  " . $_POST['vhost_name'];
		}
		
		// Backup old hosts file
		rename(get_hostsfile_dir() . "\hosts", get_hostsfile_dir() . "\hosts_" . date("Y-m-d@U"));
		
		// Save new hosts file
		$new_hostsfile = fopen(get_hostsfile_dir() . '\hosts', 'w');
		fputs($new_hostsfile,trim($new_hostfile_content));
		fclose($new_hostsfile);
		sleep(2);
		$redirect = 'http://' . $_SERVER['HTTP_HOST'] . '/home/index.php';
		header("Location: " . $redirect); 
	}			
}
//============================================================================
?>