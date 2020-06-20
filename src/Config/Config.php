<?php
declare(strict_types=1);

namespace Sample\Config;

use phpDocumentor\Reflection\Types\Integer;
use Redis as Redis;

class Config
{
    public function getRedisHost(): string
    {
        return 'redis';
    }

    public function getRedisPort(): int
    {
        return 6379;
    }

}
