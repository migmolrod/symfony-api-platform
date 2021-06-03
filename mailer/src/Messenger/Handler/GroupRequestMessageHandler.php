<?php

namespace Mailer\Messenger\Handler;

use Mailer\Messenger\Message\GroupRequestMessage;
use Mailer\Service\Mailer\ClientRoute;
use Mailer\Service\Mailer\MailerService;
use Mailer\Templating\TwigTemplate;
use function sprintf;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class GroupRequestMessageHandler implements MessageHandlerInterface
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
    public function __invoke(GroupRequestMessage $groupRequestMessage): void
    {
        $payload = [
            'requesterName' => $groupRequestMessage->getRequesterName(),
            'groupName' => $groupRequestMessage->getGroupName(),
            'url' => sprintf(
                '%s%s?groupId=%s&userId=%s&token=%s',
                $this->host,
                ClientRoute::GROUP_REQUEST,
                $groupRequestMessage->getGroupId(),
                $groupRequestMessage->getUserId(),
                $groupRequestMessage->getToken(),
            ),
        ];

        $this->mailerService->send($groupRequestMessage->getReceiverEmail(), TwigTemplate::GROUP_REQUEST, $payload);
    }
}
