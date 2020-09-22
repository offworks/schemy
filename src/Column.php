<?php

namespace Schemy;

class Column extends \Doctrine\DBAL\Schema\Column
{
    public function nullable($bool = true)
    {
        return $this->setNotnull(!$bool);
    }

    public function autoIncrement($bool = true)
    {
        return $this->setAutoincrement($bool);
    }

    public function comment($comment)
    {
        return $this->setComment($comment);
    }
}