<?php

namespace Tympahealth\Domain\Device;

use DateTime;
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
        $response->getBody()->write(json_encode(['data' => $devices]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function search(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $repository = $this->container->get(IDeviceRepository::class);
        $devices = Device::search($repository, $args['text']);
        $response->getBody()->write(json_encode(['data' => $devices]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function create(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $repository = $this->container->get(IDeviceRepository::class);
        $body = $request->getParsedBody();

        $device = Device::create($repository, $body['brand'], $body['model'], $body['os'], DateTime::createFromFormat('Y/m', $body['release_date'])->format('Y/m'));

        $response->getBody()->write(json_encode(['data' => $device]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function delete(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $repository = $this->container->get(IDeviceRepository::class);
        Device::delete($repository, $args['id']);
        $response->getBody()->write(json_encode(['data' => ['deleted' => true]]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function update(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $repository = $this->container->get(IDeviceRepository::class);

        Device::update($repository, $args['id'], $request->getParsedBody());

        $response->getBody()->write(json_encode(['data' => ['updated' => true]]));

        return $response->withHeader('Content-Type', 'application/json');
    }
}