<?php declare(strict_types=1);

namespace Sample\Service;

use Redis as Redis;
use Sample\Repository\UserRepository;

class UserService
{
    const USER_HASH_PREFIX = 'user';

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param string $username
     * @param string $email
     * @param string $password
     */
    public function addUser(string $username, string $email, string $password): void
    {
        // TODO - validation !!! throw exceptions

    }

    /**
     * @param string $username
     * @param string $password
     * @return array
     */
    public function authorize(string $username, string $password): array
    {
        // TODO - validation !!!  throw exceptions
    }

}
