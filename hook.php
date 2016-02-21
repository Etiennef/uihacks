<?php

/**
 * Fonction d'installation du plugin
 * @return boolean
 */
function plugin_uihacks_install() {
	include 'inc/config.class.php';
	PluginUihacksConfig::install();

	
	include 'inc/rule.class.php';
	PluginUihacksRule::install();

	return true;
}

/**
 * Fonction de désinstallation du plugin
 * @return boolean
 */
function plugin_uihacks_uninstall() {
	include 'inc/config.class.php';
	PluginUihacksConfig::uninstall();


	include 'inc/rule.class.php';
	PluginUihacksRule::uninstall();
	
	return true;
}



















