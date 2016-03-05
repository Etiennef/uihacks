<?php

class PluginUihacksForcechoiceconfig extends PluginConfigmanagerConfig {

   static function makeConfigParams() {
      return array(
         '_title' => array(
            'type' => 'readonly text',
            'types' => array(self::TYPE_PROFILE, self::TYPE_GLOBAL),
            'text' => self::makeHeaderLine(__('Force explicit field selection on ticket creation', 'uihacks'))
         ),
         'is_activated' => array(
            'type' => 'dropdown',
            'types' => array(self::TYPE_PROFILE, self::TYPE_GLOBAL),
            'maxlength' => 25,
            'text' => __('Force explicit type and urgency choice on ticket creation', 'uihacks'),
            'values' => array(
               '1' => Dropdown::getYesNo('1'),
               '0' => Dropdown::getYesNo('0')
            ),
            'default' => '0'
         ),
         'category_msg' => array(
            'type' => 'text area',
            'types' => array(self::TYPE_GLOBAL),
            'maxlength' => 500,
            'text' => __('Message replacing category dropdown when type is not chosen yet', 'uihacks'),
            'default' => __('You have to choose the ticket type before being allowed to chose a category', 'uihacks')
         ),
         'bad_submit_msg' => array(
            'type' => 'text area',
            'types' => array(self::TYPE_GLOBAL),
            'maxlength' => 500,
            'text' => __('Error message when user tries to validate when not both type and category are chosen', 'uihacks'),
            'default' => __('You have to choose the ticket type and urgency before being allowed to submit the ticket', 'uihacks')
         )
      );
   }
}