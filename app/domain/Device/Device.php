<?php

namespace Tympahealth\Domain\Device;

use DateTime;
use DomainException;
use Ramsey\Uuid\Uuid;
use Tympahealth\Domain\Device\IDeviceRepository;

class Device implements \JsonSerializable
{
    private function __construct(
        public readonly string $id,
        public readonly string $brand,
        public readonly string $model,
        public readonly string $os,
        public readonly string $release_date,
        public readonly int $created_datetime,
        public readonly int|null $updated_datetime = null
    )
    {
    }

    /**
     * @return Device[]
     */
    public static function search(IDeviceRepository $repository, string $text): array
    {
        return array_map(fn($device) => self::transform($device), $repository->search($text));
    }

    /**
     * @return Device[]
     */
    public static function all(IDeviceRepository $repository): array
    {
        return array_map(fn($device) => self::transform($device), $repository->getDevices());
    }

    public static function create(IDeviceRepository $repository, string $brand, string $model, string $os, string $release_date): Device
    {
        $device = new Device(
            Uuid::uuid4()->toString(),
            $brand,
            $model,
            $os,
            $release_date,
            time(),
            time()
        );

        $repository->save($device);

        return $device;
    }

    public static function update(IDeviceRepository $repository, string $id, array $args): void
    {
        $device = $repository->find($id);

        if (!$device) {
            throw new DomainException('DEVICE_NOT_FOUND', 404);
        }

        $repository->update(
            new Device(
                $id,
                $args['brand'] ?? $device['brand'],
                $args['model'] ?? $device['model'],
                $args['os'] ?? $device['os'],
                $args['release_date'] ?? $device['release_date'],
                $device['created_datetime'],
                time()
            )
        );
    }

    public static function delete(IDeviceRepository $repository, string $id): void
    {
        if (!$repository->find($id)) {
            throw new DomainException('DEVICE_NOT_FOUND', 404);
        }

        $repository->delete($id);
    }

    private static function transform(array $device): Device
    {
        return new Device(
            $device['id'],
            $device['brand'],
            $device['model'],
            $device['os'],
            $device['release_date'],
            $device['created_datetime'],
            $device['updated_datetime']
        );
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'brand' => $this->brand,
            'model' => $this->model,
            'os' => $this->os,
            'release_date' => $this->release_date,
            'created_datetime' => $this->created_datetime,
            'updated_datetime' => $this->updated_datetime
        ];
    }
}