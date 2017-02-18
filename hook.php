<?php

/**
 * Fonction d'installation du plugin
 * @return boolean
 */
function plugin_uihacks_install() {

    include 'inc/forcechoiceconfig.class.php';
    PluginUihacksForcechoiceconfig::install();

    include 'inc/formeditrule.class.php';
    PluginUihacksFormeditrule::install();

    include 'inc/entityblockerrule.class.php';
    PluginUihacksEntityblockerrule::install();

    update100to110();

    return true;
}

/**
 * Fonction de désinstallation du plugin
 * @return boolean
 */
function plugin_uihacks_uninstall() {
    include 'inc/forcechoiceconfig.class.php';
    //PluginUihacksForcechoiceconfig::uninstall();

    include 'inc/formeditrule.class.php';
    //PluginUihacksFormeditrule::uninstall();

    include 'inc/entityblockerrule.class.php';
    //PluginUihacksEntityblockerrule::uninstall();

    return true;
}



function update100to110() {
    global $DB;

    $migration = new Migration('1.1.0');

    if(TableExists('glpi_plugin_uihacks_formeditrules')) {
        $query = "UPDATE `glpi_plugin_uihacks_formeditrules`
               SET `disabled` = 'disabled'
               WHERE `disabled` = '1'";
        $DB->query($query);

        $query = "UPDATE `glpi_plugin_uihacks_formeditrules`
               SET `disabled` = 'no'
               WHERE `disabled` = '0'";
        $DB->query($query);

    }


    if (FieldExists('glpi_plugin_uihacks_forcechoiceconfigs', 'is_activated')) {
        $migration->changeField('glpi_plugin_uihacks_forcechoiceconfigs',
                'is_activated', 'is_activated_for_type', 'varchar(25)',
                array('value' => '0'));
        $migration->addField('glpi_plugin_uihacks_forcechoiceconfigs',
                'is_activated_for_urgency', 'varchar(25)',
                array('update' => 'is_activated_for_type'));
        $migration->migrationOneTable('glpi_plugin_uihacks_forcechoiceconfigs');
    }
}















