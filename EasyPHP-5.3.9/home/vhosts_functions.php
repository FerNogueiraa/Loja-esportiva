<?php
/**
 * Virtual Hosts Manager for EasyPHP
 * virtualhostsmanager_functions.php
 * PHP version 5
 * @author   Laurent Abbal <laurent@abbal.com>
 * @license  http://www.gnu.org/licenses/gpl-2.0.html  GPL License 2 
 * @version  5.3.x
 * @link     http://www.easyphp.org
 */


function adapt_httpdconf() {
	$httpdconf = @file_get_contents(dirname($_SERVER['DOCUMENT_ROOT']) . '/conf_files/httpd.conf');
	if(stripos($httpdconf,'### VirtualHost EasyPHP') === false) {
		$new_httpdconf = $httpdconf . '

# == !!! DO NOT REMOVE !!! ===================================================
### VirtualHost EasyPHP
NameVirtualHost 127.0.0.1
<VirtualHost 127.0.0.1>
	DocumentRoot "' . $_SERVER['DOCUMENT_ROOT'] . '"
	ServerName localhost
</VirtualHost>
# ============================================================================
#virtualhost
#virtualhost
# ============================================================================
### VirtualHost End
# ============================================================================';
		
		// Backup old httpd.conf
		rename(dirname($_SERVER['DOCUMENT_ROOT']) . '/conf_files/httpd.conf', dirname($_SERVER['DOCUMENT_ROOT']) . '/conf_files/httpd_' . date("Y-m-d@U") . '.conf');

		// Save new httpd.conf
		$new_httpdfile = fopen(dirname($_SERVER['DOCUMENT_ROOT']) . '/conf_files/httpd.conf', 'w');
		fputs($new_httpdfile,trim($new_httpdconf));
		fclose($new_httpdfile);	
	}
}

function read_vhosts($part) {

	$httpdconf = @file_get_contents(dirname($_SERVER['DOCUMENT_ROOT']) . '/conf_files/httpd.conf');
	$httpdconf_array = explode("#virtualhost",$httpdconf);

	if ($part == 'easyphp_vhosts') {
		$matches = array();
		$easyphp_vhosts_array = array();
		@preg_match_all("'<VirtualHost\s127.0.0.1>(.*?)<\/VirtualHost>'si", $httpdconf_array[1], $matches);
		foreach ($matches[0] as $key => $vhost) {
			@preg_match("'DocumentRoot \"(.*?)\"'si", $vhost,$documentroot);
			@preg_match("'ServerName (.*?)\n'si", $vhost,$servername);
			$easyphp_vhosts_array[$key][0] = trim($vhost); 
			$easyphp_vhosts_array[$key][1] = trim($documentroot[1]); 
			$easyphp_vhosts_array[$key][2] = trim($servername[1]); 
		}
		return $easyphp_vhosts_array;
	}
	
	if ($part == 'all_vhosts') {
		$matches = array();
		$all_vhosts_array = array();
		@preg_match_all("'<VirtualHost\s127.0.0.1>(.*?)<\/VirtualHost>'si", $httpdconf, $matches);
		foreach ($matches[0] as $key => $vhost) {
			@preg_match("'DocumentRoot \"(.*?)\"'si", $vhost,$documentroot);
			@preg_match("'ServerName (.*?)\n'si", $vhost,$servername);
			$all_vhosts_array[$key][0] = trim($vhost); 
			$all_vhosts_array[$key][1] = trim($documentroot[1]); 
			$all_vhosts_array[$key][2] = trim($servername[1]); 
		}
		return $all_vhosts_array;
	}
	
	if ($part == 'servernames') {
		$matches = array();
		$servernames_array = array();
		@preg_match_all("'<VirtualHost\s127.0.0.1>(.*?)<\/VirtualHost>'si", $httpdconf, $matches);
		foreach ($matches[0] as $key => $vhost) {
			@preg_match("'ServerName (.*?)\n'si", $vhost,$servername);
			$servernames_array[] = trim($servername[1]); 
		}
		return $servernames_array;
	}
	
	if ($part == 'file') return $httpdconf_array;
}

function get_hostsfile_dir() {
	$hostlist = array(
		// 95, 98/98SE, Me 	%WinDir%\
		// NT, 2000, and 32-bit versions of XP, 2003, Vista, 7 	%SystemRoot%\system32\drivers\etc\
		// 64-bit versions 	%SystemRoot%\system32\drivers\etc\
		'' => '#Windows 95|Win95|Windows_95#i',
		'' => '#Windows 98|Win98#i',
		'' => '#Windows ME#i',	
		'\system32\drivers\etc' => '#Windows NT 4.0|WinNT4.0|WinNT|Windows NT#i',			
		'\system32\drivers\etc' => '#Windows NT 5.0|Windows 2000#i',
		'\system32\drivers\etc' => '#Windows NT 5.1|Windows XP#i',
		'\system32\drivers\etc' => '#Windows NT 5.2#i',
		'\system32\drivers\etc' => '#Windows NT 6.0#i',
		'\system32\drivers\etc' => '#Windows NT 7.0#i',
	);
	foreach($hostlist as $hostdir=>$regex) {
		if (@preg_match($regex, $_SERVER['HTTP_USER_AGENT'])) break;
	}
	// Return FALSE is hosts cannot be opened
	$hosts_path = $_SERVER['WINDIR'].$hostdir;
	return $hosts_path;
}

function read_hostsfile($part) {
	$hostsfile_array = array();
	$hosts_array = array();
	$hostsfile = @file_get_contents(get_hostsfile_dir().'\hosts','r');
	$hostsfile_array = explode("\n",$hostsfile);
	foreach ($hostsfile_array as $line) {
		if((stripos($line,'127.0.0.1') !== false) and ((stripos($line,'127.0.0.1')) < 3)) {
			$line_array = explode('127.0.0.1', $line);
			$hosts_array[] = trim($line_array[1]);
		}
	}
	if ($part == 'file') return $hostsfile_array;
	if ($part == 'hosts') return $hosts_array;
}

function check_hash($server_name) {
	$hostsfile_array = read_hostsfile('file');
	foreach ($hostsfile_array as $line) {
		$pos_hash = stripos($line,'#');
		$pos_127001 = stripos($line,'127.0.0.1');
		$pos_servername = stripos($line,$server_name);
		if(($pos_127001 !== false) and ($pos_servername !== false)) {
			if (($pos_hash !== false) and ($pos_hash < $pos_127001)) {
				$hash = "on";
			} else {
				$hash = "off";
			}
		} 
	}
	return $hash;
}
?>