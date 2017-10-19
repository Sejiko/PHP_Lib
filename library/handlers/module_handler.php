<?php

/**
 * Handler for create and control Library Modules
 * 
 * @version 0.0.1
 */
function is_existsModule(string $moduleName = null) {
	$exists = [];

	if(!isset($moduleName) && count(MODULES) >= 1) {
		$moduleFiles = getModuleFiles();

		foreach ($moduleFiles as $key => $moduleFile) {
			$exists[$key] = in_array('init_' . $key . '_module.php', $moduleFile);
		}
	} else if(isset($moduleName)) {
		$module = (strripos($moduleName, '_module')) ? $moduleName : $moduleName . '_module';
		$key = substr($module, strripos($module, '_'));

		if(isset(MODULES[$module])) {
			$moduleFiles = getModuleFiles($module);
			$exists[$key] = in_array('init_' . $module . '.php', $moduleFiles);
		} else {
			$exists[$key] = false;
			console($module . ' is not exists!', 'is_existsModule');
		}
	}

	return $exists;
}

function initModules(string $modulName = null) {
	
}

function getModules() {
	$modules = [];
	$dirs = getDirNames('..' . MODULES_PATH . '*');

	foreach ($dirs as $dir) {
		$module = strtok($dir, '_');
		strtok('', '');

		$modules[$dir] = $module;
		$modules[$dir] = getModuleFiles($dir);
	}

	return $modules;
}

function getModuleFiles(string $moduleName = null) {
	if(isset($moduleName)) {
		$result = [];
		
		preDump($moduleName);

	} else {

	}

	return $result;
}
