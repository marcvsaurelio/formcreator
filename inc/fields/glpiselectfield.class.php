<?php
/**
 * LICENSE
 *
 * Copyright © 2011-2018 Teclib'
 *
 * This file is part of Formcreator Plugin for GLPI.
 *
 * Formcreator is a plugin that allow creation of custom, easy to access forms
 * for users when they want to create one or more GLPI tickets.
 *
 * Formcreator Plugin for GLPI is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * Formcreator Plugin for GLPI is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 * If not, see http://www.gnu.org/licenses/.
 * ------------------------------------------------------------------------------
 * @author    Thierry Bugier
 * @author    Jérémy Moreau
 * @copyright Copyright © 2018 Teclib
 * @license   GPLv2 https://www.gnu.org/licenses/gpl2.txt
 * @link      https://github.com/pluginsGLPI/formcreator/
 * @link      http://plugins.glpi-project.org/#/plugin/formcreator
 * ------------------------------------------------------------------------------
 */
class PluginFormcreatorGlpiselectField extends PluginFormcreatorDropdownField
{
   public static function getName() {
      return _n('GLPI object', 'GLPI objects', 1, 'formcreator');
   }

   public function prepareQuestionInputForSave($input) {
      if (isset($input['glpi_objects'])) {
         if (empty($input['glpi_objects'])) {
            Session::addMessageAfterRedirect(
                  __('The field value is required:', 'formcreator') . ' ' . $input['name'],
                  false,
                  ERROR);
            return [];
         }
         $input['values']         = $input['glpi_objects'];
         $input['default_values'] = isset($input['dropdown_default_value']) ? $input['dropdown_default_value'] : '';
      }
      return $input;
   }

   public function isValid($value) {
      // If the field is required it can't be empty (0 is a valid value for entity)
      if ($this->isRequired() && empty($value) && ($value == '0' && $this->fields['values'] != Entity::class)) {
         Session::addMessageAfterRedirect(
               __('A required field is empty:', 'formcreator') . ' ' . $this->getLabel(),
               false,
               ERROR);
         return false;
      }

      // All is OK
      return true;
   }

   public static function getPrefs() {
      return [
         'required'       => 1,
         'default_values' => 0,
         'values'         => 0,
         'range'          => 0,
         'show_empty'     => 1,
         'regex'          => 0,
         'show_type'      => 1,
         'dropdown_value' => 0,
         'glpi_objects'   => 1,
         'ldap_values'    => 0,
      ];
   }

   public static function getJSFields() {
      $prefs = self::getPrefs();
      return "tab_fields_fields['glpiselect'] = 'showFields(" . implode(', ', $prefs) . ");';";
   }

   public function equals($value) {
      $value = html_entity_decode($value);
      $itemtype = $this->fields['values'];
      $item = new $itemtype();

      if (!$item->getFromDB($this->fields['answer'])) {
         return false;
      }

      return $item->getField($item->getNameField()) == $value;
   }

   public function greaterThan($value) {
      $value = html_entity_decode($value);
      $itemtype = $this->fields['values'];
      $item = new $itemtype();

      if (!$item->getFromDB($this->fields['answer'])) {
         return false;
      }

      return $item->getField($item->getNameField()) > $value;
   }
}
