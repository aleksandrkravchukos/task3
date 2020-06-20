<?php declare(strict_types=1);

namespace Sample\Service;

use Sample\Exception\ValidationException;
use Sample\Repository\UserRepositoryInterface;

class UserService implements UserServiceInterface
{
    // These may be moved to config, but left as constants for simplicity
    const PASSWORD_ENCRYPTION_PARAMS = ['memory_cost' => 2048, 'time_cost' => 4, 'threads' => 3];

    /**
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @inheritDoc
     */
    public function addUser(string $username, string $email, string $password): void
    {
        $this->validateExistingUser($username);
        $this->validateUserData($username, $email, $password);

        $passHash = password_hash($password, PASSWORD_ARGON2I, self::PASSWORD_ENCRYPTION_PARAMS);

        $this->userRepository->addUser(
            $username,
            $email,
            $passHash,
            );
    }

    /**
     * @param string $username
     * @param string $email
     * @param string $password
     *
     * @throws ValidationException
     */
    private function validateUserData(string $username, string $email, string $password): void
    {
        if (strlen($username) < self::MIN_USERNAME_LENGTH || strlen($username) > self::MAX_USERNAME_LENGTH) {
            throw new ValidationException(sprintf(
                'Username %s is of %s symbols, while it should be between 3 and 12 symbols.',
                $username,
                strlen($username)
            ));
        }

        if (strlen($password) < self::MIN_PASSWORD_LENGTH || strlen($password) > self::MAX_PASSWORD_LENGTH) {
            throw new ValidationException(sprintf(
                'Password is of %s symbols, while it should be between 3 and 12 symbols.',
                strlen($password)
            ));
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new ValidationException(sprintf(
                'Email %s is invalid.',
                $email
            ));
        }
    }

    /**
     * @param string $username
     *
     * @throws ValidationException
     */
    private function validateExistingUser(string $username): void
    {
        $existingUser = $this->userRepository->getUser($username);

        if ($existingUser) {
            throw new ValidationException(sprintf('User %s already exists', $username));
        }
    }

    /**
     * @inheritDoc
     */
    public function authorize(string $username, string $password): bool
    {
        $user = $this->userRepository->getUser($username);

        if (empty($user)) {
            throw new ValidationException(sprintf('User %s not exist.', $username));
        }

        if (!password_verify($password, $user['password'])) {
            throw new ValidationException('Password in incorrect.');
        }

        return true;
    }

}
