<?php

namespace App\Tests\Unit\Service\User;

use App\Entity\User;
use App\Exception\User\UserNotFoundException;
use App\Service\User\ActivateAccountService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use function sha1;
use Symfony\Component\Uid\Uuid;
use function uniqid;

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
        $id = Uuid::v4()->toRfc4122();
        $token = UidGenerator::generateUid();

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
        $id = Uuid::v4()->toRfc4122();
        $token = UidGenerator::generateUid();

        $this->userRepository
            ->expects(self::once())
            ->method('findOneInactiveByIdAndTokenOrFail')
            ->with($id, $token)
            ->willThrowException(new UserNotFoundException(\sprintf('User with id %s and token %s not found or already active', $id, $token)));

        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage(\sprintf('User with id %s and token %s not found or already active', $id, $token));

        $this->service->activate($id, $token);
    }
}
