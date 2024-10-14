<?php

class PluginFormcreatorUpgradeTo2_13_4_1
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
    }
}