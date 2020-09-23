<?php

use Schemy\Schema;
use Schemy\Table;

require_once __DIR__ . '/../vendor/autoload.php';

$schema = new Schema();

$schema->table('user', function(Table $table) {
    $table->increments('id');
    $table->integer('facebook_id');
    $table->string('name')->nullable();
    $table->timestamps();
});

$schema->table('profile', function (Table $table) {
    $table->increments('id');
    $table->timestamps();
});

return $schema;