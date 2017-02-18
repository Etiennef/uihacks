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
        global $CFG_GLPI;

        $replacement = self::getDBReplacementForEntity($_SESSION['glpiactive_entity']);

        if($replacement === false) {
            return false;
        } else {
            $pathToHome = $_SESSION['glpiactiveprofile']['interface'] === 'helpdesk' ? '/front/helpdesk.public.php' : '/front/central.php';

            $replacement = Toolbox::unclean_html_cross_side_scripting_deep(str_replace(array("\r", "\n"), '', $replacement));

            // Ajout du bouton de choix des entités
            $replacement = preg_replace('/%%%ENTITY_BUTTON_(.*?)%%%/',
                    '<a onclick="entity_window.show();" href="#modal_entity_content" class="entity_select">$1</a>', $replacement);

            // Ajout d'un select pour changer d'entité
            if(strstr($replacement, '%%%ENTITY_DROPDOWN%%%')) {
                // On construit le dropdown

                // D'abord on réccupère toutes les entités accessibles par cet utilisateur
                $entities_list = array();
                foreach($_SESSION['glpiactiveprofile']['entities'] as $entity) {
                    $tmp_tab = array();
                    if($entity['is_recursive']) {
                        foreach(getSonsOf('glpi_entities',$entity['id']) as $e) {
                            if(!in_array($e, $entities_list)) $entities_list[] = $e;
                        }
                    }
                    if(!in_array($entity['id'], $entities_list)) $entities_list[] = $entity['id'];
                }

                // Ensuite on les filtre selon les réglages d'ici (inutile d'envoyer l'utilisateur vers un autre fail)
                $entities_list = array_filter($entities_list, function($id) {
                    echo "/* $id =>".self::getDBReplacementForEntity($id)." : ".(self::getDBReplacementForEntity($id)===false)." */\r\n";
                    return self::getDBReplacementForEntity($id) === false;
                });

                $selectEntity = '<form name="form" method="get" action="'.$CFG_GLPI['root_doc'].$pathToHome.'">'.
                        Entity::dropdown(array(
                                'name' => 'active_entity',
                                'on_change' => 'submit()',
                                'display' => false,
                                'entity' => $entities_list
                        )).
                        Html::closeForm(false);

                $selectEntity = str_replace(array("\r", "\n"), '', $selectEntity);
                $replacement = preg_replace('/%%%ENTITY_DROPDOWN%%%/',$selectEntity, $replacement);
            }

            // Ajout d'un lien pour changer d'entité
            $replacement = preg_replace('/%%%ENTITY_LINK_(\d)_(.*?)%%%/',
                    '<a href="'.$CFG_GLPI['root_doc'].$pathToHome.'?active_entity=$1">$2</a>', $replacement);


            return $replacement;
        }


    }

    public static function getDBReplacementForEntity($entities_id) {
        // On passe en revue les règle et on renvoit la valeur pour la première règle rencontrée
        foreach(static::getRulesValues() as $rule) {

            //filtrage sur le profil
            if(!in_array($_SESSION['glpiactiveprofile']['id'], $rule['profiles'])) {
                continue;
            }

            //filtrage sur l'entité
            if(!in_array($entities_id, $rule['entities'])) {
                continue;
            }

            // Retire les passage de ligne et déséchappe la chaîne tout en retirant tout ce qui est script
            return $rule['replacement'];
        }

        return false;
    }
}























