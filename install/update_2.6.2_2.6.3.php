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

function plugin_formcreator_update_2_6_3() {
   global $DB;

   // Change id of search option for status of form_answer
   $table = 'glpi_displaypreferences';
   $query = "UPDATE `$table` SET `num`='8' WHERE `itemtype`='PluginFormcreatorForm_Answer' AND `num`='1'";
   $DB->query($query);

   // Remove abusive encding in sections
   $table = 'glpi_plugin_formcreator_sections';
   $request = [
      'FROM' => $table,
   ];
   foreach ($DB->request($request) as $row) {
      $name = Toolbox::addslashes_deep(html_entity_decode($row['name'], ENT_QUOTES|ENT_HTML5));
      $id = $row['id'];
      $DB->query("UPDATE `$table` SET `name`='$name' WHERE `id` = '$id'");
   }

   // Remove abusive encoding in targets
   $table = 'glpi_plugin_formcreator_targets';
   $request = [
      'FROM' => $table,
   ];
   foreach ($DB->request($request) as $row) {
      $name = Toolbox::addslashes_deep(html_entity_decode($row['name'], ENT_QUOTES|ENT_HTML5));
      $id = $row['id'];
      $DB->query("UPDATE `$table` SET `name`='$name' WHERE `id` = '$id'");
   }

   // Remove abusive encding in target tickets
   $table = 'glpi_plugin_formcreator_targettickets';
   $request = [
      'FROM' => $table,
   ];
   foreach ($DB->request($request) as $row) {
      $name = Toolbox::addslashes_deep(html_entity_decode($row['name'], ENT_QUOTES|ENT_HTML5));
      $id = $row['id'];
      $DB->query("UPDATE `$table` SET `name`='$name' WHERE `id` = '$id'");
   }

   // Remove abusive encding in target changes
   $table = 'glpi_plugin_formcreator_targetchanges';
   $request = [
      'FROM' => $table,
   ];
   foreach ($DB->request($request) as $row) {
      $name = Toolbox::addslashes_deep(html_entity_decode($row['name'], ENT_QUOTES|ENT_HTML5));
      $id = $row['id'];
      $DB->query("UPDATE `$table` SET `name`='$name' WHERE `id` = '$id'");
   }
}