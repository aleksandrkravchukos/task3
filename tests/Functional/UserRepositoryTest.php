<?php declare(strict_types=1);

namespace SampleTest\Functional;

use PHPUnit\Framework\TestCase;
use Redis as Redis;
use Sample\Config\Config;
use Sample\Repository\UserRepository;
use Sample\Repository\UserRepositoryInterface;

class UserRepositoryTest extends TestCase
{
    /**
     * @var Redis
     */
    private Redis $redis;

    /**
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $userRepository;

    protected function setUp(): void
    {
        $config = new Config();

        $this->redis = new Redis();

        $this->redis->connect(
            $config->getRedisHost(),
            $config->getRedisPort(),
        );

        $this->userRepository = new UserRepository($this->redis);

        $this->redis->flushAll();
    }

    /**
     * @test
     */
    public function canAddUser(): void
    {
        $this->userRepository->addUser(
            'SampleUser',
            'SampleEmail@gmail.com',
            'SamplePasswordHash',
        );

        $user = $this->redis->hGetAll(
            sprintf('%s:%s', UserRepositoryInterface::USER_HASH_PREFIX, 'SampleUser'),
        );

        $this->assertNotEmpty($user);
        $this->assertEquals('SampleUser', $user['username']);
        $this->assertEquals('SampleEmail@gmail.com', $user['email']);
        $this->assertEquals('SamplePasswordHash', $user['password']);
    }

    /**
     * @test
     */
    public function canGetUser(): void
    {
        $key = sprintf('%s:%s', UserRepositoryInterface::USER_HASH_PREFIX, 'SampleUser');

        $this->redis->hMSet(
            $key,
            [
                'username' => 'SampleUser',
                'email'    => 'SampleEmail@gmail.com',
                'password' => 'SamplePasswordHash',
            ]
        );

        $user = $this->userRepository->getUser('SampleUser');

        $this->assertNotEmpty($user);
        $this->assertEquals('SampleUser', $user['username']);
        $this->assertEquals('SampleEmail@gmail.com', $user['email']);
        $this->assertEquals('SamplePasswordHash', $user['password']);
    }
}
