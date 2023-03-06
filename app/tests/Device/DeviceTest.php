<?php

namespace Tympahealth\Domain\Device;

use DomainException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Tympahealth\Domain\Device\Device;
use Tympahealth\Domain\Device\DeviceRepository;

class DeviceTest extends TestCase
{
    private $repositoryMock;

    protected function setUp(): void
    {
        $this->repositoryMock = $this->createMock(DeviceRepository::class);
    }

    public function testSearch(): void
    {
        $searchText = 'test';
        $expectedDevices = [
            [
                'id' => 'c9b7ff2d-ecb6-4ca6-b1d3-3e4d9c4ee4a4',
                'brand' => 'Apple',
                'model' => 'iPhone 11 Pro',
                'os' => 'iOS',
                'release_date' => '09/10/2019',
                'created_datetime' => 1571400553,
                'updated_datetime' => 1571400553
            ],
        ];

        $this->repositoryMock
            ->expects($this->once())
            ->method('search')
            ->with($searchText)
            ->willReturn($expectedDevices);

        $devices = Device::search($this->repositoryMock, $searchText);

        $this->assertEquals(count($expectedDevices), count($devices));
        $this->assertEquals($expectedDevices[0]['id'], $devices[0]->id);
    }

    public function testAll(): void
    {
        $expectedDevices = [
            [
                'id' => 'c9b7ff2d-ecb6-4ca6-b1d3-3e4d9c4ee4a4',
                'brand' => 'Apple',
                'model' => 'iPhone 11 Pro',
                'os' => 'iOS',
                'release_date' => '09/10/2019',
                'created_datetime' => 1571400553,
                'updated_datetime' => 1571400553
            ],
        ];

        $this->repositoryMock
            ->expects($this->once())
            ->method('getDevices')
            ->willReturn($expectedDevices);

        $devices = Device::all($this->repositoryMock);

        $this->assertEquals(count($expectedDevices), count($devices));
        $this->assertEquals($expectedDevices[0]['id'], $devices[0]->id);
    }

    public function testCreate(): void
    {
        $brand = 'Apple';
        $model = 'iPhone 11 Pro';
        $os = 'iOS';
        $release_date = '09/10/2019';

        $this->repositoryMock
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Device $device) use ($brand, $model, $os, $release_date) {
                return $device->brand === $brand
                    && $device->model === $model
                    && $device->os === $os
                    && $device->release_date === $release_date
                    && $device->created_datetime > 0
                    && $device->updated_datetime > 0;
            }));

        $device = Device::create($this->repositoryMock, $brand, $model, $os, $release_date);

        $this->assertEquals($brand, $device->brand);
        $this->assertEquals($model, $device->model);
        $this->assertEquals($os, $device->os);
        $this->assertEquals($release_date, $device->release_date);
        $this->assertTrue(Uuid::isValid($device->id));
        $this->assertTrue(is_int($device->created_datetime));
        $this->assertTrue(is_int($device->updated_datetime));
    }

    public function testUpdate(): void
    {
        $id = 'c9b7ff2d-ecb6-4ca6-b1d3-3e4d9c4ee4a4';
        $brand = 'Apple';
        $model = 'iPhone 11 Pro';
        $os = 'iOS';
        $release_date = '09/10/2019';

        $existingDevice = [
            'id' => $id,
            'brand' => 'Apple',
            'model' => 'iPhone XR',
            'os' => 'iOS',
            'release_date' => '09/10/2018',
            'created_datetime' => 1571400553,
            'updated_datetime' => 1571400553
        ];

        $this->repositoryMock
            ->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($existingDevice);

        $this->repositoryMock
            ->expects($this->once())
            ->method('update')
            ->with($this->callback(function (Device $device) use ($id, $brand, $model, $os, $release_date) {
                return $device->id === $id
                    && $device->brand === $brand
                    && $device->model === $model
                    && $device->os === $os
                    && $device->release_date === $release_date
                    && $device->created_datetime === 1571400553 // confirms that the created_datetime is not updated
                    && $device->updated_datetime > 1571400553;
            }));

        Device::update($this->repositoryMock, $id, [
            'brand' => $brand,
            'model' => $model,
            'os' => $os,
            'release_date' => $release_date
        ]);
    }


    public function testDelete(): void
    {
        $id = 'c9b7ff2d-ecb6-4ca6-b1d3-3e4d9c4ee4a4';

        $existingDevice = [
            'id' => $id,
            'brand' => 'Apple',
            'model' => 'iPhone 11 Pro',
            'os' => 'iOS',
            'release_date' => '09/10/2019',
            'created_datetime' => 1571400553,
            'updated_datetime' => 1571400553
        ];

        $this->repositoryMock
            ->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($existingDevice);

        $this->repositoryMock
            ->expects($this->once())
            ->method('delete')
            ->with($id);

        Device::delete($this->repositoryMock, $id);
    }

    public function testDeleteWhenDeviceNotFound(): void
    {
        $id = 'c9b7ff2d-ecb6-4ca6-b1d3-3e4d9c4ee4a4';

        $this->repositoryMock
            ->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(null);

        $this->repositoryMock
            ->expects($this->never())
            ->method('delete');

        $this->expectException(DomainException::class);
        $this->expectExceptionCode(404);
        $this->expectExceptionMessage('DEVICE_NOT_FOUND');

        Device::delete($this->repositoryMock, $id);
    }
}