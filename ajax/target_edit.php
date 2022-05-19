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

include('../../../inc/includes.php');
Session::checkRight(PluginFormcreatorForm::$rightname, UPDATE);

if (!isset($_REQUEST['id'])) {
    http_response_code(400);
    exit;
}
$itemId = $_REQUEST['id'];

if (!isset($_REQUEST['itemtype'])) {
    http_response_code(400);
    exit;
}
$itemtype = $_REQUEST['itemtype'];
if (!in_array($itemtype, PluginFormcreatorForm::getTargetTypes())) {
    http_response_code(400);
    exit;
}

$target = new $itemtype();
if (!$target->getFromDB($itemId)) {
    http_response_code(404);
    exit;
}
$target->showForm($itemId);
