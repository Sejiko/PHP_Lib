<?php

/**
 * Containes functions for better work with HTML,
 * CSS and JavaScript
 * 
 * @version 1.1.1
 */

function createHiddenSendFormGET(string $action, string $value, string $buttonCode = null){
	$form = '<form style="margin: 0;" action="'.$action.'" method="get">';
	$form .= '<input style="display: none;" type="text" value="'.$value.'" name="FILE">';
	$form .= isset($buttonCode) ? $buttonCode : '<input type="submit" value="Submit">';
	$form .= '</form>';
	
	return $form;
}

function createHiddenSendFormPOST(string $action, string $value, string $buttonCode = null){
	$form = '<form style="margin: 0;" action="'.$action.'" method="post">';
	$form .= '<input style="display: none;" type="text" value="'.$value.'" name="hiddenPost">';
	$form .= isset($buttonCode) ? $buttonCode : '<input type="submit" value="Submit">';
	$form .= '</form>';
	
	return $form;
}