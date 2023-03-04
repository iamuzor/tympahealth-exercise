<?php

namespace Tympahealth\DeviceManagement\Device;

class DeviceRepository
{
    public function getDevices(): array
    {
        return [new Device()];
    }
}