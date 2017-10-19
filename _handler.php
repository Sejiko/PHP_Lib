<?php

require __DIR__ . '/library' . '/init_lib.php';

define('DOWNLOAD_PATH', SITE_URL . '/download/');

$files = getFilesFromDir(LIB_DIRECTORY, 'modules');
$stats = prepareStatistic($files, LIB_DIRECTORY);

preDump(MODULES, 'MODULES');

function generateTabelForModules() {
	$modules = is_existsModule();
	$htmlTable = '';

	foreach ($modules as $key => $module) {
		if($module) {
			$htmlTable .= '<h1 id="tableName">PHP_Library - ' . $key . ' Module</h1>';
			$list = getModuleFiles($key);
			$stats = prepareStatistic($list, './library/modules/'.$key.'_module/');
			$htmlTable .= createHtmlTable($stats);
		}
	}

	return $htmlTable;
}

function prepareStatistic(array $files, string $path) {
	$filesStats = [];
	$size = 0;

	foreach ($files as $file) {
		$filesStats[$file] = createFileStats($path . '/' . $file);
		$size += filesize($path . '/' . $file);
	}

	$filesStats['packageSize'] = fileSizeConvert($size);

	return $filesStats;
}

function createHtmlTable(array $list) {
	$htmlTable = '<table id="statistic"><tr id="tableSpanName">';
	$htmlTable .= '<th>Name</th>';
	$htmlTable .= '<td>Version</td>';
	$htmlTable .= '<td>Size</td>';
	$htmlTable .= '</tr>';

	foreach ($list as $key => $line) {
		if($key === 'packageSize') {
			$htmlTable .= '<tr><th>Full Package:</th>';
			$htmlTable .= '<td></td>';
			$htmlTable .= '<td>' . $list[$key] . '</td>';
			$htmlTable .= '</tr>';
			continue;
		}

		$htmlTable .= getInfosAsHtml($line, $main);
	}

	$htmlTable .= '</table>';

	return $htmlTable;
}

function getInfosAsHtml(array $lines) {
	$htmlTable = '<tr>';
	$htmlTable .= '<th>' . $lines['basename'] . '<div class="activateHint">?';
	$htmlTable .= '<span class="hint">' . $lines['description'] . '</span></div></th>';
	$htmlTable .= '<td>' . $lines['version'] . '</td>';
	$htmlTable .= '<td>' . $lines['filesize'] . '</td>';

	$htmlTable .= '</tr>';

	return $htmlTable;
}

function getDownloadHtmlModule(array $lines) {
	$download = DOMAIN . '/' . LIBRARY2 . '/' . $lines['basename'];

	$class = (getModule($lines['filename'])) ? 'downloadButtonDisabled' : 'downloadButton';
	$button = '<button type="submit" class="' . $class . '"><span>Click</span></button>';
	$link = createHiddenSendFormGET('download.php', $lines['basename'], $button);

	$htmlModule = $link;
}
