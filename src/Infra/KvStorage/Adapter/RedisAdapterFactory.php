<?php

declare(strict_types=1);

namespace App\Infra\KvStorage\Adapter;

use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\Cache\Adapter\RedisTagAwareAdapter;

class RedisAdapterFactory
{
    public static function getAdapter(string $host, string $port): \Redis
    {
        $client = new \Redis();
        $client->connect('redis', 6379);
        return $client;
    }
}
