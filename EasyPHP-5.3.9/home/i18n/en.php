<?php
/**
 * EasyPHP i18n - Translations for EasyPHP
 * EN
 * PHP version 5
 * @author   Laurent Abbal <laurent@abbal.com>
 * @license  http://www.gnu.org/licenses/gpl-2.0.html  GPL License 2 
 * @version  5.3.x
 * @link     http://www.easyphp.org
 */

//== Navigation ==
$administration = "Administration";
$help = "help";
$t_back = "back";
$back = "back";
$close = "close";
$t_save = "save";
$t_cancel = "cancel";
$cancel = "cancel";
$confirm = "confirm";
$download = "download";
$parameters = "Parameters";
$wait = "... and wait 5 sec. (servers have to reboot)";
$backhomepage = "back to homepage";

$debbuging_intro = "Xdebug is a PHP extension for powerful debugging. It supports stack and function traces, profiling information and memory allocation and script execution analysis.";
$xdebugmanager = "Xdebug Manager";
$xdebugmanager_descr = "Xdebug Manager allows you to start, stop and control Xdebug.";
$webgrind = "Webgring";
$webgrind_descr = "Webgrind is an Xdebug web frontend. It implements a subset of the features of kcachegrind.";
$downloadninstall = "download and install";

$t_banner_php = "PHP";
$t_banner_database = "DATABASE";
$t_banner_server = "SERVER";
$t_banner_debugging = "DEBUGGING";
$t_banner_app_php = "PHP";
$t_banner_app_mysql = "MySQL";
$t_banner_app_apache = "Apache";
$t_banner_app_xdebug = "XDebug";

$t_php_versions = "Version";
$t_php_parameters = "Parameters";
$t_php_conffile = "Configuration File";
$t_php_credits = "Credits";


$t_mysql_datadir = "Data Folder";
$t_mysql_datadir_bulle = "By default, all information managed by MySQL is stored in this folder. All databases are stored here, as well as the status and log files that provide information about the server's operation.";
$t_mysql_default_param = "Default Parameters";
$t_mysql_default_param_bulle = "When you install an application you usually need these settings: User name, Password, Database Host.";
$t_mysql_logfile = "Log File";
$t_mysql_logfile_bulle = "The error log contains information indicating when MySQL was started and stopped and also any critical errors that occur while the server is running. If MySQL notices a table that needs to be automatically checked or repaired, it writes a message to the error log.";


$t_apache_folder = "EasyPHP Installation Folder";
$t_apache_conffile = "Configuration File";
$t_apache_conffile_bulle = "Apache is configured by placing directives in plain text configuration files. The main configuration file is called httpd.conf.";
$t_apache_parameters = "Parameters";
$t_apache_logfiles = "Log Files";
$t_apache_logfiles_bulle = "Feedback about the activity and performance of the server as well as any problems that may be occurring.";

$t_yourfiles = "YOUR FILES";
$t_yourmodules = "MODULES";

$t_donation = "Donation";

$t_settings_change = "change";
$t_settings_display = "display";
$t_settings_modify = "modify";
$t_settings_configuration = "configuration settings";
$t_settings_extensions = "extensions loaded";
$t_settings_mysqlerrorlog = "mysql error log";
$t_settings_apacheerrorlog = "apache error log";
$t_settings_apacheaccesslog = "apache access log";

$t_settings_addmoreversions = "Add more versions";
$t_settings_availableversions = "Available versions";
$t_settings_notes = "notes";
$t_settings_notes_descr = "You can use this space to write notes. This can be useful if you installed several times the same version of PHP with different settings.";



$hostname				= "Hostname";
$hostname_help			= "Due to incompatibilities with Windows Vista/Seven, 'localhost' is no longer used. Use '127.0.0.1' instead. For details, see FAQ";
$portnum				= "Port";
$easyphp_dir			= "EasyPHP folder";
$databases_dir			= "Databases folder";
$mysql_username			= "Username";
$mysql_password			= "Password";
$mysql_password_help	= "No password, leave it blank";
$mysql_host				= "Database Host";

$ao_warning = "Advanced options allow you to modify a configuration in such a way that you can jeopardize the integrity of the environement. So, use them wisely and be sure to know what you do.";
$ports_available_desc = "In order to avoid <b>port conflict</b>, if you use port 80 don't use applications that use the same port. Usual applications that may be using port 80 are, between others, Skype, Kazaa Lite, Norton Firewall (proxy function), IIS (under XP Pro), Yahoo Messenger, Internet Security...  To know which ports are used, you can scan your computer with <a href='http://technet.microsoft.com/en-us/sysinternals/bb897437' target='_blank'>TCPView</a>. If you have any port conflict, close your application and choose an other port for your application or EasyPHP. For details, see <a href='http://www.easyphp.org/faq.php' target='_blank'>FAQ</a>.";

//== Menu ==
$menu_mysql_manage = "Manage your databases";
$menu_section_parameters = "Parameters";
$menu_section_options = "Options";
$menu_section_advoptions = "Advanced options";
$menu_php_environment = "PHP environment";
$menu_php_extensions = "PHP extensions";
$menu_vhosts_add = "add virtual host";
$menu_alias_add = "add an alias";
$menu_module_add = "go to website";
$menu_phpconf = "PHP configuration";
$menu_apacheconf = "Apache configuration";
$php_timezone = "Time Zone";
$donate_title = "Support this project";
$donate_text = "EasyPHP is free and can be used and modified by anyone, including for commercial purposes.<br />If EasyPHP helped you in your projects or business, you can make a donation with PayPal through the <a href='https://sourceforge.net/donate/index.php?group_id=14045'>SourceForge donation system</a> (Unix name of this project on Sourceforge: quickeasyphp) or directly by clicking on a link below.<br />Thank you for your support!";

//== Info ==
$migration_title = "PHP 5.3 migration guide";
$migration_info = "Most improvements in PHP 5.3.x have no impact on existing code. However, there are a <a href='http://www.php.net/manual/en/migration53.incompatible.php'>few incompatibilities</a> and <a href='http://www.php.net/manual/en/migration53.new-features.php'>new features</a> that should be considered.";
$portable_title = "EasyPHP is portable";
$portable_info = "If you want to use EasyPHP on an USB drive, you just need to copy the entire EasyPHP folder on the key. Be sure that all your scripts are in the folder 'www' and your databases in 'mysql/data'.";

//== Local web ==
$localweb = "Local Web";
$localweb_intro = "You can place your files in the 'www' folder, in an alias or a virtual host. Thus, PHP will be able to interpret your pages (*.php).";
$t_localweb_bulle = "For small scripts, applications or projects you can choose the 'www' folder. All folders created in 'www' appear below.";

//== Docroot ==
$docroot_select = "Select a new path";
$docroot_change = "change";		
$docroot_default = "set to default";
$docroot_warning_1 = "Field is empty.";
$docroot_warning_2 = "The directory corresponding to the path you have chosen does not exist.";

//== Alias ==
$alias_title = "Alias";
$alias_none = "No alias created ";
$alias_delete = "delete";
$alias_intro = "Aliases allow you to place your files in directories other than Apache's root directory ('www'). This is a better choice if you develop a website or a complex application. Create a new folder, put all your files in it and create an alias for that folder. If you develop and maintain websites and large scale applications, you should use Virtual Hosts (see below).";
$alias_1 = "<span>Create a directory</span> (eg: C:\localweb\websites\site1)";
$alias_2 = "<span>Create a name for the Alias</span> (eg: site1)";
$alias_3 = "<span>Copy the path to the directory you have created</span> (eg: C:\weblocal\websites\site1)";
$alias_4 = "Default settings for the directory";
$alias_5 = "create";
$alias_warning_1 = "Field 2 is empty.";
$alias_warning_2 = "Field 3 is empty.";
$alias_warning_3 = "The directory corresponding to the path you have chosen does not exist.";
$alias_warning_4 = "This name, or a part of this name, is already used by the system.";

//== Virtual Hosts ==
$vhosts_title = "Virtual Hosts";
$vhosts_none = "No virtual host created ";
$vhosts_delete = "delete";

//== Phpinimanager - Apacheconf manager ==
$t_info = "info";	
$t_open = "open";		
$t_warning_phpini = "If you experience any problem, open the folder 'EasyPHP-xxx/conf_files/', delete or rename 'php.ini', rename the most recent backup 'php.ini' and restart EasyPHP.";
$t_warning_apacheconf = "If you experience any problem, open the folder 'EasyPHP-xxx/conf_files/', delete or rename 'httpd.conf', rename the most recent backup 'httpd.conf' and restart EasyPHP.";
$t_recommended = "<b>*</b> highly recommended";
$t_warning_save = "I have read the warning above &raquo; Save";

//== Virtual Hosts ==
$vhosts_norights = "Unfortunately, you do not have rights to create a Virtual Host. You must have write permissions on the file:";
$vhosts_activate = "activate";
$vhosts_desactivate = "deactivate";		
$vhosts_delete = "delete";
$vhosts_add_vhost = "add a virtual host";
$vhosts_add_vhost_chapo = "When maintaining and developing multiple sites / applications on intenet, it is helpful to have a copy of each site / application running on your local computer and to have them running in the same conditions (same server configuration, same path...). Virtual Hosts allow you to do that. Create a new folder, put all your files in it and create a Virtual Host for that folder.";
$vhosts_add_vhost_1 = "<span>If the directory you want to use doesn't exist, create it</span> (eg: C:\localweb\websites\projet1)";
$vhosts_add_vhost_2 = "<span>Create a name for the Servername</span> (eg: projet1)";
$vhosts_add_vhost_3 = "<span>Copy below the path to your directory</span> (eg: C:\weblocal\websites\projet1)";
$vhosts_add_vhost_4 = "create";
$vhosts_info = "info";
$vhosts_cancel = "cancel";	
$vhosts_close = "close";
$vhosts_warning_servername_1 = "The name can only contain alpha-numeric characters, dots, underscores and hyphens.";
$vhosts_warning_servername_2 = "If the name is an internet address, this address will redirected on your local computer. Not on internet.";
$vhosts_warning_conf = "If you experience any problem, open the folder 'EasyPHP-xxx/conf_files/', delete or rename 'httpd.conf', rename the most recent backup 'httpd.conf' and restart EasyPHP. Follow the same procedure with the hosts file ";
$vhosts_warning_url = "The name looks like an internet address. Be carefull. If the name chosen is www.google.com for example, the address http://www.google.com will be redirected to your local computer and won't be reachable on internet. You can do that, but don't forget to deactivate your virtual host or to delete it if you want go on internet.";
$vhosts_save = "I have read the warning above &raquo; Save";
$vhosts_no_vhost_created = "No virtual host created ";
$vhosts_add_vhost = "add a virtual host";
$t_add_vhost_warning_1	= "Warning : the name is empty.";
$t_add_vhost_warning_2	= "Warning : the path is empty.";
$t_add_vhost_warning_3	= "Warning : the directory corresponding to the path you have chosen does not exist.";
$t_add_vhost_warning_4	= "Warning : the name can only contain alpha-numeric characters, dots, underscores and hyphens.";
$t_add_vhost_warning_5	= "Warning : this name, or a part of this name, is already used by the system.";
$t_back = "back";

//== Extensions ==
$extensions_title = "EXTENSIONS";
$extensions_nb = "List of all modules compiled and loaded<br />You have %s extensions loaded.";
$extensions_show = "show";
$extensions_functions = "functions";

//== Modules ==
$module_none = "No module installed ";
$module_add = "Modules are pre-configured applications for EasyPHP. You can dowload, install and test immediately applications like WordPress, Spip, Drupal, Joomla!, Prestashop... Modules are downlable on EasyPHP website : ";
$module_uninstall = "How to uninstall a module ?";
$module_uninstall_folder = "If you want to uninstall a module, you need to move or delete the folder :";
$module_uninstall_db = "And you need to backup or delete (with PhpMyAdmin) the associated database :";

//== MySQl Info ==
$mysqlinfo_parameters_1 = "Host : '127.0.0.1'";
$mysqlinfo_parameters_2 = "Username : 'root'";
$mysqlinfo_parameters_3 = "Password : '' (no password)";
$mysqlinfo_parameters_4 = "Path to the database root (datadir)";
?>