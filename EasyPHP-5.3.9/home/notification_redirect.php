<?php
/**
 * notification_redirect.php
 * PHP version 5
 * @author   Laurent Abbal <laurent@abbal.com>
 * @license  http://www.gnu.org/licenses/gpl-2.0.html  GPL License 2 
 * @version  5.3.x
 * @link     http://www.easyphp.org
 */ 
 
include("notification.php"); 
$new_notification = fopen('notification.php', "w");
$new_content = '<?php $notification = array(\'check_date\'=>\'' . date('Ymd') . '\',\'date\'=>\'' . $notification['date'] . '\',\'status\'=>\'0\',\'link\'=>\'' . $notification['link'] . '\',\'message\'=>\'' . $notification['message'] . '\'); ?>';
fputs($new_notification,$new_content);
fclose($new_notification);	
$redirect = $notification['link'];
header("Location: " . $redirect); 
exit;	
?>