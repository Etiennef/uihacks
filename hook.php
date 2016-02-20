<?php

/**
 * Fonction d'installation du plugin
 * @return boolean
 */
function plugin_uihacks_install() {
	include 'inc/config.class.php';
	PluginUihacksConfig::install();

	include 'inc/optionmodifrule.class.php';
	PluginUihacksOptionmodifrule::install();
	
	include 'inc/inputmodifrule.class.php';
	PluginUihacksInputmodifrule::install();

	return true;
}

/**
 * Fonction de désinstallation du plugin
 * @return boolean
 */
function plugin_uihacks_uninstall() {
	include 'inc/config.class.php';
	PluginUihacksConfig::uninstall();

	include 'inc/optionmodifrule.class.php';
	PluginUihacksOptionmodifrule::uninstall();
	
	include 'inc/inputmodifrule.class.php';
	PluginUihacksInputmodifrule::uninstall();
	
	
	return true;
}



















