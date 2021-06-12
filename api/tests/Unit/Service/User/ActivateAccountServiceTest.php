<?php

namespace App\Tests\Unit\Service\User;

use App\Entity\User;
use App\Exception\User\UserNotFoundException;
use App\Service\User\ActivateAccountService;
use App\Service\Utils\UidGenerator;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use function sprintf;

class ActivateAccountServiceTest extends UserServiceTestBase
{
    private ActivateAccountService $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new ActivateAccountService($this->userRepository);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function testActivateAccount(): void
    {
        $user = new User('user1', 'user@api.com');
        $id = UidGenerator::generateId();
        $token = UidGenerator::generateToken();

        $this->userRepository
            ->expects(self::once())
            ->method('findOneInactiveByIdAndTokenOrFail')
            ->with($id, $token)
            ->willReturn($user);

        $user = $this->service->activate($id, $token);

        self::assertInstanceOf(User::class, $user);
        self::assertNull($user->getToken());
        self::assertTrue($user->isActive());
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function testForNonExistingUser(): void
    {
        $id = UidGenerator::generateId();
        $token = UidGenerator::generateToken();

        $this->userRepository
            ->expects(self::once())
            ->method('findOneInactiveByIdAndTokenOrFail')
            ->with($id, $token)
            ->willThrowException(new UserNotFoundException(sprintf('User with id %s and token %s not found or already active', $id, $token)));

        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage(sprintf('User with id %s and token %s not found or already active', $id, $token));

        $this->service->activate($id, $token);
    }
}
