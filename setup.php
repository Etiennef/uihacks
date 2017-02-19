<?php

/**
 * Fonction de définition de la version du plugin
 * @return array description du plugin
 */
function plugin_version_uihacks() {
   return array(
      'name' => "UI Hacks",
      'version' => '1.1.0',
      'author' => 'Etiennef',
      'license' => 'GPLv2+',
      'homepage' => 'https://github.com/Etiennef/uihacks',
      'minGlpiVersion' => '0.84'
   );
}

/**
 * Fonction de vérification des prérequis
 * @return boolean le plugin peut s'exécuter sur ce GLPI
 */
function plugin_uihacks_check_prerequisites() {
   if(version_compare(GLPI_VERSION, '0.84.8', 'lt') || version_compare(GLPI_VERSION, '0.85', 'ge')) {
      echo __("Plugin has been tested only for GLPI 0.84.8", 'uihacks');
      return false;
   }

   //Vérifie la présence de ConfigManager
   if(!(new Plugin())->isActivated('configmanager')) {
      echo __("Plugin requires ConfigManager 1.x.x", 'uihacks');
      return false;
   }
   $configmanager_version = Plugin::getInfo('configmanager', 'version');
   if(version_compare($configmanager_version, '1.0.0', 'lt') || version_compare($configmanager_version, '2.0.0', 'ge')) {
      echo __("Plugin requires ConfigManager 1.x.x", 'uihacks');
      return false;
   }

   return true;
}

/**
 * Fonction de vérification de la configuration initiale
 * @param type $verbose
 * @return boolean la config est faite
 */
function plugin_uihacks_check_config($verbose = false) {
   return true;
}

/**
 * Fonction d'initialisation du plugin.
 * @global array $PLUGIN_HOOKS
 */
function plugin_init_uihacks() {
   global $PLUGIN_HOOKS;

   $PLUGIN_HOOKS['csrf_compliant']['uihacks'] = true;

   $PLUGIN_HOOKS['add_javascript']['uihacks'] = array(
         '../../lib/jquery/js/jquery-1.10.2.min.js',
         'scripts/formedit.js.php',
         );
   if(preg_match("/^([^\/]*\/)*(helpdesk\.public\.php\?create_ticket=1|tracking\.injector\.php|ticket\.form\.php(?!(\?id=\d+))).*/", $_SERVER['REQUEST_URI']) ) {

      // On n'ajoute ces js que sur la page de créaiton du ticket
      $PLUGIN_HOOKS['add_javascript']['uihacks'][] = 'scripts/entityblocker.js.php';
      $PLUGIN_HOOKS['add_javascript']['uihacks'][] = 'scripts/forcechoice.js.php';
   }

   Plugin::registerClass('PluginUihacksForcechoiceconfig');
   Plugin::registerClass('PluginUihacksFormeditrule');
   Plugin::registerClass('PluginUihacksEntityblockerrule');
   Plugin::registerClass('PluginUihacksTabmerger', array('addtabon' => array(
      'Profile',
      'Config'
   )));

   if((new Plugin())->isActivated('uihacks')) {
      $PLUGIN_HOOKS['config_page']['uihacks'] = "../../front/config.form.php?forcetab=" . urlencode('PluginUihacksTabmerger$1');
   }


}
