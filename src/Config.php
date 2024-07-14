<?php

namespace App;

use Dotenv\Dotenv;

class Config
{
    public static function load()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();
    }
}
