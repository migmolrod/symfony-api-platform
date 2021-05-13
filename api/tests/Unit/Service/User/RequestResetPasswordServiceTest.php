<?php

namespace App\Tests\Unit\Service\User;

use App\Entity\User;
use App\Exception\User\UserNotFoundException;
use App\Service\User\RequestResetPasswordService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\Messenger\Envelope;

class RequestResetPasswordServiceTest extends UserServiceTestBase
{
    private RequestResetPasswordService $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new RequestResetPasswordService($this->userRepository, $this->messageBus);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function testRequestResetPassword(): void
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

        $this->service->send($email);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function gestRequestResetPasswordForNonExistingUser(): void
    {
        $email = 'non-existing@api.com';
        $user = new User('non-existing', $email);

        $this->userRepository
            ->expects(self::once())
            ->method('findOneByEmailOrFail')
            ->with($email)
            ->willThrowException(new UserNotFoundException());

        $this->expectException(UserNotFoundException::class);

        $this->service->send($email);
    }
}
