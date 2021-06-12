<?php

namespace App\Entity;

use App\Service\Utils\UidGenerator;
use DateTime;

class GroupRequest
{
    public const PENDING = 'pending';
    public const ACCEPTED = 'accepted';

    private string $id;
    private Group $group;
    private User $user;
    private string $token;
    private string $status;
    private ?DateTime $acceptedAt;

    public function __construct(Group $group, User $user)
    {
        $this->id = UidGenerator::generateId();
        $this->group = $group;
        $this->user = $user;
        $this->refreshToken();
        $this->status = self::PENDING;
        $this->acceptedAt = null;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getGroup(): Group
    {
        return $this->group;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getAcceptedAt(): ?DateTime
    {
        return $this->acceptedAt;
    }

    public function refreshToken(): void
    {
        $this->token = UidGenerator::generateToken();
    }

    public function markAsAccepted(): void
    {
        $this->acceptedAt = new DateTime();
        $this->status = self::ACCEPTED;
    }
}
