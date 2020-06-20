<?php declare(strict_types=1);

namespace SampleTest\Unit;

use PHPUnit\Framework\TestCase;
use Sample\Exception\ValidationException;
use Sample\Repository\UserRepositoryInterface;
use Sample\Service\UserService;
use Sample\Service\UserServiceInterface;

class UserServiceTest extends TestCase
{
    /**
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $userRepositoryMock;

    /**
     * @var UserServiceInterface
     */
    private UserServiceInterface $userService;

    protected function setUp(): void
    {
        $this->userRepositoryMock = $this->createMock(UserRepositoryInterface::class);

        $this->userService = new UserService($this->userRepositoryMock);
    }

    /**
     * @test
     */
    public function canAddUser(): void
    {
        $this->userRepositoryMock->expects($this->once())
            ->method('addUser');

        $this->userService->addUser(
            'SampleUser',
            'SampleEmail@gmail.com',
            'SamplePassword',
        );

        $user = $this->userRepositoryMock->getUser('SampleUser');
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

        $this->userRepositoryMock->method('getUser')
            ->willReturn(
                [
                    'username' => 'SomeUser',
                    'email'    => 'SampleEmail@gmail.com',
                    'password' => $passHash,
                ]
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

        $this->userRepositoryMock->method('getUser')
            ->willReturn([]);

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

        $this->userRepositoryMock->method('getUser')
            ->willReturn(
                [
                    'username' => 'SomeUser',
                    'email'    => 'SampleEmail@gmail.com',
                    'password' => $passHash,
                ]
            );

        $this->userService->authorize('SomeUser', 'WrongPassword');
    }
}
