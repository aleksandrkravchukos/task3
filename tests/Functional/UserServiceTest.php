<?php declare(strict_types=1);

namespace SampleTest\Functional;

use PHPUnit\Framework\TestCase;
use Redis as Redis;
use Sample\Config\Config;
use Sample\Exception\ValidationException;
use Sample\Repository\UserRepository;
use Sample\Repository\UserRepositoryInterface;
use Sample\Service\UserService;
use Sample\Service\UserServiceInterface;

class UserServiceTest extends TestCase
{
    /**
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $userRepository;

    /**
     * @var UserServiceInterface
     */
    private UserServiceInterface $userService;

    protected function setUp(): void
    {
        $config = new Config();

        $redis = new Redis();

        $redis->connect(
            $config->getRedisHost(),
            $config->getRedisPort(),
        );

        $this->userRepository = new UserRepository($redis);

        $this->userService = new UserService($this->userRepository);

        $redis->flushAll();
    }

    /**
     * @test
     */
    public function canAddUser(): void
    {
        $this->userService->addUser(
            'SampleUser',
            'SampleEmail@gmail.com',
            'SamplePassword',
        );

        $user = $this->userRepository->getUser('SampleUser');

        $this->assertNotEmpty($user);
        $this->assertEquals('SampleUser', $user['username']);
    }

    /**
     * @test
     */
    public function throwsExceptionAddUserUsernameLessThanThreeChars(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Username Us is of 2 symbols, while it should be between 3 and 12 symbols.');

        $this->userService->addUser(
            'Us',
            'SampleEmail@gmail.com',
            'SamplePassword',
        );
    }

    /**
     * @test
     */
    public function throwsExceptionAddUserUsernameLongerThanTwelveChars(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Username SomeVeryLongUserNameThatExceedsLength is of 37 symbols, while it should be between 3 and 12 symbols.');

        $this->userService->addUser(
            'SomeVeryLongUserNameThatExceedsLength',
            'SampleEmail@gmail.com',
            'SamplePassword',
        );
    }

    /**
     * @test
     */
    public function throwsExceptionAddUserInvalidEmail(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Email NotAnEmail is invalid.');

        $this->userService->addUser(
            'SomeUser',
            'NotAnEmail',
            'SamplePassword',
        );
    }

    /**
     * @test
     */
    public function throwsExceptionAddUserPasswordLessThanSixChars(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Password is of 2 symbols, while it should be between 3 and 12 symbols.');

        $this->userService->addUser(
            'SomeUser',
            'SampleEmail@gmail.com',
            'ps',
        );
    }

    /**
     * @test
     */
    public function throwsExceptionAddUserPasswordLongerThanTwelveChars(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Password is of 44 symbols, while it should be between 3 and 12 symbols.');

        $this->userService->addUser(
            'SomeUser',
            'SampleEmail@gmail.com',
            'SomeVeryLongPasswordThatExceedsAllowedLength',
        );
    }

    /**
     * @test
     */
    public function canAuthorizeUser(): void
    {
        $password = 'SomePassword';
        $passHash = password_hash($password, PASSWORD_ARGON2I, UserService::PASSWORD_ENCRYPTION_PARAMS);

        $this->userRepository->addUser(
            'SomeUser',
            'SampleEmail@gmail.com',
            $passHash,
        );

        $isAuthorized = $this->userService->authorize('SomeUser', $password);

        $this->assertTrue($isAuthorized);
    }

    /**
     * @test
     */
    public function throwsExceptionAuthorizeUserNotExist(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('User NotExistingUser not exist.');

        $password = 'SomePassword';
        $passHash = password_hash($password, PASSWORD_ARGON2I, UserService::PASSWORD_ENCRYPTION_PARAMS);

        $this->userRepository->addUser(
            'SomeUser',
            'SampleEmail@gmail.com',
            $passHash,
        );

        $this->userService->authorize('NotExistingUser', $password);
    }

    /**
     * @test
     */
    public function throwsExceptionAuthorizeUserInvalidPassword(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Password in incorrect.');

        $password = 'SomePassword';
        $passHash = password_hash($password, PASSWORD_ARGON2I, UserService::PASSWORD_ENCRYPTION_PARAMS);

        $this->userRepository->addUser(
            'SomeUser',
            'SampleEmail@gmail.com',
            $passHash,
        );

        $this->userService->authorize('SomeUser', 'WrongPassword');
    }
}
