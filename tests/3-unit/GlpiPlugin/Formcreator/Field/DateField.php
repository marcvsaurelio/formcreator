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
namespace GlpiPlugin\Formcreator\Field\tests\units;
use GlpiPlugin\Formcreator\Tests\CommonTestCase;
use PluginFormcreatorFormAnswer;

class DateField extends CommonTestCase {

   public function providerGetValue() {
      $dataset = [
         [
            'question'           => $this->getQuestion([
               'fieldtype'       => 'date',
               'name'            => 'question',
               'required'        => '0',
               'default_values'  => '',
               'values'          => "",
               'order'           => '1',
               'show_rule'       => \PluginFormcreatorCondition::SHOW_RULE_ALWAYS,
               '_parameters'     => [],
            ]),
            'expectedValue'   => null,
            'expectedValidity' => true
         ],
         [
            'question'           => $this->getQuestion([
               'fieldtype'       => 'date',
               'name'            => 'question',
               'required'        => '0',
               'default_values'  => '2018-08-16',
               'values'          => "",
               'order'           => '1',
               'show_rule'       => \PluginFormcreatorCondition::SHOW_RULE_ALWAYS,
               '_parameters'     => [],
            ]),
            'expectedValue'   => '2018-08-16',
            'expectedValidity' => true
         ],
         [
            'question'           => $this->getQuestion([
               'fieldtype'       => 'date',
               'name'            => 'question',
               'required'        => '1',
               'default_values'  => '',
               'values'          => "",
               'order'           => '1',
               'show_rule'       => \PluginFormcreatorCondition::SHOW_RULE_ALWAYS,
               '_parameters'     => [],
            ]),
            'expectedValue'   => null,
            'expectedValidity' => false
         ],
         [
            'question'           => $this->getQuestion([
               'fieldtype'       => 'date',
               'name'            => 'question',
               'required'        => '1',
               'default_values'  => '2018-08-16',
               'values'          => "",
               'order'           => '1',
               'show_rule'       => \PluginFormcreatorCondition::SHOW_RULE_ALWAYS,
               '_parameters'     => [],
            ]),
            'expectedValue'   => '2018-08-16',
            'expectedValidity' => true
         ],
      ];

      return $dataset;
   }

   public function providerIsValid() {
      return $this->providerGetValue();
   }

   /**
    * @dataProvider providerIsValid
    */
   public function testIsValid($question, $expectedValue, $expectedValidity) {
      $instance = $this->newTestedInstance($question);
      $instance->deserializeValue($question->fields['default_values']);

      $isValid = $instance->isValid();
      $this->boolean((boolean) $isValid)->isEqualTo($expectedValidity);
   }

   public function testGetName() {
      $itemtype = $this->getTestedClassName();
      $output = $itemtype::getName();
      $this->string($output)->isEqualTo('Date');
   }

   public function testisPublicFormCompatible() {
      $instance = $this->newTestedInstance($this->getQuestion());
      $output = $instance->isPublicFormCompatible();
      $this->boolean($output)->isTrue();
   }

   public function testIsPrerequisites() {
      $instance = $this->newTestedInstance($this->getQuestion());
      $output = $instance->isPrerequisites();
      $this->boolean($output)->isEqualTo(true);
   }

   public function testSerializeValue() {
      $value = $expected = '2019-01-01';
      $question = $this->getQuestion([
         'fieldtype' => 'Date',
      ]);
      $instance = $this->newTestedInstance($question);
      $instance->parseAnswerValues(['formcreator_field_' . $question->getID() => $value]);
      $form = $this->getForm();
      $formAnswer = new PluginFormcreatorFormAnswer();
      $formAnswer->add([
         $form::getForeignKeyField() => $form->getID(),
      ]);
      $output = $instance->serializeValue($formAnswer);
      $this->string($output)->isEqualTo($expected);
   }

   public function testGetValueForDesign() {
      $value = $expected = '2019-01-01';
      $instance = $this->newTestedInstance($this->getQuestion());
      $instance->deserializeValue($value);
      $output = $instance->getValueForDesign();
      $this->string($output)->isEqualTo($expected);
   }

   public function providerEquals() {
      return [
         [
            'value'     => '0000-00-00',
            'answer'    => '',
            'expected'  => true,
         ],
         [
            'value'     => '2019-01-01',
            'answer'    => '',
            'expected'  => false,
         ],
         [
            'value'     => '2019-01-01',
            'answer'    => '2018-01-01',
            'expected'  => false,
         ],
         [
            'value'     => '2019-01-01',
            'answer'    => '2019-01-01',
            'expected'  => true,
         ],
         [
            'value'     => '',
            'answer'    => '2019-01-01',
            'expected'  => false,
         ],
      ];
   }

   /**
    * @dataProvider providerEquals
    */
   public function testEquals($value, $answer, $expected) {
      $question = $this->getQuestion();
      $instance = $this->newTestedInstance($question);
      $instance->parseAnswerValues(['formcreator_field_' . $question->getID() => $answer]);
      $this->boolean($instance->equals($value))->isEqualTo($expected);
   }

   public function providerNotEquals() {
      return [
         [
            'value'     => '2019-01-01',
            'answer'    => '',
            'expected'  => true,
         ],
         [
            'value'     => '2019-01-01',
            'answer'    => '2018-01-01',
            'expected'  => true,
         ],
         [
            'value'     => '2019-01-01',
            'answer'    => '2019-01-01',
            'expected'  => false,
         ],
         [
            'value'     => '',
            'answer'    => '2019-01-01',
            'expected'  => true,
         ],
      ];
   }

   /**
    * @dataProvider providerNotEquals
    */
   public function testNotEquals($value, $answer, $expected) {
      $question = $this->getQuestion();
      $instance = $this->newTestedInstance($question, $answer);
      $instance->parseAnswerValues(['formcreator_field_' . $question->getID() => $answer]);
      $this->boolean($instance->notEquals($value))->isEqualTo($expected);
   }

   public function testGetDocumentsForTarget() {
      $instance = $this->newTestedInstance($this->getQuestion());
      $this->array($instance->getDocumentsForTarget())->hasSize(0);
   }

   public function testCanRequire() {
      $instance = $this->newTestedInstance($this->getQuestion());
      $output = $instance->canRequire();
      $this->boolean($output)->isTrue();
   }

   public function providerGetValueForApi() {
      return [
         [
            'input'    => '2021-05-11',
            'expected' => '2021-05-11'
         ]
      ];
   }

   /**
    * @dataProvider providerGetValueForApi
    *
    * @return void
    */
   public function testGetValueForApi($input, $expected) {
      $question = $this->getQuestion();

      $instance = $this->newTestedInstance($question);
      $instance->deserializeValue($input);
      $output = $instance->getValueForApi();
      $this->string($output)->isEqualTo($expected);
   }
}
