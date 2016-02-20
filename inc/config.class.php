<?php

class PluginUihacksConfig extends PluginConfigmanagerConfig {
	
	static function makeConfigParams() {
		return array(
			'force_type_choice' => array(
				'type' => 'dropdown',
				'text' => __('Force ticket type choice', 'uihacks'),
				'values' => array(
					'1' => Dropdown::getYesNo('1'),
					'0' => Dropdown::getYesNo('0')
				),
				'types' => array(self::TYPE_PROFILE, self::TYPE_GLOBAL),
				'dbtype' => 'varchar(25)',
				'default' => '0'
			),
			'missing_category_msg' => array(
				'type' => 'text area',
				'text' => __('Message replacing category dropdown when type is not chosen yet', 'uihacks'),
				'types' => array(self::TYPE_GLOBAL),
				'dbtype' => 'varchar(500)',
				'default' => __('You have to choose the ticket type before being allowed to chose a category', 'uihacks')
			),
			'force_urgency_choice' => array(
				'type' => 'dropdown',
				'text' => __('Force ticket urgency choice', 'uihacks'),
				'values' => array(
					'1' => Dropdown::getYesNo('1'),
					'0' => Dropdown::getYesNo('0')
				),
				'types' => array(self::TYPE_PROFILE, self::TYPE_GLOBAL),
				'dbtype' => 'varchar(25)',
				'default' => '0'
			)
		);
	}
}