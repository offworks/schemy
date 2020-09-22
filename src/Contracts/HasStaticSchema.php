<?php

namespace Schemy\Contracts;

use Schemy\Table;

interface HasStaticSchema
{
    public static function getDbTableName();

    public static function setUpSchema(Table $table);
}