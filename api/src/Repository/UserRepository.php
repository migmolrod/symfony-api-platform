<?php

namespace App\Repository;

use App\Entity\User;
use App\Exception\User\UserAlreadyActiveException;
use App\Exception\User\UserNotFoundException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class UserRepository extends BaseRepository
{
    protected static function entityClass(): string
    {
        return User::class;
    }

    public function findOneByEmailOrFail(string $email): User
    {
        if (null === $user = $this->objectRepository->findOneBy(['email' => $email])) {
            throw UserNotFoundException::fromEmail($email);
        }

        return $user;
    }

    public function findOneByIdAndTokenOrFail(string $id, string $token): User
    {
        if (null === $user = $this->objectRepository->findOneBy(['id' => $id, 'token' => $token, 'active' => false])) {
            throw UserNotFoundException::fromIdAndToken($id, $token);
        }

        return $user;
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function save(User $user): void
    {
        $this->saveEntity($user);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function remove(User $user): void
    {
        $this->removeEntity($user);
    }
}
