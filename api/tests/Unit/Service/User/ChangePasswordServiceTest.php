<?php

namespace App\Tests\Unit\Service\User;

use App\Entity\User;
use App\Exception\Password\PasswordException;
use App\Service\User\ChangePasswordService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class ChangePasswordServiceTest extends UserServiceTestBase
{
    private ChangePasswordService $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new ChangePasswordService($this->userRepository, $this->encoderService);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function testChangePassword(): void
    {
        $user = new User('username', 'username@api.com');
        $oldPassword = 'oldPassword123';
        $newPassword = 'newPassword123';

        $this->userRepository
            ->expects(self::once())
            ->method('findOneByIdOrFail')
            ->with($user->getId())
            ->willReturn($user);

        $this->encoderService
            ->expects(self::once())
            ->method('isValidPassword')
            ->with($user, $oldPassword)
            ->willReturn(true);

        $user = $this->service->change($user->getId(), $oldPassword, $newPassword);

        self::assertInstanceOf(User::class, $user);
        self::assertNull($user->getResetPasswordToken());
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function testChangePasswordForInvalidOldPassword(): void
    {
        $user = new User('username', 'username@api.com');
        $oldPassword = 'oldPassword123';
        $newPassword = 'newPassword123';

        $this->userRepository
            ->expects(self::once())
            ->method('findOneByIdOrFail')
            ->with($user->getId())
            ->willReturn($user);

        $this->encoderService
            ->expects(self::once())
            ->method('isValidPassword')
            ->with($user, $oldPassword)
            ->willReturn(false);

        $this->expectException(PasswordException::class);

        $this->service->change($user->getId(), $oldPassword, $newPassword);
    }
}
