<?php declare(strict_types=1);

namespace Sample\Service;

interface UserServiceInterface
{
    // These may be moved to config, but left as constants for simplicity
    const MIN_USERNAME_LENGTH        = 3;
    const MAX_USERNAME_LENGTH        = 15;
    const MIN_PASSWORD_LENGTH        = 6;
    const MAX_PASSWORD_LENGTH        = 20;

    /**
     * @param string $username
     * @param string $email
     * @param string $password
     *
     * @throws \Exception
     */
    public function addUser(string $username, string $email, string $password): void;

    /**
     * @param string $username
     * @param string $password
     *
     * @throws \Exception
     *
     * @return bool
     */
    public function authorize(string $username, string $password): bool;

}
