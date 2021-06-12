<?php

namespace App\Entity;

use App\Service\Utils\UidGenerator;
use DateTime;
use Symfony\Component\Security\Core\User\UserInterface;

class Activity
{
    private string $id;
    private Category $category;
    private User $owner;
    private ?Group $group;
    private float $amount;
    private ?string $filePath;
    private DateTime $createdAt;
    private DateTime $updatedAt;

    /**
     * Activity constructor.
     */
    public function __construct(Category $category, User $owner, float $amount, Group $group = null)
    {
        $this->id = UidGenerator::generateId();
        $this->category = $category;
        $this->owner = $owner;
        $this->group = $group;
        $this->amount = $amount;
        $this->filePath = null;
        $this->createdAt = new DateTime();
        $this->markAsUpdated();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function setCategory(Category $category): void
    {
        $this->category = $category;
    }

    public function getOwner(): User
    {
        return $this->owner;
    }

    public function getGroup(): ?Group
    {
        return $this->group;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    public function setFilePath(?string $filePath): void
    {
        $this->filePath = $filePath;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    public function markAsUpdated(): void
    {
        $this->updatedAt = new DateTime();
    }

    /**
     * @param UserInterface|User $user
     */
    public function isOwnedBy(User $user): bool
    {
        return $this->owner->equals($user);
    }

    public function belongsToGroup(Group $group): bool
    {
        if (null !== $this->group) {
            return $this->group->equals($group);
        }

        return false;
    }
}
