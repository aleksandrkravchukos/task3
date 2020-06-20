<?php
declare(strict_types=1);

namespace Sample\Config;

interface ConfigInterface
{
    public function getRedisHost(): string;

    public function getRedisPort(): int;
}
