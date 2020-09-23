<?php

use Doctrine\DBAL\DriverManager;
use Doctrine\Migrations\Configuration\Connection\ExistingConnection;
use Doctrine\Migrations\Configuration\Migration\JsonFile;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Provider\SchemaProvider;
use Doctrine\Migrations\Provider\StubSchemaProvider;
use Doctrine\Migrations\Tools\Console\Command\DumpSchemaCommand;
use Doctrine\Migrations\Tools\Console\Command\ExecuteCommand;
use Doctrine\Migrations\Tools\Console\Command\GenerateCommand;
use Doctrine\Migrations\Tools\Console\Command\LatestCommand;
use Doctrine\Migrations\Tools\Console\Command\MigrateCommand;
use Doctrine\Migrations\Tools\Console\Command\RollupCommand;
use Doctrine\Migrations\Tools\Console\Command\StatusCommand;
use Doctrine\Migrations\Tools\Console\Command\SyncMetadataCommand;
use Doctrine\Migrations\Tools\Console\Command\VersionCommand;
use Schemy\Commands\DiffCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\ListCommand;

require_once __DIR__ . '/../vendor/autoload.php';

$config = new JsonFile('migrations.json');

$connection = DriverManager::getConnection(require_once __DIR__ . '/migrations-db.php');

$dependencyFactory = DependencyFactory::fromConnection($config, new ExistingConnection($connection));

//$dependencyFactory->setService(SchemaProvider::class, new StubSchemaProvider(require_once __DIR__ . '/schema.php'));
$dependencyFactory->setService(SchemaProvider::class, \Schemy\SchemaProvider::fromDirectory(__DIR__ . '/../src/Models'));

$cli = new Application('Doctrine Migrations');

$cli->setCatchExceptions(true);

$cli->addCommands(array(
    new DumpSchemaCommand($dependencyFactory),
    new ExecuteCommand($dependencyFactory),
    new GenerateCommand($dependencyFactory),
    new LatestCommand($dependencyFactory),
    new ListCommand('list'),
    new MigrateCommand($dependencyFactory),
    new RollupCommand($dependencyFactory),
    new StatusCommand($dependencyFactory),
    new SyncMetadataCommand($dependencyFactory),
    new VersionCommand($dependencyFactory),
    new DiffCommand($dependencyFactory)
));

$cli->run();