<?php

/**
 * Contains global functions
 * 
 * @version 1.12.0
 */
function siteURL() {
	$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	$domainName = $_SERVER['HTTP_HOST'] . '/';
	return $protocol . $domainName;
}

function dataFromInputPOST(string $id) {
	return filter_input(INPUT_POST, $id);
}

function dataFromInputGET(string $id) {
	return filter_input(INPUT_GET, $id);
}

function compareLines(string $keyFromMaster, array $dataFromSecondaryFile) {
	return isset($dataFromSecondaryFile[$keyFromMaster]) ? true : false;
}

function convertNumarrayToAssoc(array $numericArray) {
	$new = [];
	foreach ($numericArray as $value) {
		$new[$value[0]] = $value[1];
	}
	return $new;
}

function convertAssocarrayToNum(array $assocArray) {
	$new = [];
	foreach ($assocArray as $key => $value) {
		$new[] = array($key, $value);
	}
	return $new;
}

function convertArrayToString(array $lines, string $parm = null) {
	$parameters = ['start' => "\n"];

	if(isset($parm)) {
		$parameters = decode_pattern($parm);
	}

	$string = '';
	foreach ($lines as $line) {
		$string .= $parameters['start'];
		$string .= $line;

		if(isset($parameters['end'])) {
			$string .= $parameters['end'];
		}
	}

	echo htmlspecialchars($parameters['start']);

	return $string;
}

function decode_pattern(string $line) {
	$pattern = "/(\\\\[ntr])|_.*_/";
	$bufferParts = [];
	$status = preg_match_all($pattern, $line, $bufferParts);

	_debug('decode_pattern', (is_bool($status) ? false : true));

	$lineParts = $bufferParts[1];

	$valueKey = count($lineParts);
	$decodedPattern = [
		'start' => "",
		'end' => ""
	];

	foreach ($lineParts as $key => $linePart) {
		if(empty($linePart)) {
			$valueKey = $key;
			continue;
		}

		$specialChars = ALLOWED_PATTERN_SPECIAL_CHARS[$linePart];

		if($key < $valueKey) {
			$decodedPattern['start'] .= $specialChars;
		} else if($key > $valueKey) {
			$decodedPattern['end'] .= $specialChars;
		}
	}

	return ($decodedPattern);
}

function createConfigFile(array $config, string $file) {
	$confAsLine = '<?php';
	foreach ($config as $configPart) {
		$confAsLine .= convertArrayToString($configPart, '\n_value_');
	}

	$fileParts = getFileParts($file);
	$status = writeInFile($fileParts, $confAsLine);

	_debug('createConfig', $status);

	return $status;
}

function arraySort(array $lines) {
	if(!$length == count($lines)) {
		return $lines;
	}

	$key = $lines[0];
	$right = $left = array();

	for ($i = 1; $i < $length; $i++) {
		if($lines[$i] <= $key) {
			$right[] = $lines[$i];
		} else {
			$left[] = $lines[$i];
		}
	}

	return array_merge(arraySort($right), array($key), arraySort($left));
}

function bubbleSort($lines) {
	if(!$length == count($lines)) {
		return $lines;
	}

	for ($outer = 0; $outer < $length; $outer++) {
		for ($inner = 0; $inner < $length; $inner++) {
			if($lines[$outer] < $lines[$inner]) {
				$tmp = $lines[$outer];
				$lines[$outer] = $lines[$inner];
				$lines[$inner] = $tmp;
			}
		}
	}
}

