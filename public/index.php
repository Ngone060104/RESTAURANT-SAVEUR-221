<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Core\App;
use App\Core\Container;

session_start();

$container = new Container();
$app = new App($container);

(require __DIR__ . '/../routes/web.php')($app->router());

$app->run();
