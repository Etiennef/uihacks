<?php

/**
 * Fonction d'installation du plugin
 * @return boolean
 */
function plugin_uihacks_install() {
	include 'inc/forcechoiceconfig.class.php';
	PluginUihacksForcechoiceconfig::install();
	
	include 'inc/tooltiprule.class.php';
	PluginUihacksTooltiprule::install();
	
	include 'inc/entityblockerrule.class.php';
	PluginUihacksEntityblockerrule::install();
	
	return true;
}

/**
 * Fonction de désinstallation du plugin
 * @return boolean
 */
function plugin_uihacks_uninstall() {
	include 'inc/forcechoiceconfig.class.php';
	PluginUihacksForcechoiceconfig::uninstall();

	include 'inc/tooltiprule.class.php';
	PluginUihacksTooltiprule::uninstall();
	
	include 'inc/entityblockerrule.class.php';
	PluginUihacksEntityblockerrule::uninstall();
	
	return true;
}



















