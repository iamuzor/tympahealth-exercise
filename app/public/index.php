<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Tympahealth\App\Container;
use Tympahealth\App\Infrastructure\DeviceRepository;
use Tympahealth\App\Controllers\DeviceController;
use Tympahealth\Domain\Device\IDeviceRepository;

$container = new Container();
$container->set(IDeviceRepository::class, DeviceRepository::getInstance());

AppFactory::setContainer($container);

$app = AppFactory::create();
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);

// Routes
$app->group('/devices', function (RouteCollectorProxy $group) {
    $group->get('', [DeviceController::class, 'index']);
    $group->post('', [DeviceController::class, 'create']);
    $group->get('/search/{text}', [DeviceController::class, 'search']);
    $group->put('/{id}', [DeviceController::class, 'update']);
    $group->delete('/{id}', [DeviceController::class, 'delete']);
});

$app->run();