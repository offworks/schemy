<?php
namespace Laraquent\Schematic;

use Schemy\Exception\Exception;

class Primary extends Field
{
    public function __construct($field, $type = 'integer', $length = 11, $autoincrement = true)
    {
        parent::__construct($field, array(
            'type' => $type,
            'length' => $length,
            'autoincrement' => $autoincrement
        ));
    }

    public static function create($data)
    {
        if(is_string($data))
            return static::createFromString($data);

        if(is_array($data))
            return static::createFromArray($data);

        throw new Exception('Primary format must be either [string] or [array]');
    }

    public static function createFromString($data)
    {
        if($data === false)
            return null;

        @list($field, $type) = explode(',', $data);

        if(!$type)
            $type = 'integer(11)';

        @list($typeLength) = explode(' ', $type);

        @list($type, $length) = explode('(', $typeLength);

        if($length)
            $length = trim($length, ')');

        return new static($field, $type, $length);
    }

    public static function createFromArray(array $attributes)
    {
        if(!isset($attributes['type']))
        {
            $attributes['type'] = 'integer';
            $attributes['length'] = 11;
        }

        if(!isset($attributes['autoincrement']))
            $attributes['autoincrement'] = true;

        return new static($attributes['field'], $attributes['type'], $attributes['length'], $attributes['autoincrement']);
    }
}