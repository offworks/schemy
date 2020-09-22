<?php

namespace Schemy;

use Doctrine\DBAL\Types\Type;

class Table extends \Doctrine\DBAL\Schema\Table
{
    public function addColumn($columnName, $typeName, array $options = [])
    {
        $column = new Column($columnName, Type::getType($typeName), $options);

        $this->_addColumn($column);

        return $column;
    }

    public function integer($column)
    {
        return $this->addColumn($column, 'integer');
    }

    public function string($column, $length = null)
    {
        return $this->addColumn($column, 'string', $length ? ['length' => $length] : ['length' => 191]);
    }

    public function text($column)
    {
        return $this->addColumn($column, 'text');
    }

    public function boolean($column)
    {
        return $this->addColumn($column, 'smallint');
    }

    public function smallInteger($column)
    {
        return $this->addColumn($column, 'smallint');
    }

    public function bigInteger($column)
    {
        return $this->addColumn($column, 'bigint');
    }

    public function guid($column)
    {
        return $this->addColumn($column, 'guid');
    }

    public function increments($column)
    {
        $obj = $this->addColumn($column, 'integer')
            ->setAutoincrement(true);

        $this->setPrimaryKey([$column]);

        return $obj;
    }

    public function decimal($column, $total = 8, $places = 2)
    {
        return $this->addColumn($column, 'decimal', [
            'precision' => $total,
            'scale' => $places
        ]);
    }

    public function float($column, $total = 8, $places = 2)
    {
        return $this->addColumn($column, 'float', [
            'precision' => $total,
            'scale' => $places
        ]);
    }

    public function timestamp($column, $precision = 0)
    {
        return $this->addColumn($column, 'datetime', [
            'precision' => $precision
        ]);
    }

    /**
     * Create a created_at and updated_at timestamps
     */
    public function timestamps()
    {
        $this->addColumn('created_at', 'datetime');
        $this->addColumn('updated_at', 'datetime');
    }
}