<?php
/**
 * version_notes_update.php
 * PHP version 5
 * @author   Laurent Abbal <laurent@abbal.com>
 * @license  http://www.gnu.org/licenses/gpl-2.0.html  GPL License 2 
 * @version  5.3.x
 * @link     http://www.easyphp.org
 */
 
include('../php/' . $_POST['phpdir'] . '/easyphp.php');

$newnotes = '<?php
$phpversion = array();
$phpversion = array(
	"status"	=> "' . $phpversion['status'] . '",
	"dirname"	=> "' . $phpversion['dirname'] . '",
	"name" 		=> "' . $phpversion['name'] . '",
	"version" 	=> "' . $phpversion['version'] . '",
	"date" 		=> "' . $phpversion['date'] . '",
	"notes"		=> "' . htmlspecialchars($_POST['version_notes']) . '",
);
?>';
file_put_contents('../php/' . $_POST['phpdir'] . '/easyphp.php', $newnotes);

$redirect = "http://" . $_SERVER['HTTP_HOST'] . "/home/index.php?to=php-page&display=changephpversion";
header("Location: " . $redirect); 
exit;
?>