<?php

namespace Tympahealth\App\Infrastructure;

use Medoo\Medoo;
use Tympahealth\Domain\Device\Device;
use Tympahealth\Domain\Device\IDeviceRepository;

class DeviceRepository implements IDeviceRepository
{
    private static DeviceRepository $instance;
    private Medoo $db;

    private function __construct()
    {
        $this->db = new Medoo([
            'database_type' => 'pgsql',
            'database_name' => getenv('DB_NAME'),
            'server' => getenv('DB_HOST'),
            'username' => getenv('DB_USER'),
            'password' => getenv('DB_PASSWORD'),
            'port' => getenv('DB_PORT'),
            'charset' => 'utf8',
        ]);
    }

    static function getInstance(): DeviceRepository
    {
        if (!isset(self::$instance)) {
            self::$instance = new DeviceRepository();
        }

        return self::$instance;
    }

    public function getDevices(): array
    {
        return $this->db->query('SELECT * FROM devices')->fetchAll();
    }

    public function find(string $id): array
    {
        $data = $this->db->query("SELECT * FROM devices WHERE id = :id LIMIT 1", [
            ':id' => $id
        ])->fetchAll();

        return count($data) ? $data[0] : [];
    }

    public function search(string $text): array
    {
        return $this->db->query("SELECT * FROM devices WHERE brand LIKE :text OR os LIKE :text OR model LIKE :text", [
            ':text' => "%$text%"
        ])->fetchAll();
    }

    public function save(Device $device): void
    {
        $this->db->insert('devices', $device->jsonSerialize());
    }

    public function update(Device $device): void
    {
        $this->db->update('devices', $device->jsonSerialize(), [
            'id' => $device->id
        ]);
    }

    public function delete(string $id): void
    {
        $this->db->delete('devices', [
            'id' => $id
        ]);
    }
}