<?php

//include_once GLPI_ROOT . '/plugins/uihacks/inc/optionmodifrule.class.php';

class PluginUihacksInputmodifrule extends PluginConfigmanagerRule {
	protected static $inherit_order = array(self::TYPE_USER, self::TYPE_GLOBAL);
	
	static function makeConfigParams() {
		$params = PluginUihacksOptionmodifrule::makeConfigParams();
		unset($params['option']);
		return $params;
	}
	
	
}