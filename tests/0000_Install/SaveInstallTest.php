<?php
/**
 LICENSE

 This file is part of the storkmdm plugin.

 Order plugin is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Order plugin is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI; along with storkmdm. If not, see <http://www.gnu.org/licenses/>.
 --------------------------------------------------------------------------
 @package   storkmdm
 @author    the storkmdm plugin team
 @copyright Copyright (c) 2015 storkmdm plugin team
 @license   GPLv2+ http://www.gnu.org/licenses/gpl.txt
 @link      https://github.com/teclib/storkmdm
 @link      http://www.glpi-project.org/
 @since     0.1.33
 ----------------------------------------------------------------------
*/

class SaveInstallTest extends CommonDBTestCase
{

   public function should_restore_install() {
      return FALSE;
   }

   public function testSaveInstallation() {
      global $DB;
      $DB = new DB();

      $this->mysql_dump($DB->dbuser, $DB->dbhost, $DB->dbpassword, $DB->dbdefault, './save.sql');

      $this->assertFileExists("./save.sql");
      $filestats = stat("./save.sql");
      $length = $filestats[7];
      $this->assertGreaterThan(0, $length);
   }

}