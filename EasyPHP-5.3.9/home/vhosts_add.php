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

	//== MENU ITEM : ADD VIRTUAL HOST ========================================
	if ($_GET['to'] == "add_vhost_1") {
		?>
			<div class="add_vhost">
				<h5><?php echo $vhosts_add_vhost; ?></h5>
				<form method="post" action="vhosts_update.php">
					<div><span>1.</span> <?php echo $vhosts_add_vhost_1; ?></div>
					<br />
					<div><span>2.</span> <?php echo $vhosts_add_vhost_2; ?></div>
					<input type="text" name="vhost_name"  class="input" style="width:300px" />
					<div class='warning_servername'><span style="background:#8F381A;">!</span> <?php echo $vhosts_warning_servername_2; ?></div>
					<div class='warning_servername'><span>!</span> <?php echo $vhosts_warning_servername_1; ?></div>
					<br />
					<div><span>3.</span> <?php echo $vhosts_add_vhost_3; ?></div>
					<input type="text" name="vhost_link" class="input" style="width:428px" />
					<input type="hidden" name="to" value="add_vhost_2" />
					<input type="hidden" name="lang" value="<?php echo $lang; ?>" />
					<br />
					<div class="warning_conf"><?php echo $vhosts_warning_conf . "'" . get_hostsfile_dir() . "\hosts'"; ?></div>
					<input type="submit" value="<?php echo $vhosts_save; ?>" class="submit" />
						
				</form>
			</div>
		</div><br /></body></html>
		<?php
		exit; // close tags				
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