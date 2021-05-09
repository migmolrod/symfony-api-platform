<?php

namespace App\Service\User;

use App\Exception\User\UserAlreadyActiveException;
use App\Messenger\Message\UserRegisteredMessage;
use App\Messenger\RoutingKey;
use App\Repository\UserRepository;
use App\Service\Request\RequestService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use JsonException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\MessageBusInterface;

class ResendActivationEmailService
{
    private UserRepository $userRepository;
    private MessageBusInterface $messageBus;

    public function __construct(UserRepository $userRepository, MessageBusInterface $messageBus)
    {
        $this->userRepository = $userRepository;
        $this->messageBus = $messageBus;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function resend(string $email): void
    {
        $user = $this->userRepository->findOneByEmailOrFail($email);

        if ($user->isActive()) {
            throw UserAlreadyActiveException::fromEmail($email);
        }

        $user->refreshToken();
        $this->userRepository->save($user);

        $this->messageBus->dispatch(
            new UserRegisteredMessage($user->getId(), $user->getName(), $user->getEmail(), $user->getToken()),
            [new AmqpStamp(RoutingKey::USER_QUEUE)]
        );
    }
}
