<?php
namespace Schemy;

use Schemy\Exception\Exception;

class Model
{
    /**
     * @var string $name
     */
    protected $name;

    /**
     * @var Collection|Field[]
     */
    protected $fields;

    /**
     * @var string $table
     */
    protected $table;

    /**
     * @var boolean $timestamps
     */
    protected $timestamps = false;

    /**
     * @var Primary|null $primary
     */
    protected $primary;

    /**
     * @var bool $softDeletes
     */
    protected $softDeletes = false;

    public function __construct($name, $table, Collection $fields, Primary $primary = null)
    {
        $this->name = $name;

        $this->table = $table;

        $this->fields = $fields;

        $this->primary = $primary;
    }

    /**
     * @param $name
     * @param array $model
     * @return static
     * @throws Exception
     */
    public static function createFromArray($name, array $model)
    {
        if(!isset($model['table']))
            throw new Exception('Missing [table].');

        if(!isset($model['fields']))
            throw new Exception('Missing [fields]');

        if(!is_array($model['fields']))
            throw new Exception('[fields] must be array');

        $fields = new Collection;

        $primary = null;

        if(isset($model['primary']) && $model['primary'] != false) {

            $primary = Primary::create($model['primary']);

            $fields->append($primary);
        }

        foreach($model['fields'] as $name => $field)
            $fields->append(Field::create($name, $field));

        $modelObj = new static($name, $model['table'], $fields, $primary);

        if(isset($model['timestamps']))
            $modelObj->timestamps($model['timestamps']);

        if(isset($model['soft_deletes']))
            $modelObj->softDeletes($model['soft_deletes']);

        return $modelObj;
    }

    /**
     * @param boolean $timestamps
     * @return $this
     */
    public function timestamps($timestamps)
    {
        $this->timestamps = $timestamps;

        return $this;
    }

    /**
     * @param boolean $timestamps
     * @return $this
     */
    public function softDeletes($flag)
    {
        $this->softDeletes = $flag;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getTableName()
    {
        return $this->table;
    }

    /**
     * @return Primary|null
     */
    public function getPrimary()
    {
        return $this->primary;
    }

    /**
     * @return Collection|Field[]
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param Field $field
     * @return $this
     */
    public function addField(Field $field)
    {
        $this->fields->append($field);

        return $this;
    }
}