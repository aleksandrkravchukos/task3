<?php declare(strict_types=1);

namespace Sample;

use Redis as Redis;
use Sample\Repository\UserRepository;

require 'vendor/autoload.php';

$config = new Config\Config();

$redis = new Redis();
$redis->connect(
    $config->getRedisHost(),
    $config->getRedisPort(),
);

$userRepository = new UserRepository($redis);
$userRepository->addUser(
    'SampleUser',
    'SampleEmail@gmail.com',
    'SamplePassword',
);

$user = $userRepository->getUser('SampleUser');

var_dump($user);

/**
 * TODOs
 *
 * 1) Implement UserService->addUser() and UserService->authorize() methods
 * 2) Add validations to tha above UserService methods,
 *  throw exceptions in case following conditions do not match
 *  - username not empty 3 to 12 symbols
 *  - email is email (google regexp)
 *  - password not empty 6 to 12 symbols
 * 3) google is there a restriction in redis to replace an existing hash
 *  - in case there is no anything built in, check is user exist when adding a new one, throw an exception if exits
 *
 * Test coverage
 *
 *  1) Functional->UserRepositoryTest
 * - canAddUser
 * - canGetUser
 *
 * 1) Functional->AuthServiceTest
 * - canAddUser
 * - throwsExceptionAddUserUsernameLessThanThreeChars
 * - throwsExceptionAddUserUsernameLongerThanTwelveChars
 * - throwsExceptionAddUserInvalidEmail
 * - throwsExceptionAddUserPasswordLessThanSixChars
 * - throwsExceptionAddUserPasswordLongerThanTwelveChars
 * - canAuthorizeUser
 * - throwsExceptionAuthorizeUserNotExist
 * - throwsExceptionAuthorizeUserInvalidPassword
 *
 * Optionally:
 * 3) Unit->AuthServiceTest
 * - the same methods as in Functional, but with mocked UserRepository and check of mock calls
 */