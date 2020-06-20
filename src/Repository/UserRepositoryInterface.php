<?php declare(strict_types=1);

namespace Sample\Repository;

interface UserRepositoryInterface
{
    const USER_HASH_PREFIX = 'user';

    /**
     * @param string $username
     * @param string $email
     * @param string $password
     *
     * @return bool
     */
    public function addUser(string $username, string $email, string $password): bool;

    /**
     * @param string $username
     *
     * @return array
     */
    public function getUser(string $username): array;
}
