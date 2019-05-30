<?php

use App\Container;

$container = new Container();

$container->set('config', require __DIR__ . '/params.php');

return $container;