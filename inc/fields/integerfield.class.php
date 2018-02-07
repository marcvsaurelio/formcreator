<?php
class PluginFormcreatorIntegerField extends PluginFormcreatorField
{
   public function isValid($value) {
      if (!parent::isValid($value)) {
         return false;
      }

      if (!$this->isValidValue($value)) {
         return false;
      }

      return true;
   }

   private function isValidValue($value) {
      if (!empty($value) && !ctype_digit($value)) {
         Session::addMessageAfterRedirect(__('This is not an integer:', 'formcreator') . ' ' . $this->fields['name'], false, ERROR);
         return false;
      }

      $parameters = $this->getUsedParameters();
      foreach ($parameters as $fieldname => $parameter) {
         $parameter->getFromDBByCrit([
            'plugin_formcreator_questions_id'   => $this->fields['id'],
            'fieldname'                         => $fieldname,
         ]);
      }

      // Check the field matches the format regex
      if (!$parameters['regex']->isNewItem()) {
         $regex = $parameters['regex']->getField('regex');
         if ($regex !== null && strlen($regex) > 0) {
            if (!preg_match($regex, $value)) {
               Session::addMessageAfterRedirect(__('Specific format does not match:', 'formcreator') . ' ' . $this->fields['name'], false, ERROR);
               return false;
            }
         }
      }

      // Check the field is in the range
      if (!$parameters['range']->isNewItem()) {
         $rangeMin = $parameters['range']->getField('range_min');
         $rangeMax = $parameters['range']->getField('range_max');
         if (strlen($rangeMin) > 0 && $value < $rangeMin) {
            $message = sprintf(__('The following number must be greater than %d:', 'formcreator'), $rangeMin);
            Session::addMessageAfterRedirect($message . ' ' . $this->fields['name'], false, ERROR);
            return false;
         }

         if (strlen($rangeMax) > 0 && $value > $rangeMax) {
            $message = sprintf(__('The following number must be lower than %d:', 'formcreator'), $rangeMax);
            Session::addMessageAfterRedirect($message . ' ' . $this->fields['name'], false, ERROR);
            return false;
         }
      }

      return true;
   }

   public static function getName() {
      return __('Integer', 'formcreator');
   }

   public function prepareQuestionInputForSave($input) {
      $success = true;
      $fieldType = $this->getFieldTypeName();
      // Add leading and trailing regex marker automaticaly
      if (isset($input['_parameters'][$fieldType]['regex']['regex']) && !empty($input['_parameters'][$fieldType]['regex']['regex'])) {
         // Avoid php notice when validating the regular expression
         set_error_handler(function($errno, $errstr, $errfile, $errline, $errcontext) {});
         $isValid = !(preg_match($input['_parameters'][$fieldType]['regex']['regex'], null) === false);
         restore_error_handler();

         if (!$isValid) {
            Session::addMessageAfterRedirect(__('The regular expression is invalid', 'formcreator'), false, ERROR);
            $success = false;
         }
      }
      if (!$success) {
         return false;
      }

      if (isset($input['range_min'])
          && isset($input['range_max'])
          && isset($input['default_values'])) {
         $input['default_values'] = !empty($input['default_values'])
                                  ? (float) str_replace(',', '.', $input['default_values'])
                                  : null;
         $input['range_min']      = !empty($input['range_min'])
                                  ? (float) str_replace(',', '.', $input['range_min'])
                                  : null;
         $input['range_max']      = !empty($input['range_max'])
                                  ? (float) str_replace(',', '.', $input['range_max'])
                                  : null;
      }
      return $input;
   }

   public static function getPrefs() {
      return [
         'required'       => 1,
         'default_values' => 1,
         'values'         => 0,
         'range'          => 1,
         'show_empty'     => 0,
         'regex'          => 1,
         'show_type'      => 1,
         'dropdown_value' => 0,
         'glpi_objects'   => 0,
         'ldap_values'    => 0,
      ];
   }

   public static function getJSFields() {
      $prefs = self::getPrefs();
      return "tab_fields_fields['integer'] = 'showFields(" . implode(', ', $prefs) . ");';";
   }

   public function getUsedParameters() {
      $regexDoc = '<small>';
      $regexDoc.= '<a href="http://php.net/manual/reference.pcre.pattern.syntax.php" target="_blank">';
      $regexDoc.= '('.__('Regular expression', 'formcreator').')';
      $regexDoc.= '</small>';
      return [
         'regex' => new PluginFormcreatorQuestionRegex(
            $this,
            [
               'fieldName' => 'regex',
               'label'     => __('Additional validation', 'formcreator') . $regexDoc,
               'fieldType' => ['text'],
            ]
         ),
         'range' => new PluginFormcreatorQuestionRange(
            $this,
            [
               'fieldName' => 'range',
               'label'     => __('Range', 'formcreator'),
               'fieldType' => ['text'],
            ]
         ),
      ];
   }

}
