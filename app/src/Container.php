<?php

namespace Tympahealth\App;

use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    private $container = [];

    public function set($key, $value)
    {
        $this->container[$key] = $value;
    }

    public function get($key)
    {
        return $this->container[$key];
    }

    public function has(string $id): bool
    {
        return isset($this->container[$id]);
    }
}