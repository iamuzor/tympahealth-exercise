<?php

namespace Tympahealth\Domain\Device;

use DateTime;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use Tympahealth\Domain\Device\DeviceRepository;
use Tympahealth\Domain\Device\Device;

class DeviceController
{
    public function index(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $devices = Device::all(DeviceRepository::getInstance());

        $response->getBody()->write(json_encode(['data' => $devices]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function search(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $devices = Device::search(DeviceRepository::getInstance(), $args['text']);

        $response->getBody()->write(json_encode(['data' => $devices]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function create(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $body = $request->getParsedBody();

        if (empty($body['model'])) {
            throw new HttpBadRequestException($request, '"model" is required');
        }

        if (empty($body['brand'])) {
            throw new HttpBadRequestException($request, '"brand" is required');
        }

        if (!preg_match('/^\d{4}\/\d{2}$/', $body['release_date'])) {
            throw new HttpBadRequestException($request, '"release_date" must be in the format YYYY/MM');
        }

        $device = Device::create(DeviceRepository::getInstance(), $body['brand'], $body['model'], $body['os'] ?? '', DateTime::createFromFormat('Y/m', $body['release_date'])->format('Y/m'));

        $response->getBody()->write(json_encode(['data' => $device]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function delete(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        Device::delete(DeviceRepository::getInstance(), $args['id']);

        $response->getBody()->write(json_encode(['data' => ['deleted' => true]]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function update(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        Device::update(DeviceRepository::getInstance(), $args['id'], $request->getParsedBody());

        $response->getBody()->write(json_encode(['data' => ['updated' => true]]));

        return $response->withHeader('Content-Type', 'application/json');
    }
}