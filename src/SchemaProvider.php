<?php

namespace Schemy;

use DirectoryIterator;
use Doctrine\DBAL\Schema\Schema;
use Schemy\Contracts\HasStaticSchema;

class SchemaProvider implements \Doctrine\Migrations\Provider\SchemaProvider
{
    /**
     * @var Schema
     */
    private $schema;

    public function __construct(Schema $schema)
    {
        $this->schema = $schema;
    }

    /**
     * @param HasStaticSchema[] $classes
     */
    public static function fromStaticSchemas(array $classes)
    {
        $schema = new \Schemy\Schema();

        foreach ($classes as $provider)
            $provider::setUpSchema($schema->table($provider::getDbTableName()));

        return new static($schema);
    }

    /**
     * Directory scan
     * @param $dir
     */
    public static function fromDirectory($dir)
    {
        $classes = [];

        foreach (new DirectoryIterator($dir) as $fileInfo) {
            if($fileInfo->isDot())
                continue;

            /** @var HasStaticSchema $class */
            $class = static::getClassName($fileInfo->getRealPath());

            $refClass = new \ReflectionClass($class);

            if (!$refClass->isSubclassOf(HasStaticSchema::class) || $refClass->isAbstract())
                continue;

            $classes[] = $class;
        }

        return static::fromStaticSchemas($classes);
    }

    protected static function getClassName($file)
    {
        $fp = fopen($file, 'r');
        $class = $namespace = $buffer = '';
        $i = 0;
        while (!$class) {
            if (feof($fp)) break;

            $buffer .= fread($fp, 512);
            $tokens = token_get_all($buffer);

            if (strpos($buffer, '{') === false) continue;

            for (;$i<count($tokens);$i++) {
                if ($tokens[$i][0] === T_NAMESPACE) {
                    for ($j=$i+1;$j<count($tokens); $j++) {
                        if ($tokens[$j][0] === T_STRING) {
                            $namespace .= '\\'.$tokens[$j][1];
                        } else if ($tokens[$j] === '{' || $tokens[$j] === ';') {
                            break;
                        }
                    }
                }

                if ($tokens[$i][0] === T_CLASS) {
                    for ($j=$i+1;$j<count($tokens);$j++) {
                        if ($tokens[$j] === '{') {
                            $class = $tokens[$i+2][1];
                        }
                    }
                }
            }
        }

        return $namespace . '\\' . $class;
    }

    public function createSchema(): Schema
    {
        return $this->schema;
    }
}