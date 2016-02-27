<?php

class PluginUihacksEntityblockerrule extends PluginConfigmanagerRule {
	protected static $inherit_order = array(self::TYPE_GLOBAL);
	
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
		
		// Réccupération de la table des entités
		$entities = array();
		$query = "SELECT `id`, `completename`
			FROM `".Entity::getTable()."`
			ORDER BY `completename`";
			
		if ($result = $DB->query($query)) {
			while ($data = $DB->fetch_assoc($result)) {
				$entities[$data['id']] = $data['completename'];
			}
		}
		
		return array(
			'_header' => array(
				'type' => 'readonly text',
				'text' => self::makeHeaderLine(__('Rules for ticket creation prevention based on entities', 'uihacks'))
			),
			'profiles' => array(
				'type' => 'dropdown',
				'maxlength' => 5000,
				'text' => __('Profile'),
				'values' => $profiles,
				'default' => '[]',
				'multiple'=>true,
				'size'=>5
			),
			'entities' => array(
				'type' => 'dropdown',
				'maxlength' => 50000,
				'text' => __('Blocked entities', 'uichacks'),
				'values' => $entities,
				'default' => '[]',
				'multiple'=>true,
				'size'=>5
			),
			'replacement' => array(
				'type' => 'text area',
				'maxlength' => 50000,
				'text' => __('Replacement', 'uihacks'),
				'tooltip' => __('This will replace the ticket creation forms. You can use HTLM code here. You can use %%%ENTITY_BUTTON_(.*)%%% to insert a button leading to the entity change menu.', 'uihacks'),
				'default' => '<table class="tab_cadre_fixe"><tr><th>' . __('Ticket creation has been disabled here', 'uihacks') . '</th></tr><tr><td class=center>%%%ENTITY_BUTTON_' . __('Click here to select an other entity', 'uihacks') . '%%%</td></tr></table>',
				'maxsize' => 50000,
				'rows' => 10,
				'cols' => 50
			)
		);
	}
	
	public static function getApplicableReplacement() {
		if(!isset($_SERVER['HTTP_REFERER']) ||
				!preg_match("/^([^\/]*\/)*(helpdesk\.public\.php\?create_ticket=1|tracking\.injector\.php|ticket\.form\.php(?!(\?id=\d+))).*/",
					$_SERVER['HTTP_REFERER']))
			return false;
		
		// On passe en revue les règle et on renvoit la valeur pour la première règle rencontrée
		foreach(static::getRulesValues() as $rule) {
				
			//filtrage sur le profil
			if(!in_array($_SESSION['glpiactiveprofile']['id'], $rule['profiles']))
				continue;
			
			//filtrage sur l'entité
			if(!in_array($_SESSION['glpiactive_entity'], $rule['entities']))
				continue;
			
			// Retire les passage de ligne et déséchappe la chaîne tout en retirant tout ce qui est script
			$replacement = Toolbox::unclean_html_cross_side_scripting_deep(str_replace(array("\r", "\n"), '', $rule['replacement']));
			
			// Remplace le schéma %%%ENTITY_BUTTON_(.*)%%% par le code html permettant d'afficher le bouton de sélection du profil
			$replacement = preg_replace('/%%%ENTITY_BUTTON_(.*)%%%/', '<a onclick="entity_window.show();" href="#modal_entity_content" class="entity_select">$1</a>', $replacement);
			
			return $replacement;
		}
		
		return false;
	}
	
	
}























