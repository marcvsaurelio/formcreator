<?php
/**
 * ---------------------------------------------------------------------
 * Formcreator is a plugin which allows creation of custom forms of
 * easy access.
 * ---------------------------------------------------------------------
 * LICENSE
 *
 * This file is part of Formcreator.
 *
 * Formcreator is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * Formcreator is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Formcreator. If not, see <http://www.gnu.org/licenses/>.
 * ---------------------------------------------------------------------
 * @copyright Copyright © 2011 - 2021 Teclib'
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @link      https://github.com/pluginsGLPI/formcreator/
 * @link      https://pluginsglpi.github.io/formcreator/
 * @link      http://plugins.glpi-project.org/#/plugin/formcreator
 * ---------------------------------------------------------------------
 */

include ('../../../inc/includes.php');

// Check if plugin is activated...
if (!(new Plugin())->isActivated('formcreator')) {
   http_response_code(404);
   exit();
}

$formFk = PluginFormcreatorForm::getForeignKeyField();
if (!isset($_POST[$formFk])) {
   http_response_code(403);
   exit();
}

$form = PluginFormcreatorCommon::getForm();
$form->getFromDB((int) $_POST['plugin_formcreator_forms_id']);
if (!Session::haveRight(PluginFormcreatorForm::$rightname, UPDATE) && ($form->isDeleted() || $form->fields['is_active'] == '0')) {
   http_response_code(403);
   exit();
}

if (!$form->canViewForRequest()) {
   http_response_code(403);
   exit();
}

try {
    $visibility = PluginFormcreatorFields::updateVisibility($_POST);
} catch (\Exception $e) {
    http_response_code(500);
    exit();
}
echo json_encode($visibility);
exit();
