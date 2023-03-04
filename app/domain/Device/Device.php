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
        public readonly string $created_at,
        private string $updated_at
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

    public static function create(IDeviceRepository $repository, array $data): Device
    {
        $device = new Device(
            Uuid::uuid4()->toString(),
            $data['brand'],
            $data['model'],
            $data['os'],
            $data['release_date'],
            (new DateTime())->format('Y/m/d'),
            (new DateTime())->format('Y/m/d')
        );

        $repository->save($device);

        return $device;
    }

    public static function update(IDeviceRepository $repository, string $id, array $data): void
    {
        $device = $repository->find($id);

        if (!$device) {
            throw new DomainException('DEVICE_NOT_FOUND', 404);
        }

        $repository->update(
            new Device(
                $id,
                $data['brand'] ?? $device['brand'],
                $data['model'] ?? $device['model'],
                $data['os'] ?? $device['os'],
                $data['release_date'] ?? $device['release_date'],
                $device['created_at'],
                (new DateTime())->format('Y/m/d')
            )
        );
    }

    public static function delete(IDeviceRepository $repository, string $id): void
    {
        $repository->delete($id);
    }

    private static function transform(array $device): Device
    {
        return new Device(
            $device['id'],
            $device['brand'],
            $device['model'],
            $device['os'],
            $device['release_date'] ?? '',
            $device['created_at'] ?? '',
            $device['updated_at'] ?? ''
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
            'created_at' => $this?->created_at,
            'updated_at' => $this?->updated_at,
        ];
    }
}