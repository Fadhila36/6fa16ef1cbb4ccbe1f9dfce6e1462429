<?php

namespace App;

use Predis\Client;

class RedisQueue
{
    private $redis;

    public function __construct()
    {
        $this->redis = new Client([
            'scheme' => 'tcp',
            'host'   => $_ENV['REDIS_HOST'],
            'port'   => $_ENV['REDIS_PORT'],
        ]);
    }

    public function enqueue($data)
    {
        $this->redis->rpush('email_queue', json_encode($data));
    }

    public function dequeue()
    {
        return json_decode($this->redis->lpop('email_queue'), true);
    }
}
