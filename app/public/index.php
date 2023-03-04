<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use Slim\Factory\AppFactory;
use Tympahealth\DeviceManagement\Container;
use Tympahealth\DeviceManagement\Device\DeviceController;


$container = new Container();
$container->set('HelloWorld', 'Hello World Everyoneeee');

AppFactory::setContainer($container);

$app = AppFactory::create();

$app->get('/', [DeviceController::class, 'home']);
$app->run();