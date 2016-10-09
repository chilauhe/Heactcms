<?php

/*	====================================================

	EMCE
	http://www.emce.org
	Copyright (C) 2011 Jon Belelieu, 2016 Chilau He

	File Function: Loads all necessary classes for the
	basic functions of the Ascad engine and the program.
	This file is called from the "config.php" file. Always
	load config.php first when loading this program.

	Licensed under the MIT and GPL licenses:
	http://www.opensource.org/licenses/mit-license.php

====================================================== */


// ----------------------------------------
// 	Global Options

include PATH . "/generated/globals.php";
require PATH . "/includes/general.functions.php";


// ----------------------------------------
// 	Breadcrumb Divider

$divider = ' / ';

// ----------------------------------------
// 	Database class
// require PATH . "/includes/db.functions.php";
//$db = new db;

$db = new db();
$db->connect();

define("NAME",$db->get_option('site_name'));

// ----------------------------------------
// 	Sessions class
// require PATH . "/includes/session.functions.php";
// $session = new session;

$session = new session();

// ----------------------------------------
// 	Template-rendering class
// require PATH . "/includes/template.functions.php";
// $template = new template;

$template = new template();

// ----------------------------------------
// 	Detect mobile browsers

$mobile = $template->detect_mobile();
if ($mobile == '1' && $_COOKIE['skip_mobile'] != '1') {
	define('BD_MOBILE','1');
	$template_folder = 'mobile';
} else {
	define('BD_MOBILE','0');
	$template_folder = 'html';
}


// ----------------------------------------
// 	Load the current theme
//	Defaults to the "default" theme
// 	Check for SSL as well

$theme_info = $template->get_theme();
$theme = $theme_info['theme'];
$theme_type = $theme_info['theme_type'];
$theme_path = PATH . "/templates/" . $template_folder . "/" . $theme;

$check_ssl = $db->check_ssl();
if ($check_ssl == '1') {
	$temp_url = URLHOLD;
	$temp_url = str_replace('http://','https://',$temp_url);
	//unset($GLOBALS['URL']);
	define('URL',$temp_url);
	// $GLOBALS['URL'] = $temp_url;
	//$GLOBALS['URL'] = $temp_url;
	$theme_folder = $temp_url . "/templates/" . $template_folder . "/" . $theme;
	$theme_images = $temp_url . "/templates/" . $template_folder . "/" . $theme . "/imgs";
} else {
	define('URL',URLHOLD);
	$theme_folder = URL . "/templates/" . $template_folder . "/" . $theme;
	$theme_images = URL . "/templates/" . $template_folder . "/" . $theme . "/imgs";
}

define('THEME',$theme_folder);
define('THEME_PATH',$theme_path);
define('THEME_IMAGES',$theme_images);

// ----------------------------------------
// 	Check a user's session and return
//	the user's privilieges

$user = $session->check_logged();
$banned = $session->check_banned('',$user);
if (! empty($user)) {
	$privileges = $session->get_user_privileges($user);
	$user_data = $session->get_user_data($user);
} else {
	$privileges = array();
	$user_data = array();
}


// ----------------------------------------
// 	Banana Dance primary class

// require PATH . "/includes/primary.functions.php";
// $manual = new manual;
$manual = new manual();

?>
