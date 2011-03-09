#!/usr/bin/php
<?php

/**
 * Compile a release tarball from a working copy.
 * 
 * @package	AGCDR
 * @author	Stuart Benjamin Ford <stuartford@me.com>
 * @copyright	09/03/2011
 */

// my path
$mypath = realpath(dirname(array_shift($argv)));

// get source directory
if (isset($argv[0])) {
	$srcdir = array_shift($argv);
} else {
	die("Usage: {$mypath} <path to working directory> [output directory path]\n");
}

// get output dir
if (isset($argv[0])) {
	$outpath = array_shift($argv);
	if (!is_dir($outpath)) die("Output directory {$outpath} does not exist.\n");
} else {
	$outpath = ".";
}

// determine version number and create file path
require_once("{$mypath}/../application/config.php");
$filepath = "{$outpath}/".strtolower(APP_TITLE)."-".VERSION.".tar.gz";

// exclusions
$exclusions = array(
	"*svn*",					// SVN crap
	"debug",
	".buildpath",".project",".settings",		// Eclipse crap
	".DS_Store",					// Mac OS crap
	"*.*~",						// text editor crap
	"docs/phpdoc"					// phpDoc directory
);

// create command list
$commands = array(
	"rm -f {$mypath}/../public/images/charts/*.png",
	"tar -czf {$filepath} -C {$srcdir} application public docs data --exclude=".implode(" --exclude=",$exclusions)
);

// run commands
foreach ($commands as $cmd) {
	print "{$cmd}\n";
	system($cmd);
}

?>
