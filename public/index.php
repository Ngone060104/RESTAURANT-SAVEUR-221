<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Core\App;
use App\Core\Database;
use App\Core\Container;

session_start();

$container = new Container();

$container->singleton(PDO::class, fn () => Database::getInstance()->getConnection());
$app = new App($container);

(require __DIR__ . '/../routes/web.php')($app->router());

$app->run();
