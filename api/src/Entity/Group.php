<?php

namespace App\Entity;

use App\Service\Utils\UidGenerator;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\UserInterface;

class Group
{
    private string $id;
    private string $name;
    private User $owner;
    private DateTime $createdAt;
    private DateTime $updatedAt;
    private Collection $users;
    private Collection $categories;

    public function __construct(string $name, User $owner)
    {
        $this->id = UidGenerator::generateId();
        $this->name = $name;
        $this->owner = $owner;
        $this->createdAt = new DateTime();
        $this->markAsUpdated();
        $this->users = new ArrayCollection([$owner]);
        $owner->addGroup($this);
        $this->categories = new ArrayCollection();
    }

    public function markAsUpdated(): void
    {
        $this->updatedAt = new DateTime();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getOwner(): User
    {
        return $this->owner;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): void
    {
        if ($this->users->contains($user)) {
            return;
        }

        $this->users->add($user);
    }

    public function removeUser(User $user): void
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
        }
    }

    public function hasUser(User $user): bool
    {
        return $this->users->contains($user);
    }

    /**
     * @param User|UserInterface $user
     */
    public function isOwnedBy($user): bool
    {
        return $this->owner->equals($user);
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }
}
