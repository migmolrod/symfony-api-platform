<?php

namespace Mailer\Messenger\Handler;

use Mailer\Messenger\Message\RequestResetPasswordMessage;
use Mailer\Service\Mailer\ClientRoute;
use Mailer\Service\Mailer\MailerService;
use Mailer\Templating\TwigTemplate;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use function sprintf;

class RequestResetPasswordMessageHandler implements MessageHandlerInterface
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
    public function __invoke(RequestResetPasswordMessage $requestResetPasswordMessage): void
    {
        $payload = [
            'url' => sprintf(
                '%s%s?resetPasswordToken=%s&uid=%s',
                $this->host,
                ClientRoute::RESET_PASSWORD,
                $requestResetPasswordMessage->getResetPasswordToken(),
                $requestResetPasswordMessage->getId(),
            ),
        ];

        $this->mailerService->send($requestResetPasswordMessage->getEmail(), TwigTemplate::REQUEST_RESET_PASSWORD, $payload);
    }
}
