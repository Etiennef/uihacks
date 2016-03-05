<?php

class PluginUihacksTabmerger extends PluginConfigmanagerTabmerger {
   protected static function getTabsConfig() {
      return array(
         // '__.*' => 'html code',
         // CommonGLPI => tabnum|'all',
         'PluginUihacksForcechoiceconfig' => 'all',
         'PluginUihacksEntityblockerrule' => 'all',
         'PluginUihacksFormeditrule' => 'all',
         '__cheat sheets' => self::makeCheatSheets(),
      );
   }


   private static function makeCheatSheets() {
      $output = '';

      $output .= '<table class="tab_cadre_fixe"><tr><th colspan="3">Cheat sheets to help you find the regexps</th></tr>';
      $output .= '<tr><th rowspan="5">Regexp for ticket pages</th>';
      $output .= '<td>Match helpdesk ticket creation page</td><td>@^(helpdesk\.public\.php\?create_ticket=1|tracking\.injector\.php)@</td></tr>';
      $output .= '<tr><td>Match standard ticket creation page</td><td>@^ticket\\.form\\.php(?!(\\?id=\\d+))@</td></tr>';
      $output .= '<tr><td>Match ticket update form</td><td>@^ticket\\.form\\.php\\?id=\\d+@</td></tr>';
      $output .= '<tr><td>Match both standard creation and ticket update forms</td><td>@^ticket\.form\.php@</td></tr>';
      $output .= '<tr><td>Match all the ticket forms</td><td>@^(helpdesk\.public\.php\?create_ticket=1|tracking\.injector\.php|ticket\.form\.php)@</td></tr>';

      $selectors = self::getSelectorsHelpdesk();
      $output .= '<tbody id="uihacks_getSelectorsHelpdesk"><tr><th rowspan="'.(count($selectors)+1).'">Selectors for helpdesk form</th></tr>';
      foreach($selectors as $text=>$selector) {
         $output .= '<tr><td>'.$text.'</td><td>'.$selector.'</td></tr>';
      }
      $output .= '</tbody>';

      $selectors = self::getSelectorsStandard();
      $output .= '<tbody id="uihacks_getSelectorsStandard"><tr><th rowspan="'.(count($selectors)+1).'">Selectors for standard form</th></tr>';
      foreach($selectors as $text=>$selector) {
         $output .= '<tr><td>'.$text.'</td><td>'.$selector.'</td></tr>';
      }
      $output .= '</tbody>';

      $selectors = self::getSelectorsTicketUpdate();
      $output .= '<tbody id="uihacks_getSelectorsTicketUpdate"><tr><th rowspan="'.(count($selectors)+1).'">Selectors for ticket update form</th></tr>';
      foreach($selectors as $text=>$selector) {
         $output .= '<tr><td>'.$text.'</td><td>'.$selector.'</td></tr>';
      }
      $output .= '</tbody>';

      $output .= '</table>';
      return $output;
   }


   static function getSelectorsHelpdesk() {
      return array(
         __('Type') => 'select[name="type"]',
         __('Category') => 'input[name="itilcategories_id"]',
         __('Requester') => 'select[name="_users_id_requester_notif[use_notification]"]',
         __('Requester') .'-'. __('Email followup') .'-'. __('Email') => 'input[name="_users_id_requester_notif[alternative_email]"]',
         __('Urgency') => 'select[name="urgency"]',
         __('Urgency').'-'.Ticket::getUrgencyName(1) => 'select[name="urgency"] option[value="1"]',
         __('Hardware type') => 'select[name="_my_items"]',
         __('Hardware type') . " - " . __('Or complete search') => 'select[name="itemtype"]',
         __('Location') => 'select[name="locations_id"]',
         __('Title') => 'input[name="name"]',
         __('Description') => 'textarea[name="content"]'
      );
   }

   static function getSelectorsStandard() {
      return array(
         __('Opening date') => 'input[name="date"]',
         __('Due date') => 'input[name="due_date"]',
         __('SLA') => 'select[name="slas_id"]',
         __('Type') => 'select[name="type"]',
         __('Category') => 'input[name="itilcategories_id"]',

         __('Requester user') => 'select[name="_users_id_requester"]',
         //TODO entité qui pop quand je choisis le demandeur
         __('Requester user') .'-'. __('Email followup') => 'select[name="_users_id_requester_notif[use_notification]"]',
         __('Requester user') .'-'. __('Email followup') .'-'. __('Email') => 'input[name="_users_id_requester_notif[alternative_email]"]',
         __('Requester group') => 'select[name="_groups_id_requester"]',
         __('Watcher user') => 'select[name="_users_id_observer"]',
         __('Watcher user') .'-'. __('Email followup') => 'select[name="_users_id_observer_notif[use_notification]"]',
         __('Watcher user') .'-'. __('Email followup') .'-'. __('Email') => 'input[name="_users_id_observer_notif[alternative_email]"]',
         __('Watcher group') => 'select[name="_groups_id_observer"]',
         __('Technician') => 'select[name="_users_id_assign"]',
         __('Technician') .'-'. __('Email followup') => 'select[name="_users_id_assign_notif[use_notification]"]',
         __('Technician') .'-'. __('Email followup') .'-'. __('Email') => 'input[name="_users_id_assign_notif[alternative_email]"]',
         __('Group in charge of the ticket') => 'select[name="_groups_id_assign"]',
         __('Supplier') => 'select[name="_suppliers_id_assign"]',

         __('Status') => 'select[name="status"]',
         __('Status').'-'.Ticket::getStatus(1) => 'select[name="status"] option[value="1"]',
         __('Request source') => 'select[name="requesttypes_id"]',
         __('Urgency') => 'select[name="urgency"]',
         __('Urgency').'-'.Ticket::getUrgencyName(1) => 'select[name="urgency"] option[value="1"]',
         __('Approval request') => 'select[name="_add_validation"]',
         __('Impact') => 'select[name="impact"]',
         __('Impact').'-'.Ticket::getImpactName(1) => 'select[name="impact"] option[value="1"]',
         __('Hardware type') => 'select[name="itemtype"]',
         __('Priority') => 'select[name="priority"]',
         __('Priority').'-'.Ticket::getPriorityName(1) => 'select[name="priority"] option[value="1"]',
         __('Total duration') => 'select[name="actiontime"]',
         __('Location') => 'select[name="locations_id"]',

         __('Title') => 'input[name="name"]',
         __('Description') => 'textarea[name="content"]',
         __('Linked ticket').'-'.__('link type', 'uihacks') => 'select[name="_link[link]"]',
         __('Linked ticket').'-'.__('Ticket id', 'uihacks') => 'input[name="_link[tickets_id_2]"]'
      );
   }

   static function getSelectorsTicketUpdate() {
      return array(
         __('Opening date') => 'input[name="date"]',
         __('Due date') => 'input[name="due_date"]',
         __('SLA') => 'select[name="slas_id"]',
         __('Assign a SLA') => '#sla_action a.vsubmit[onclick*="sla_choice"]',
         __('SLA') .'-'. _x('button', 'Delete permanently') => 'a.vsubmit[onclick*="sla_delete"]',
         __('By') => 'select[name="users_id_recipient"]',
         __('Type') => 'select[name="type"]',
         __('Category') => 'input[name="itilcategories_id"]',

         __('Status') => 'select[name="status"]',
         __('Status').'-'.Ticket::getStatus(1) => 'select[name="status"] option[value="1"]',
         __('Request source') => 'select[name="requesttypes_id"]',
         __('Urgency') => 'select[name="urgency"]',
         __('Urgency').'-'.Ticket::getUrgencyName(1) => 'select[name="urgency"] option[value="1"]',
         __('Approval') => 'select[name="global_validation"]',
         __('Impact') => 'select[name="impact"]',
         __('Impact').'-'.Ticket::getImpactName(1) => 'select[name="impact"] option[value="1"]',
         __('Hardware type') => 'select[name="itemtype"]',
         __('Priority') => 'select[name="priority"]',
         __('Priority').'-'.Ticket::getPriorityName(1) => 'select[name="priority"] option[value="1"]',
         __('Location') => 'select[name="locations_id"]',

         __('Requester').'-'.__('Type') => 'select[name="_itil_requester[_type]"]',
         __('Requester').'-'.__('Type').'-'.__('User') => 'select[name="_itil_requester[_type]"] option[value="user"]',
         __('Watcher').'-'.__('Type') => 'select[name="_itil_observer[_type]"]',
         __('Assigned to').'-'.__('Type') => 'select[name="_itil_assign[_type]"]',
         __('Assigned to').'-'.__('Type').'-'.__('Supplier') => 'select[name="_itil_assign[_type]"] option[value="supplier"]',

         __('Title') => 'input[name="name"]',
         __('Description') => 'textarea[name="content"]',
         __('Linked ticket').'-'.__('link type', 'uihacks') => 'select[name="_link[link]"]',
         __('Linked ticket').'-'.__('Ticket id', 'uihacks') => 'input[name="_link[tickets_id_2]"]'
      );
   }


}