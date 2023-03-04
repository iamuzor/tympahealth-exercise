<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use Slim\Factory\AppFactory;
use Tympahealth\DeviceManagement\Device\DeviceController;
use Slim\Routing\RouteCollectorProxy;


$app = AppFactory::create();


$app->group('/devices', function (RouteCollectorProxy $group) {
    $group->get('', [DeviceController::class, 'index']);
    $group->get('/{id}', [DeviceController::class, 'getById']);
});
$app->run();