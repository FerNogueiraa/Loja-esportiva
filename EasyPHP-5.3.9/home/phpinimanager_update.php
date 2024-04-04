<?php
/**
 * Phpini Manager Update for EasyPHP
 * phpinimanager_update.php
 * PHP version 5
 * @author   Laurent Abbal <laurent@abbal.com>
 * @license  http://www.gnu.org/licenses/gpl-2.0.html  GPL License 2 
 * @version  5.3.x
 * @link     http://www.easyphp.org
 */ 

if ($_POST){
	$source = "../conf_files/php.ini";
	$phpini = file_get_contents($source);

	// Backup old php.ini
	$backup_phpini = "../conf_files/php_" . date("Y-m-d@U") . ".ini";
	$fp_backup = fopen($backup_phpini, 'w');
	fputs($fp_backup, $phpini);
	fclose($fp_backup);	

	foreach ($_POST as $parameter => $parameter_value){
		// Search and replace
		$pattern = "/" . $parameter . "[ \t]+=[ \t]+(.*)/";
		$replacement = $parameter . " = " . $parameter_value;
		$phpini = preg_replace($pattern, $replacement, $phpini);
	}

	// Save new php.ini
	$fp_update = fopen('../conf_files/php.ini', 'w');
	fputs($fp_update, $phpini);
	fclose($fp_update);	

	$redirect = "http://" . $_SERVER['HTTP_HOST'] . "/home/index.php?to=php-page&display=phpconfmodify";
	sleep(2);
	header("Location: " . $redirect); 
	exit;
}
?>