<?php

/**
 * Contains utils Functions
 * 
 * @version 1.5.0
 */
function console($data, string $name = null) {
	$output = $data;
	$outputName = isset($name) ? $name : '';

	if(is_array($output)) {
		$output = implode(',', $output);
	}

	echo "<script>console.log('Debug " . $outputName . ": " . $output . "');</script>";
}

function _debug(string $debug_name, bool $status = false, string $par = null) {
	if(!DEBUG_MODE)
		return;

	global $_debug;

	if(!isset($_debug[$debug_name])) {
		console(false, 'Debug ' . 'debug_name');

		return;
	}

	$debug = ($status !== $_debug[$debug_name]) ? 'true' : 'false';

	console($debug, 'Function ' . $debug_name);

	if($par === 'dontDie')
		return;

	($status === false) ? die : null;
}

function preDump($var, string $description = null) {
	echo '<div id="preDump"><p id="preDumpDesc">' . $description . '</p>';
	echo '<pre id="varDump">';
	var_dump($var);
	echo '</pre>' . '</div>';
}

function getPHPDocs(string $file) {
	$comments = [];
	$replaceNeedle = ['/', '*'];
	$description = '';

	$tokens = token_get_all(file_get_contents($file));
	foreach ($tokens as $key => $token) {
		$i = 0;

		if($token[0] != T_DOC_COMMENT) {
			continue;
		}

		$strings = explode("\n", str_replace($replaceNeedle, '', $token[1]));

		foreach ($strings as $strKey => $string) {
			$trimedString = trim($string);
			$subKey = strtok($trimedString, ' ');
			$subStr = strtok('');
			strtok('', '');

			if(!empty($trimedString) && strpos($subKey, '@') === 0) {
				$strings[$subKey] = trim($subStr);
			} else if(!empty($trimedString)) {
				$description .= $trimedString . ' ';
			}

			unset($strings[$strKey]);
		}

		$strings['description'] = $description;
		$comments[$key] = $strings;
	}

	return $comments;
}

function is_included(string $fileName) {
	$success = false;

	$included_files = get_included_files();

	foreach ($included_files as $included_file) {
		$included_name = pathinfo($included_file);

		if($included_name['basename'] === $fileName) {
			$success = true;
		}
	}

	return $success;
}

function includeDir(string $path, bool $_once = null) {
	if(!is_dir($path)) {
		console('No such directory', 'includeDir');
		_debug('includeDir');
	}

	$dir = new RecursiveDirectoryIterator($path);
	$iterator = new RecursiveIteratorIterator($dir);

	foreach ($iterator as $file) {
		$fileName = $file->getFilename();

		if(preg_match('%\.php$%', $fileName)) {
			if($_once === true) {
				include_once($file->getPathname());
			} else {
				include($file->getPathname());
			}
		}
	}
}

function requireDir(string $path, bool $_once = null) {
	if(!is_dir($path)) {
		console('No such directory', 'requireDir');
		_debug('requireDir');
	}

	$dir = new RecursiveDirectoryIterator($path);
	$iterator = new RecursiveIteratorIterator($dir);

	foreach ($iterator as $file) {
		$fileName = $file->getFilename();

		if(preg_match('%\.php$%', $fileName)) {
			if($_once === true) {
				require_once($file->getPathname());
			} else {
				require($file->getPathname());
			}
		}
	}
}

function is_config_exists(string $config) {
	$success = file_exists($config) ? true : false;

	_debug('is_config_exists', $success, 'dontDie');

	return $success;
}

function is_multi_dim_array(array $map) {
	$success = false;

	if(!count($map) === !count($map, COUNT_RECURSIVE)) {
		$success = true;
	}

	return $success;
}
