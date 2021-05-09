<?php

namespace Mailer\Service\Mailer;

use Mailer\Templating\TwigTemplate;
use Psr\Log\LoggerInterface;
use function sprintf;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class MailerService
{
    private const TEMPLATE_SUBJECT_MAP = [
        TwigTemplate::USER_REGISTER => 'Bienvenido/a',
        TwigTemplate::REQUEST_RESET_PASSWORD => 'Restablecer contraseña',
    ];
    private MailerInterface $mailer;
    private Environment $environment;
    private LoggerInterface $logger;
    private string $mailerDefaultSender;

    public function __construct(MailerInterface $mailer, Environment $environment, LoggerInterface $logger, string $mailerDefaultSender)
    {
        $this->mailer = $mailer;
        $this->environment = $environment;
        $this->logger = $logger;
        $this->mailerDefaultSender = $mailerDefaultSender;
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function send(string $receiver, string $template, array $payload): void
    {
        $email = (new Email())
            ->from($this->mailerDefaultSender)
            ->to($receiver)
            ->subject(self::TEMPLATE_SUBJECT_MAP[$template])
            ->html($this->environment->render($template, $payload))
        ;

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $exception) {
            $this->logger->error(sprintf('Error sending email: %s', $exception->getMessage()));
        }
    }
}
