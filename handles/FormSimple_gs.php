<?php
/**
 * FormSimple for GetSimple - Simply add forms in your pages
 *
 * This file is the handle of FormSimple for GetSimple.
 *
 * @link http://nliautaud.fr/wiki/travaux/FormSimple Documentation
 * @link http://get-simple.info/extend/plugin/FormSimple/35 Latest Version
 * @author Nicolas Liautaud <contact@nliautaud.fr>
 * @package FormSimple
 */
/*
 * Define where is FormSimple from GS root (for securimage captcha).
 */
define('FSPATH', 'plugins/FormSimple/');
/*
 * Define FormSimple global default language according to GS (optionnal).
 */
define('FSLANG', substr($LANG, 0, 2));
/*
 * Include FormSimple.
 */
require_once GSPLUGINPATH . 'FormSimple/FormSimple.php';
$thisfile = basename(__FILE__, '.php');
register_plugin(
    $thisfile,                  // ID of plugin, should be filename minus php
    'FormSimple',               // Title of plugin
    FormSimple::version(),      // Version of plugin
    'Nicolas Liautaud',         // Author of plugin
    'http://nliautaud.fr',      // Author URL
    'Simply add forms in your pages',  // Plugin Description
    'plugins',                  // Type of plugin
    'FormSimple::config'        // Method that displays backoffice content
);
/*
 * Add a menu entry in plugins sidebar for FormSimple config.
 */
add_action('plugins-sidebar', 'createSideMenu', array($thisfile, 'FormSimple'));
/*
 * Add a link to FormSimple default stylesheet in page header if needed.
 */
add_action('theme-header', 'FormSimple_header');
function FormSimple_header()
{
    if(FormSimple::setting('use_default_style'))
	{
		echo '<link rel="stylesheet" type="text/css" ';
		echo 'href="plugins/FormSimple/style.css" />';
	}
}
/*
 * Parse page content to replace all tags by forms.
 */
add_filter('content', 'FormSimple::parse');
?>