<?php

namespace Tympahealth\Domain\Device;

interface IDeviceRepository
{
    public function getDevices(): array;
    public function find(string $id): array|null;
    public function search(string $text): array;
    public function save(Device $device): void;
    public function update(Device $device): void;
    public function delete(string $id): void;
}