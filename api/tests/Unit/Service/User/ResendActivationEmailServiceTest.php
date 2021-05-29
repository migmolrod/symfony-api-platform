<?php

namespace App\Tests\Unit\Service\User;

use App\Entity\User;
use App\Exception\User\UserAlreadyActiveException;
use App\Service\User\RequestResetPasswordService;
use App\Service\User\ResendActivationEmailService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\Messenger\Envelope;

class ResendActivationEmailServiceTest extends UserServiceTestBase
{
    private ResendActivationEmailService $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new ResendActivationEmailService($this->userRepository, $this->messageBus);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function testResendActivationEmail(): void
    {
        $email = 'username@api.com';
        $user = new User('username', $email);

        $this->userRepository
            ->expects(self::once())
            ->method('findOneByEmailOrFail')
            ->with($email)
            ->willReturn($user);

        $message = $this->getMockBuilder(RequestResetPasswordService::class)->disableOriginalConstructor()->getMock();
        $this->messageBus
            ->expects(self::once())
            ->method('dispatch')
            ->with(self::isType('object'), self::isType('array'))
            ->willReturn(new Envelope($message));

        $this->service->resend($email);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function testResendActivationEmailForAlreadyActiveUser(): void
    {
        $email = 'username@api.com';
        $user = new User('username', $email);
        $user->setActive(true);

        $this->userRepository
            ->expects(self::once())
            ->method('findOneByEmailOrFail')
            ->with($email)
            ->willReturn($user);

        $this->expectException(UserAlreadyActiveException::class);

        $this->service->resend($email);
    }
}
