<?php

/**
 * Main application configuration script
 * 
 * @package	AGCDR
 * @author	SBF
 * @copyright	2010-2011
 */

// full URL of your virtual host
define('LIVE_URL','http://fully.qualified.hostname/');

// main database server configuration (first server is the default)
$_SESSION["servers"] = array(
	"asterisk.server.hostname" => array(
		"description"	=> "Server description",
		"type"		=> "mysql",
		"hostname"	=> "db.server.hostname",
		"username"	=> "db_username",
		"password"	=> "db_password",
		"dbname"	=> "db_name",
		"tablename"	=> "db_table_name"
	)
);

/**
 * It's not necessary to change anything beyond this point but you obviously
 * can if you wish. Of most interest will be configuration that changes the
 * way the system looks, such as CHART_PALETTE AND JQUI_THEME.
 */

// release version
define('VERSION','1.0.1.0');

// beta version and development mode flags
define('BETA',true);
define('DEVMODE',true);

// application titles (short and long)
define('APP_TITLE','AGCDR');
define('LONG_TITLE','Asterisk CDR Statistics');

// jQuery UI theme to use
// (the theme must be installed in public/libraries/<jquery-ui>/css/)
define('JQUI_THEME','smoothness');

// chart colour palette to use
define('CHART_PALETTE','pastel');

// chart cache expiry (seconds, 86400 = 1 day, 604800 = 1 week)
define('CHART_CACHE_EXPIRE',604800);

?>