<?php
/**
 * Virtual Hosts Manager for EasyPHP
 * virtualhostsmanager.php
 * PHP version 5
 * @author   Laurent Abbal <laurent@abbal.com>
 * @license  http://www.gnu.org/licenses/gpl-2.0.html  GPL License 2 
 * @version  5.3.x
 * @link     http://www.easyphp.org
 */

include_once('vhosts_functions.php');
?>

<style type="text/css" media="all">
	@import url("../modules/<?php echo $file; ?>/virtualhostsmanager_styles.css");
</style>

<div class='add_vhost_button'><a href='<?php echo $_SERVER['PHP_SELF']; ?>?to=add_vhost_1#anchor_<?php echo $file; ?>'><?php echo $vhosts_add_vhost; ?></a></div>
<div class='module_parameters'><a href='index.php?to=<?php echo $file; ?>#anchor_<?php echo $file; ?>' name='anchor_<?php echo $file; ?>'><img src='images_easyphp/plus.gif' width='12' height='9' alt='+' border='0' /><?php echo $vhosts_info; ?></a></div>

<?php
clearstatcache();

if (is_writable(get_hostsfile_dir() . '\hosts')) {

	adapt_httpdconf();

	//== DISPLAY SERVERNAME WARNING ==========================================
	if ($_GET['to'] == "warning_url") {	
		?>
		<br style="clear:both;" />
		<div class="warning_url_frame"><?php echo $vhosts_warning_url; ?></div>
		<div class='close'><a href='index.php'><?php echo $vhosts_close; ?></a></div>
		<?php
	}
	//========================================================================


	//== DISPLAY VIRTUALHOSTS ================================================
	$vhosts_array = read_vhosts('easyphp_vhosts');
	
	if (count($vhosts_array) != 0) {
		echo '<br style="clear:both;" />';
		echo '<div class="vhosts">';
		foreach ($vhosts_array as $key => $vhost) {

			$vhost_name = trim($vhost[2]);
			$port = ($_SERVER['SERVER_PORT'] == '80') ? '' : ':' . $_SERVER['SERVER_PORT'];

			if (in_array($vhost_name, read_hostsfile('hosts'))) {
			
				$vhost_link = str_replace("/","\\", trim($vhost[1]));			
				$hash = (check_hash($vhost_name) == 'on') ? 'on' : 'off';
				$switch_hash = (check_hash($vhost_name) == 'on') ? 'off' : 'on';
				$onoff = (check_hash($vhost_name) == 'on') ? $vhosts_activate : $vhosts_desactivate;			
			
				echo '<div class="vhost">';
				if ($hash == 'on') echo '<div class="vhost_name_hashon"><img src="../modules/' . $file . '/images/vhost_hashon.png" width="16" height="11" alt="virtualhost" />' . $vhost_name;
				if ($hash == 'off') echo '<div class="vhost_name_hashoff"><img src="../modules/' . $file . '/images/vhost_hashoff.png" width="16" height="11" alt="virtualhost" /><a href="http://' . $vhost_name . $port . '" target="_blank">' . $vhost_name . '</a>';
				if (stripos($vhost_name,'.') !== false) echo ' <span class="warning_url"><a href="' . $_SERVER['PHP_SELF'] . '?to=warning_url#anchor_' . $file . '">!</a></span>';
				echo '</div>';
				
				echo '<div class="vhost_path_hash' . $hash . '"><img src="../modules/' . $file . '/images/path_hash' . $hash . '.png" width="16" height="11" alt="virtualhost path" border="0" />' . $vhost_link . '\</div>';
				if ((isset($_GET['num_virtualhost'])) and ($_GET['to'] == "del_confirm") and ($_GET['num_virtualhost'] == $key)) {
					echo '<div class="vhost_del_confirm_frame">';
					echo '<div class="vhost_del_confirm"><a href="http://' . $_SERVER['HTTP_HOST'] . '/modules/' . $file . '/virtualhostsmanager_update.php?to=del_virtualhost&amp;num_virtualhost=' . $key . '#anchor_' . $file . '">' . $confirm . '</a></div>';
					echo '<div class="vhost_del_cancel"><a href="' . $_SERVER['PHP_SELF'] . '#anchor_' . $file . '">' . $cancel . '</a></div>';
					echo '</div>';
				} else {
					echo '<div class="vhost_del">';
					echo '<a href="' . $_SERVER['PHP_SELF'] . '?to=del_confirm&amp;num_virtualhost=' . $key . '#anchor_' . $file . '"><img src="../modules/' . $file . '/images/delete.png" width="9" height="9" alt="delete virtualhost" />' . $vhosts_delete . '</a>';
					echo '</div>';
					
					
					echo '<div class="vhost_onoff">';
					echo '<a href="http://' . $_SERVER['HTTP_HOST'] . '/modules/' . $file . '/virtualhostsmanager_update.php?to=onoff_host&amp;hash=' . $switch_hash . '&amp;servername=' . $vhost_name . '#anchor_' . $file . '">' . $onoff . '</a>';
					echo '</div>';

				}
				echo "</div>";
			}
		}
		echo '<br style="clear:both"; />';
		echo "</div>";
	} else {
		echo '<br style="clear:both"; />';
	}
	//========================================================================
	
} else {
	echo '<br style="clear:both"; />';
	if ($_GET['to'] == "add_vhost_1") {
		echo '<div class="norights">' . $vhosts_norights . ' ' . get_hostsfile_dir() . '\hosts</div>';
		echo '<div class="close"><a href="index.php">' . $vhosts_close . '</a></div>';
	}
}	
?>