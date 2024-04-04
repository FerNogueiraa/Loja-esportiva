<?php
/**
 * change_php_update_step2.php
 * PHP version 5
 * @author   Laurent Abbal <laurent@abbal.com>
 * @license  http://www.gnu.org/licenses/gpl-2.0.html  GPL License 2 
 * @version  5.3.x
 * @link     http://www.easyphp.org
 */ 
 
// Export old php.ini
copy('../conf_files/php.ini', '../php/' . $_GET['oldphpdir'] . '/php.ini');	

// Import new php.ini
copy('../php/' . $_GET['newphpdir'] . '/php.ini', '../conf_files/php.ini');	

$redirect = "http://" . $_SERVER['HTTP_HOST'] . "/home/index.php?to=php-page&display=changephpversion";
sleep(1);
header("Location: " . $redirect); 
exit;
?>