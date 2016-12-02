<?php
namespace Schemy\Schematic;

use Schemy\Exception\Exception;
use Symfony\Component\Yaml\Yaml;

class Schema extends Collection
{
    /** @var array|Table[] */
    protected $tables = array();

    public function __construct(array $schema)
    {
        parent::__construct();

        $this->parse($schema);
    }

    /**
     * Create schema from given yaml
     * Require Symfony\Component\Yaml\Yaml
     *
     * @param string $contents
     * @return static|Model[]
     */
    public static function createFromYaml($contents)
    {
        return new static(Yaml::parse($contents));
    }

    /**
     * @param string $contents
     * @return static
     */
    public static function createFromJson($contents)
    {
        return new static(json_decode($contents, true));
    }

    /**
     * @param array $schema
     * @throws Exception
     */
    protected function parse(array $schema)
    {
        try {
            foreach($schema as $name => $model)
                $this->addModel(Model::createFromArray($name, $model));
        } catch(\Exception $e) {
            throw new Exception('[' . $name . '] ' . $e->getMessage());
        }
    }

    public function addModel(Model $model)
    {
        $this->append($model);
    }
}