<?php

declare(strict_types=1);

namespace App\Infra\KvStorage\Adapter;

use Redis;

/**
 * Create a factory as Symfony service and inject interface
 */
class SimpleRedisAdapter implements SimpleRedisAdapterInterface
{
    private Redis $redis;

    /**
     * @throws \RedisException
     */
    public function __construct(Redis $redis, string $host, string $port, string $timeout)
    {
        if(!$redis->isConnected()){
            $redis->connect(
                host: $host,
                port: $port,
                timeout: $timeout
            );
        };
        $this->redis = $redis;
    }

    /**
     * @throws \RedisException
     */
    public function set($key, $value, $options = null): void
    {
        $this->redis->set($key, $value, $options);
    }

    /**
     * @throws \RedisException
     */
    public function get($key): false|Redis|string|array
    {
        return $this->redis->get($key);
    }
}