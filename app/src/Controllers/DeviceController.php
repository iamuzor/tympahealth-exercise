<?php

namespace Tympahealth\App\Controllers;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tympahealth\Domain\Device\Device;
use Tympahealth\Domain\Device\IDeviceRepository;

class DeviceController
{
    protected ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function index(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $repository = $this->container->get(IDeviceRepository::class);
        $devices = Device::all($repository);
        $response->getBody()->write(json_encode($devices));

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function search(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $repository = $this->container->get(IDeviceRepository::class);
        $devices = Device::search($repository, $args['text']);
        $response->getBody()->write(json_encode($devices));

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function create(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $repository = $this->container->get(IDeviceRepository::class);
        $device = Device::create($repository, $request->getParsedBody());
        $response->getBody()->write(json_encode($device));

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function delete(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $repository = $this->container->get(IDeviceRepository::class);
        Device::delete($repository, $args['id']);
        $response->getBody()->write(json_encode(['deleted' => true]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function update(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $repository = $this->container->get(IDeviceRepository::class);
        Device::update($repository, $args['id'], $request->getParsedBody());

        $response->getBody()->write(json_encode(['updated' => true]));

        return $response->withHeader('Content-Type', 'application/json');
    }
}