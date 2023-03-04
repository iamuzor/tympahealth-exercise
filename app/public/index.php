<?php
use Slim\Routing\RouteCollectorProxy;

require dirname(__DIR__) . '/vendor/autoload.php';

use Slim\Factory\AppFactory;
use Tympahealth\DeviceManagement\Device\DeviceController;


// $container = new Container();
// $container->set(DeviceRepository::class, new DeviceRepository());
// AppFactory::setContainer($container);

$app = AppFactory::create();


$app->group('/devices', function (RouteCollectorProxy $group) {
    $group->get('', [DeviceController::class, 'index']);
    $group->get('/{id}', [DeviceController::class, 'getById']);
});
$app->run();