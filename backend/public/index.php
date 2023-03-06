<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Tympahealth\Domain\Device\DeviceController;

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

// enable cors
$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($request, $handler) {
    $response = $handler->handle($request);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});


$app->run();