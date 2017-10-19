<?php

/**
 * Global Vars
 * 
 * @version 1.2.0
 */

define('DEBUG_MODE', true);

define('ALLOWED_PATTERN_SPECIAL_CHARS', [
	'\n' => "\n",
	'\t' => "\t",
	'\r' => "\r"
]);

define( 'SITE_URL', siteURL() );

define('LIB_DIRECTORY', __DIR__);

define('LIBRARY2', '/PHP_Library/library/'); // TODO Change to
define('LIBRARY', '/library/');

define('MODULES_PATH', LIBRARY2.'modules/');
define('MODULES_PATH2', LIBRARY.'modules/');
define('MODULES', getModules());



