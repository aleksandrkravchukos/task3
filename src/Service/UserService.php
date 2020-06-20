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
     * @return bool
     */
    public function addUser(string $username, string $email, string $password): bool
    {
        // TODO - validation !!! throw exceptions

        return $this->userRepository->addUser($username, $email, password_hash($password, PASSWORD_ARGON2I));

    }


    public function authorize(string $username, string $password): bool
    {
        $user = $this->userRepository->getUser($username);

//        var_dump($user);
//        exit;

        if (empty($user)) {
            throw new \Exception(sprintf('User %s not exist', $username));
        }

        var_dump($password);
        var_dump($user['password']);
        var_dump(password_verify($password, $user['password']));

        if (password_verify($password, $user['password'])) {
            throw new \Exception('Password in incorrect.');
        }

        return true;
    }

}
