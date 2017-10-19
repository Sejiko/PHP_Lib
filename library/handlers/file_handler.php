<?php

/**
 * Contains functions for better work with Files
 * 
 * @version 1.3.1
 */
function fileSizeConvert(int $bytes) {
	$bytes = floatval($bytes);
	$arBytes = [
		[
			"UNIT" => "TB",
			"VALUE" => pow(1024, 4)
		],
		[
			"UNIT" => "GB",
			"VALUE" => pow(1024, 3)
		],
		[
			"UNIT" => "MB",
			"VALUE" => pow(1024, 2)
		],
		[
			"UNIT" => "KB",
			"VALUE" => 1024
		],
		[
			"UNIT" => "Byte",
			"VALUE" => 1
		],
	];

	foreach ($arBytes as $arItem) {
		if($bytes >= $arItem["VALUE"]) {
			$result = $bytes / $arItem["VALUE"];
			$result = str_replace(".", ",", strval(round($result, 2))) . " " . $arItem["UNIT"];
			break;
		}
	}

	return $result;
}

function openFile(array $file, string $mode) {
	if(!is_dir($file['dirname']))
		_debug('openFile', false);

	$fileName = $file['dirname'] . '/' . $file['basename'];
	$openedFile = fopen($fileName, $mode);

	$status = ($openedFile === false) ? false : true;

	_debug('openFile', $status);

	return $openedFile;
}

function writeInFile(array $file, string $line) {
	$fileHandle = openFile($file, 'w');

	$status = ($fileHandle === false) ? false : true;

	_debug('writeInFile', $status);

	fwrite($fileHandle, $line);
	fclose($fileHandle);

	return $status;
}

function createArrayFromFile(array $file) {
	$fileHandle = openFile($file, 'r');

	$status = false;
	$lines = [];

	if($fileHandle) {
		while (($line = fgets($fileHandle)) !== false) {
			$trimedLine = trim($line);
			array_push($lines, $trimedLine);
		}

		$status = true;
	}

	fclose($fileHandle);

	_debug('readFile', $status);

	return $lines;
}

function getFilesFromDir(string $path, string $exception = null) {
	_debug('filesFromDir', ((!is_dir($path)) ? false : true));

	$fileList = scandir($path);
	foreach ($fileList as $key => $file) {
		if(strpos($file, '.') === 0) {
			unset($fileList[$key]);
			continue;
		}

		if($file === $exception) {
			unset($fileList[$key]);
			continue;
		}

		if(is_dir($path . '/' . $file)) {
			$subDirFiles = getFilesFromDir($path . '/' . $file);

			unset($fileList[$key]);
			foreach ($subDirFiles as $subDirFile) {
				array_push($fileList, $file . '/' . $subDirFile);
			}
		}
	}

	sort($fileList);
	return $fileList;
}

function getDirNames(string $path, string $exception = null) {
	$dirs = [];
	foreach (glob($path, GLOB_ONLYDIR) as $dir) {
		if($dir === $exception) {
			continue;
		}

		$dir = str_replace('/', '', substr($dir, strripos($dir, '/')));
		array_push($dirs, $dir);
	}

	return $dirs;
}

function getFileParts(string $file) {
	return pathinfo($file);
}

function createFileStats(string $file) {
	$fileStats = getFileParts($file);
	$fileStats['version'] = getFileVersion($file);
	$fileStats['filesize'] = fileSizeConvert(filesize($file));
	$fileStats['description'] = getDescriptionFromPHPDocs($file);

	return $fileStats;
}

function getFilesFromServer(string $path, string $dir, string $type = '.php') {
	ini_set('max_execution_time', 300);
	$input_lines = file($path);
	$files = getFiles($input_lines, $type);
	$urls = createUrls($path, $files);

	foreach ($files as $key => $file) {
		$data = file_get_contents($urls[$key]);

		if(!is_dir($dir)) {
			mkdir($dir);
		}

		$status = file_put_contents($dir . $file, $data);
	}

	return $status;
}

function getFiles(array $urls, string $type) {
	$files = [];

	foreach ($urls as $url) {
		if(!strpos($url, 'href="') == true) {
			continue;
		}

		strtok($url, '"');
		$file = strtok('"');
		strtok('', '');

		if(strpos($file, $type) === true) {
			array_push($files, $file);
		}
	}

	return $files;
}

function createUrls(string $path, array $files) {
	$urls = [];

	foreach ($files as $key => $file) {
		$urls[$key] = $path . $file;
	}

	return $urls;
}

function getFileVersion(string $file) {
	$phpDocs = getPHPDocs($file);
	$version = 'No version set';

	foreach ($phpDocs as $phpDoc) {
		foreach ($phpDoc as $key => $doc) {
			if($key === '@version') {
				$version = 'Fileversion: ' . $doc;
				break;
			}
		}
	}

	return $version;
}

function getDescriptionFromPHPDocs(string $file) {
	$phpDocs = getPHPDocs($file);
	$description = 'No description';

	foreach ($phpDocs as $phpDoc) {
		foreach ($phpDoc as $key => $doc) {
			if($key === 'description') {
				$description = $doc;
			}
		}
	}

	return $description;
}

function makeDownload(string $dir, string $filename, string $type) {
	header("Content-Type: $type");

	header("Content-Disposition: attachment; filename=\"$filename\"");

	readfile($dir . $filename);
}
