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
 * @link     https://github.com/pluginsGLPI/formcreator/
 * @link     https://pluginsglpi.github.io/formcreator/
 * @link     http://plugins.glpi-project.org/#/plugin/formcreator
 * ---------------------------------------------------------------------
 */

include('../../../inc/includes.php');

// Check if plugin is activated...
if (!(new Plugin())->isActivated('formcreator')) {
    http_response_code(404);
    exit();
}

if (!isset($_POST['action'])) {
    http_response_code(400);
    die();
}

switch ($_POST['action']) {
    case 'add':
        $id = (int) $_POST['id'];
        $actor_value = $_POST['actor_value_' . $_POST['actor_type']] ?? 0;
        $target_actor = new PluginFormcreatorTarget_Actor();
        if ($target_actor->add($_POST) === false) {
            http_response_code(500);
            Session::addMessageAfterRedirect(__('Failed to add the actor', 'formcreator'), false, ERROR, true);
        }
        break;

    case 'delete':
        $target_actor = new PluginFormcreatorTarget_Actor();
        $success = $target_actor->delete([
            'id' => (int) $_POST['id']
        ]);
        if (!$success) {
            http_response_code(400);
            Session::addMessageAfterRedirect(__('Failed to delete the actor', 'formcreator'), false, ERROR, true);
        }
        break;
}
