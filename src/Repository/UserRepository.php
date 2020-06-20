<?php declare(strict_types=1);

namespace Sample\Repository;

use Redis as Redis;

class UserRepository implements UserRepositoryInterface
{
    private Redis $redis;

    public function __construct(Redis $redis)
    {
        $this->redis = $redis;
    }

    /**
     * @inheritDoc
     */
    public function addUser(string $username, string $email, string $password): bool
    {
        $key = sprintf('%s:%s', self::USER_HASH_PREFIX, $username);

        return $this->redis->hMSet(
            $key,
            [
                'username' => $username,
                'email'    => $email,
                'password' => $password,
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function getUser(string $username): array
    {
        $key = $this->getHashKeyByUserName($username);

        return $this->redis->hGetAll($key);
    }

    /**
     * @param string $username
     *
     * @return string
     */
    private function getHashKeyByUserName(string $username): string
    {
        return sprintf('%s:%s', self::USER_HASH_PREFIX, $username);
    }
}
