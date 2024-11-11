<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use DI\Container;
use Slim\Factory\AppFactory;
use Marcelofj\LibraryApp\Infra\Framework\Http\ContainerConfig;
use Marcelofj\LibraryApp\Infra\Framework\Http\Router;

$container = new Container();

ContainerConfig::configure($container);

AppFactory::setContainer($container);
$app = AppFactory::create();

$router = new Router($app);
$router->setupRoutes();

$app->run();
