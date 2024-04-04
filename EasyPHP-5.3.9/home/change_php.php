<?php
/**
 * change_php.php
 * PHP version 5
 * @author   Laurent Abbal <laurent@abbal.com>
 * @license  http://www.gnu.org/licenses/gpl-2.0.html  GPL License 2 
 * @version  5.3.x
 * @link     http://www.easyphp.org
 */ 
 
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


foreach ($php_versions as $file) {
	include("../php/$file/easyphp.php");

	echo '<a href="change_php_update_httpdconf.php?version=' . $file . '">' . $file . '</a><br />';
}
?>