<?php

namespace Tympahealth\DeviceManagement\Device;

class DeviceRepository
{
    private static DeviceRepository $instance;

    static function getInstance(): DeviceRepository
    {
        if (!isset(self::$instance)) {
            self::$instance = new DeviceRepository();
        }

        return self::$instance;
    }

    public function getDevices(): array
    {
        return [new Device()];
    }

    public function getDevice($id): Device
    {
        return new Device();
    }
}