<?php

class PluginUihacksFormeditrule extends PluginConfigmanagerRule {
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

      return array(
         '_header' => array(
            'type' => 'readonly text',
            'text' => self::makeHeaderLine(__('Html form customisation rules', 'uihacks'))
         ),
         'profiles' => array(
            'type' => 'dropdown',
            'maxlength' => 5000,
            'text' => __('Profile'),
            'tooltip' => __('Do trick only for selected profiles', 'uihacks'),
            'values' => $profiles,
            'default' => '[]',
            'multiple'=>true
         ),
         'page' => array(
            'type' => 'text area',
            'maxlength' => 500,
            'text' => __('Target urls', 'uihacks'),
            'tooltip' => __('use a PHP regexp matching the pages you want to alter', 'uihacks'),
            'default' => '@^$@',
            'rows' => 5,
            'cols' => 25
         ),
         'selector' => array(
            'type' => 'text area',
            'maxlength' => 500,
            'text' => __('Selector', 'uihacks'),
            'tooltip' => __('a CSS selector that will be able to targer exactly the items you want to alter', 'uihacks'),
            'default' => '',
            'rows' => 5,
            'cols' => 25
         ),
         'readonly1' => array(
            'type' => 'readonly text',
            'text' => '=>'
         ),
         'tooltip' => array(
            'type' => 'text area',
            'maxlength' => 5000,
            'text' => __('Add tooltip', 'uihacks'),
            'tooltip' => __('This tooltip will be added to the selected input. Leave empty for no tooltip.', 'uihacks'),
            'default' => '',
            'rows' => 5,
            'cols' => 25
         ),
         'disabled' => array(
            'type' => 'dropdown',
            'maxlength' => 25,
            'text' => __('Disable', 'uihacks'),
            'values' => array(
               '1' => Dropdown::getYesNo('1'),
               '0' => Dropdown::getYesNo('0')
            ),
            'tooltip' => __('If yes is selected, this input will be disabled and the user won\'t be able to edit it. Keep in mind that it is only client-side modification, it has to be considered as a hint for the user not to edit this field, not a true right management. Also, if you disable fileds in creation forms, it may make the creation impossible.', 'uihacks'),
            'default' => '0',
         )
      );
   }

   public static function makeJsRules() {
      $jsRules = array();

      // On passe en revue les règle et on les décline en règles basiques pour le js
      foreach(static::getRulesValues() as $rule) {
         //filtrage sur le profil
         if(!in_array($_SESSION['glpiactiveprofile']['id'], $rule['profiles']))
            continue;

         //filtrage sur la page d'appel
         if(!preg_match(
               preg_replace( "/\r|\n/", "", $rule['page']), // on retire les passages à la ligne du textarea
               preg_replace("/([^\/]*\/)*([^\/]*)/", "$2", $_SERVER['HTTP_REFERER'])
               ))
            continue;

         $jsRules[] = array(
            'selector' => preg_replace( "/\r|\n/", "", $rule['selector']),
            'tooltip' => $rule['tooltip'],
            'disabled' => ($rule['disabled']?true:false)
         );
      }

      return $jsRules;
   }


}























