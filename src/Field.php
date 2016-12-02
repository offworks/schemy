<?php
namespace Schemy;

use Schemy\Exception\Exception;

class Field
{
    /**
     * @var array $attributes
     */
    protected $attributes;

    public function __construct($name, array $attributes)
    {
        if(!isset($attributes['type']))
            throw new Exception('Attribute [type] is required');

        $attributes['name'] = $name;

        $this->attributes = $attributes;
    }

    public function getType()
    {
        return $this->get('type');
    }

    public function getName()
    {
        return $this->get('name');
    }

    public function getLength()
    {
        return $this->get('length');
    }

    public function get($key)
    {
        return $this->attributes[$key];
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public static function create($name, $data)
    {
        if(is_array($data))
            return static::createFromArray($name, $data);

        if(is_string($data))
            return static::createFromString($name, $data);

        throw new Exception('Field format must be either string or array');
    }

    public static function createFromArray($name, array $attributes)
    {
        return new static($name, $attributes);
    }

    public static function createFromString($name, $data)
    {
        @list($typeLength) = explode(' ', $data);

        @list($type, $length) = explode('(', $typeLength);

        if($length)
            $length = trim($length, ')');

        return new static($name, array(
            'type' => $type,
            'length' => $length
        ));
    }
}