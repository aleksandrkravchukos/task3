<?php declare(strict_types=1);

namespace Sample\Repository;

use Redis as Redis;

class UserRepository
{
    const USER_HASH_PREFIX = 'user';

    private Redis $redis;

    public function __construct(Redis $redis)
    {
        $this->redis = $redis;
    }

    /**
     * Insert user
     *
     * @param string $username
     * @param string $email
     * @param string $password
     * @return bool
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
     * Get user
     *
     * @param string $username
     *
     * @return array
     */
    public function getUser(string $username): array
    {
        return $this->redis->hGetAll(
            $this->getHashKeyByUserName($username),
        );
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
