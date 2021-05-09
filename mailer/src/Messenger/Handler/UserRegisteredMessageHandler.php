<?php

namespace Mailer\Messenger\Handler;

use Mailer\Messenger\Message\UserRegisteredMessage;
use Mailer\Service\Mailer\ClientRoute;
use Mailer\Service\Mailer\MailerService;
use Mailer\Templating\TwigTemplate;
use function sprintf;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class UserRegisteredMessageHandler implements MessageHandlerInterface
{
    private MailerService $mailerService;
    private string $host;

    public function __construct(MailerService $mailerService, string $host)
    {
        $this->mailerService = $mailerService;
        $this->host = $host;
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function __invoke(UserRegisteredMessage $userRegisteredMessage): void
    {
        $payload = [
            'name' => $userRegisteredMessage->getName(),
            'url' => sprintf(
                '%s%s?token=%s&uid=%s',
                $this->host,
                ClientRoute::ACTIVATE_ACCOUNT,
                $userRegisteredMessage->getToken(),
                $userRegisteredMessage->getId(),
            ),
        ];

        $this->mailerService->send($userRegisteredMessage->getEmail(), TwigTemplate::USER_REGISTER, $payload);
    }
}
