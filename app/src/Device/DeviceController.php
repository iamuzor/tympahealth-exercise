<?php

namespace Tympahealth\DeviceManagement\Device;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class DeviceController
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function home(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $devices = $this->container->get(DeviceRepository::class)->getDevices();

        $response->getBody()->write(json_encode($devices));
        $response->withHeader('Content-Type', 'application/json');

        return $response;
    }
}