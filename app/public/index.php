<?php
use Tympahealth\DeviceManagement\Device\DeviceRepository;

require dirname(__DIR__) . '/vendor/autoload.php';

use Slim\Factory\AppFactory;
use Tympahealth\DeviceManagement\Container;
use Tympahealth\DeviceManagement\Device\DeviceController;


$container = new Container();
$container->set(DeviceRepository::class, new DeviceRepository());

AppFactory::setContainer($container);

$app = AppFactory::create();

$app->get('/', [DeviceController::class, 'home']);
$app->run();