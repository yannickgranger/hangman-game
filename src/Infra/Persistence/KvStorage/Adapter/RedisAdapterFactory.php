<?php

declare(strict_types=1);

namespace App\Infra\Persistence\KvStorage\Adapter;

class RedisAdapterFactory
{
    /**
     * @throws \RedisException
     */
    public static function getAdapter(string $host, string $port): \Redis
    {
        $client = new \Redis();
        $client->connect('redis', 6379);

        return $client;
    }
}
