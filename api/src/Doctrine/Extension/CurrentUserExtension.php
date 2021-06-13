<?php

namespace App\Doctrine\Extension;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Category;
use App\Entity\Group;
use App\Entity\Movement;
use App\Entity\User;
use App\Exception\Group\GroupNotFoundException;
use App\Repository\GroupRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use function in_array;
use function sprintf;

class CurrentUserExtension implements QueryCollectionExtensionInterface
{
    private const GROUP_DENIED = 'You can\'t retrieve another user groups.';
    private const USER_DENIED = 'You can\'t retrieve users of a group you don\'t belong to.';

    private TokenStorageInterface $tokenStorage;
    private GroupRepository $groupRepository;

    public function __construct(TokenStorageInterface $tokenStorage, GroupRepository $groupRepository)
    {
        $this->tokenStorage = $tokenStorage;
        $this->groupRepository = $groupRepository;
    }

    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null): void
    {
        $this->addWhere($queryBuilder, $resourceClass);
    }

    private function addWhere(QueryBuilder $queryBuilder, string $resourceClass): void
    {
        $parameterId = $queryBuilder->getParameters()->first()->getValue();

        /** @var User|null $user */
        $user = $this->tokenStorage->getToken()
            ? $this->tokenStorage->getToken()->getUser()
            : null;

        $rootAlias = $queryBuilder->getRootAliases()[0];

        if ((Group::class === $resourceClass) && $parameterId !== $user->getId()) {
            throw new AccessDeniedHttpException(self::GROUP_DENIED);
        }

        if (User::class === $resourceClass) {
            foreach ($user->getGroups() as $group) {
                if ($group->getId() === $parameterId) {
                    return;
                }
            }

            throw new AccessDeniedHttpException(self::USER_DENIED);
        }

        if (in_array($resourceClass, [Category::class, Movement::class], true)) {
            if ($this->isGroupAndUserIsMember($parameterId, $user)) {
                $queryBuilder->andWhere(sprintf('%s.group = :parameterId', $rootAlias));
                $queryBuilder->setParameter('parameterId', $parameterId);
            } else {
                $queryBuilder->andWhere(sprintf('%s.%s = :user', $rootAlias, $this->getResources()[$resourceClass]));
                $queryBuilder->andWhere(sprintf('%s.group IS NULL', $rootAlias));
                $queryBuilder->setParameter('user', $user);
            }
        }
    }

    private function isGroupAndUserIsMember(string $parameterId, User $user): bool
    {
        try {
            return $user->isMemberOfGroup($this->groupRepository->findOneByIdOrFail($parameterId));
        } catch (GroupNotFoundException $exception) {
            return false;
        }
    }

    private function getResources(): array
    {
        return [
            Category::class => 'owner',
            Movement::class => 'owner',
        ];
    }
}
