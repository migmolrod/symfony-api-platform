<?php

namespace App\Tests\Unit\Service\User;

use App\Entity\User;
use App\Exception\Password\PasswordException;
use App\Exception\User\UserAlreadyExistsException;
use App\Messenger\Message\UserRegisteredMessage;
use App\Service\User\UserRegisterService;
use Doctrine\ORM\ORMException;
use Symfony\Component\Messenger\Envelope;

class UserRegisterServiceTest extends UserServiceTestBase
{
    private UserRegisterService $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new UserRegisterService($this->userRepository, $this->encoderService, $this->messageBus);
    }

    public function testUserRegister(): void
    {
        $name = 'username';
        $email = 'username@api.com';
        $password = '123456';

        $message = $this->getMockBuilder(UserRegisteredMessage::class)->disableOriginalConstructor()->getMock();

        $this->messageBus
            ->expects(self::once())
            ->method('dispatch')
            ->with(self::isType('object'), self::isType('array'))
            ->willReturn(new Envelope($message));

        $user = $this->service->create($name, $email, $password);

        self::assertInstanceOf(User::class, $user);
        self::assertEquals($name, $user->getName());
        self::assertEquals($email, $user->getEmail());
        self::assertEquals($email, $user->getUsername());
    }

    public function testUserRegisterForInvalidPassword(): void
    {
        $name = 'username';
        $email = 'username@api.com';
        $password = '123';

        $message = $this->getMockBuilder(UserRegisteredMessage::class)->disableOriginalConstructor()->getMock();

        $this->encoderService
            ->expects(self::once())
            ->method('generateEncodedPassword')
            ->with(self::isType('object'), self::isType('string'))
            ->willThrowException(new PasswordException());

        $this->expectException(PasswordException::class);

        $this->service->create($name, $email, $password);
    }

    public function testUserRegisterForAlreadyExistingUser(): void
    {
        $name = 'username';
        $email = 'username@api.com';
        $password = '123456';

        $message = $this->getMockBuilder(UserRegisteredMessage::class)->disableOriginalConstructor()->getMock();

        $this->userRepository
            ->expects(self::once())
            ->method('save')
            ->with(self::isType('object'))
            ->willThrowException(new ORMException());

        $this->expectException(UserAlreadyExistsException::class);

        $this->service->create($name, $email, $password);
    }
}
