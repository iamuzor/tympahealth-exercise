<?php

namespace Tympahealth\DeviceManagement\Device;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class DeviceController
{
    private DeviceRepository $deviceRepository;

    public function __construct()
    {
        $this->deviceRepository = DeviceRepository::getInstance();
    }

    public function index(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $devices = $this->deviceRepository->getDevices();

        $response->getBody()->write(json_encode($devices));
        $response->withHeader('Content-Type', 'application/json');

        return $response;
    }

    public function findById(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $device = $this->deviceRepository->getDevice($args['id']);

        $response->getBody()->write(json_encode($device));
        $response->withHeader('Content-Type', 'application/json');

        return $response;
    }
}