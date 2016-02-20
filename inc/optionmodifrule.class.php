<?php

class PluginUihacksOptionmodifrule extends PluginConfigmanagerRule {
	protected static $inherit_order = array(self::TYPE_USER, self::TYPE_GLOBAL);
	
	static function makeConfigParams() {
		global $DB;
		
		// Réccupération de la table des profils
		$profiles = array();
		$query = "SELECT `id`, `name`
			FROM `".Profile::getTable()."`
			ORDER BY `name`";
			
		if ($result = $DB->query($query)) {
			while ($data = $DB->fetch_assoc($result)) {
				$profiles[$data['id']] = $data['name'];
			}
		}
		
		return array(
			'profiles' => array(
				'type' => 'dropdown',
				'text' => __('Profile'),
				'tooltip' => __('Do trick only for selected profiles', 'uihacks'),
				'values' => $profiles,
				'dbtype' => 'varchar(2500)',
				'default' => '[]',
				'options' => array(
					'multiple'=>true,
					'size'=>5
				)
			),
			'pageforms' => array(
				'type' => 'dropdown',
				'text' => __('Forms', 'uihacks'),
				'tooltip' => __('Forms we want to be affected by this rule', 'uihacks'),
				'values' => self::getPageFormUIOptions(),
				'dbtype' => 'varchar(2500)',
				'default' => '[]',
				'options' => array(
					'multiple'=>true,
					'size'=>3
				)
			),
			'name' => array(
				'type' => 'text input',
				'text' => __('Name', 'uihacks'),
				'tooltip' => __('Name of the input to modify. Use value of the "name" field in html, not displayed description text', 'uihacks'),
				'dbtype' => 'varchar(250)',
				'default' => '',
				'options' => array(
					'maxsize' => 250,
					'size' => 20
				)
			),
			'option' => array(
				'type' => 'text input',
				'text' => __('Options value', 'uihacks'),
				'tooltip' => __('Values of the option to modify. Use value of the "value" field in html, not displayed description text', 'uihacks'),
				'dbtype' => 'varchar(250)',
				'default' => '',
				'options' => array(
					'maxsize' => 250,
					'size' => 20
				)
			),
			'readonly1' => array(
				'type' => 'readonly text',
				'text' => '=>'
			),
			'tooltip' => array(
				'type' => 'text area',
				'text' => __('Add tooltip', 'uihacks'),
				'tooltip' => __('This tooltip will be added to the selected input. Leave empty for no tooltip.', 'uihacks'),
				'dbtype' => 'varchar(5000)',
				'default' => '',
				'options' => array(
					'maxsize' => 5000,
					'rows' => 5,
					'cols' => 25
				)
			),
			'disabled' => array(
				'type' => 'dropdown',
				'text' => __('Disable', 'uihacks'),
				'values' => array(
					'1' => Dropdown::getYesNo('1'),
					'0' => Dropdown::getYesNo('0')
				),
				'tooltip' => __('If yes is selected, this input will be disabled and the user won\'t be able to edit it. Keep in mind that it is only client-side modification, it has to be considered as a hint for the user not to edit this field, not a true right management', 'uihacks'),
				'dbtype' => 'varchar(25)',
				'default' => '0',
			)
		);
	}
	
	static function getPageFormIndex() {
		return array(
			'helpdesk_create' => array(
				'text' => __('Helpdesk ticket creation form', 'uihacks'),
				'patterns' => array(
					'/^helpdesk\\.public\\.php\\?create_ticket=1/' => 'helpdeskform',
					'/^tracking\\.injector\\.php' => 'helpdeskform'
				)
			),
			'standard_create' => array(
				'text' => __('Standard ticket creation form', 'uihacks'),
				'patterns' => array(
					'/^ticket\\.form\\.php(?!(\\?id=\d+))/' => 'form_ticket'
				)
			),
			'ticket_form' => array(
				'text' => __('Standard ticket modification form', 'uihacks'),
				'patterns' => array(
					'/^ticket\\.form\\.php\\?id=\d+/' => 'form_ticket'
				)
			)
		);
	}
	
	private static function getPageFormUIOptions() {
		$ret = array();
		foreach(self::getPageFormIndex() as $opt=>$desc) {
			$ret[$opt] = $desc['text'];
		}
		return $ret;
	}
	
	
}























