<?php

namespace App\Infra\KvStorage\Adapter;

interface SimpleRedisAdapterInterface
{
    public function set($key, $value, $options = null): void;
    public function get($key);
}