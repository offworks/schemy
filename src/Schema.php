<?php

namespace Schemy;

class Schema extends \Doctrine\DBAL\Schema\Schema
{
    /**
     * Creates a new table.
     *
     * @param string $tableName
     *
     * @return Table
     */
    public function createTable($tableName)
    {
        $table = new Table($tableName);
        $this->_addTable($table);

        foreach ($this->_schemaConfig->getDefaultTableOptions() as $name => $value) {
            $table->addOption($name, $value);
        }

        return $table;
    }

    /**
     * Alias to createTable
     * @param $tableName
     * @return Table
     */
    public function table($tableName, \Closure $callback = null)
    {
        $table = $this->createTable($tableName);

        if ($callback)
            $callback($table);

        return $table;
    }
}