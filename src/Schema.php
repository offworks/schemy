<?php

namespace Schemy;

class Schema extends \Doctrine\DBAL\Schema\Schema
{
    /**
     * Creates a new table.
     *
     * @param string $tableName
     *
     * @param \Closure|null $tableFactory
     * @return Table
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Schema\SchemaException
     */
    public function createTable($tableName, callable $tableFactory = null)
    {
        $table = $tableFactory ? $tableFactory() : new Table($tableName);
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