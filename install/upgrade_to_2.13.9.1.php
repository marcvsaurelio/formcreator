<?php

class PluginFormcreatorUpgradeTo2_13_9_1
{

    protected $migration;

    /**
     * @param Migration $migration
     */
    public function upgrade(Migration $migration)
    {
        global $DB;
        $this->migration = $migration;

        if ($DB->tableExists(PluginFormcreatorQuestion::getTable())) 
        {
            $query = 'SELECT 
                    COLUMN_NAME AS "ColumnName", 
                    TABLE_NAME AS "TableName" 
                FROM INFORMATION_SCHEMA.COLUMNS 
                WHERE TABLE_NAME = "' . PluginFormcreatorQuestion::getTable() . '" 
                    AND COLUMN_NAME = "annotation" 
            ';

            $result = $DB->request($query);

            if(count($result) == 0)
            {
                $migration->addField(
                    PluginFormcreatorQuestion::getTable(),
                    'annotation',
                    'string',
                    [
                        'value'   => null,
                        'after'   => 'uuid',
                        'comment' => 'Annotation to question, never display in in Wizard'
                    ]
                );
            }
        }

        if ($DB->tableExists(PluginFormcreatorCategory::getTable())) 
        {
            $query = 'SELECT 
                    COLUMN_NAME AS "ColumnName", 
                    TABLE_NAME AS "TableName" 
                FROM INFORMATION_SCHEMA.COLUMNS 
                WHERE TABLE_NAME = "' . PluginFormcreatorCategory::getTable() . '" 
                    AND COLUMN_NAME = "url_image" 
            ';

            $result = $DB->request($query);

            if(count($result) == 0)
            {
                $migration->addField(
                    PluginFormcreatorCategory::getTable(),
                    'url_image',
                    'mediumtext',
                    [
                        'value'   => null,
                        'after'   => 'knowbaseitemcategories_id',
                        'comment' => ''
                    ]
                );
            }

            $query = 'SELECT 
                    COLUMN_NAME AS "ColumnName", 
                    TABLE_NAME AS "TableName" 
                FROM INFORMATION_SCHEMA.COLUMNS 
                WHERE TABLE_NAME = "' . PluginFormcreatorCategory::getTable() . '" 
                    AND COLUMN_NAME = "is_display" 
            ';

            $result = $DB->request($query);

            if(count($result) == 0)
            {
                $migration->addField(
                    PluginFormcreatorCategory::getTable(),
                    'is_display',
                    'bool',
                    [
                        'value'   => null,
                        'after'   => 'knowbaseitemcategories_id',
                        'comment' => ''
                    ]
                );
            }

            $query = 'SELECT 
                    COLUMN_NAME AS "ColumnName", 
                    TABLE_NAME AS "TableName" 
                FROM INFORMATION_SCHEMA.COLUMNS 
                WHERE TABLE_NAME = "' . PluginFormcreatorCategory::getTable() . '" 
                    AND COLUMN_NAME = "icon_color" 
            ';

            $result = $DB->request($query);

        }


        if ($DB->tableExists(PluginFormcreatorForm::getTable())) 
        {
            $query = 'SELECT 
                    COLUMN_NAME AS "ColumnName", 
                    TABLE_NAME AS "TableName" 
                FROM INFORMATION_SCHEMA.COLUMNS 
                WHERE TABLE_NAME = "' . PluginFormcreatorForm::getTable() . '" 
                    AND COLUMN_NAME = "url_image" 
            ';

            $result = $DB->request($query);

            if(count($result) == 0)
            {
                $migration->addField(
                    PluginFormcreatorForm::getTable(),
                    'url_image',
                    'mediumtext',
                    [
                        'value'   => null,
                        'after'   => 'uuid',
                        'comment' => ''
                    ]
                );
            }

            $query = 'SELECT 
                    COLUMN_NAME AS "ColumnName", 
                    TABLE_NAME AS "TableName" 
                FROM INFORMATION_SCHEMA.COLUMNS 
                WHERE TABLE_NAME = "' . PluginFormcreatorForm::getTable() . '" 
                    AND COLUMN_NAME = "is_display" 
            ';

            $result = $DB->request($query);

            if(count($result) == 0)
            {
                $migration->addField(
                    PluginFormcreatorForm::getTable(),
                    'is_display',
                    'bool',
                    [
                        'value'   => null,
                        'after'   => 'uuid',
                        'comment' => ''
                    ]
                );
            }

            $query = 'SELECT 
                    COLUMN_NAME AS "ColumnName", 
                    TABLE_NAME AS "TableName" 
                FROM INFORMATION_SCHEMA.COLUMNS 
                WHERE TABLE_NAME = "' . PluginFormcreatorForm::getTable() . '" 
                    AND COLUMN_NAME = "annotation" 
            ';

            $result = $DB->request($query);

            if(count($result) == 0)
            {
                $migration->addField(
                    PluginFormcreatorForm::getTable(),
                    'annotation',
                    'bool',
                    [
                        'value'   => null,
                        'after'   => 'uuid',
                        'comment' => ''
                    ]
                );
            }
        }


        if ($DB->tableExists(PluginFormcreatorIssue::getTable())) 
        {
            // UP order display issue
            $query = "UPDATE `glpi_displaypreferences` SET `rank` = (`rank`+1) WHERE itemtype = '" . PluginFormcreatorIssue::class . "';";
            $DB->query($query);

            $query = "INSERT INTO `glpi_displaypreferences` (
                `itemtype`,
                `num`,
                `rank`,
                `users_id`
            ) VALUES (
                '" . PluginFormcreatorIssue::class . "',
                '254',
                '1',
                '0'
            );";
            $DB->query($query);
        }
    }
}