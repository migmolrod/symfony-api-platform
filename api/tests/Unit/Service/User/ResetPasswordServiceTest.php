<?php

namespace App\Tests\Unit\Service\User;

use App\Entity\User;
use App\Exception\Password\PasswordException;
use App\Exception\User\UserNotFoundException;
use App\Service\User\ResetPasswordService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Uid\Uuid;

class ResetPasswordServiceTest extends UserServiceTestBase
{
    private ResetPasswordService $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new ResetPasswordService($this->userRepository, $this->encoderService);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function testResetPassword(): void
    {
        $resetPasswordToken = sha1(uniqid('SYM', true));
        $password = 'newPassword123';
        $user = new User('username', 'username@api.com');

        $this->userRepository
            ->expects(self::once())
            ->method('findOneByIdAndResetPasswordTokenOrFail')
            ->with($user->getId(), $resetPasswordToken)
            ->willReturn($user);

        $user = $this->service->reset($user->getId(), $resetPasswordToken, $password);

        self::assertInstanceOf(User::class, $user);
        self::assertNull($user->getResetPasswordToken());
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function testResetPasswordForNonExistingUser(): void
    {
        $resetPasswordToken = sha1(uniqid('SYM', true));
        $password = 'newPassword123';
        $user = new User('non-existing', 'non-existing@api.com');

        $this->userRepository
            ->expects(self::once())
            ->method('findOneByIdAndResetPasswordTokenOrFail')
            ->with($user->getId(), $resetPasswordToken)
            ->willThrowException(new UserNotFoundException());

        $this->expectException(UserNotFoundException::class);

        $this->service->reset($user->getId(), $resetPasswordToken, $password);
    }
}
