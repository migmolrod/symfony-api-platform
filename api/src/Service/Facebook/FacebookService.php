<?php

namespace App\Service\Facebook;

use App\Entity\User;
use App\Http\FacebookClient;
use App\Repository\UserRepository;
use App\Service\Password\EncoderService;
use App\Service\Utils\UidGenerator;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use Facebook\Exceptions\FacebookSDKException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use function sprintf;

class FacebookService
{
    private const ENDPOINT = '/me?fields=name,email';

    private FacebookClient $facebookClient;
    private UserRepository $userRepository;
    private EncoderService $encoderService;
    private JWTTokenManagerInterface $JWTTokenManager;

    public function __construct(FacebookClient $facebookClient, UserRepository $userRepository, EncoderService $encoderService, JWTTokenManagerInterface $JWTTokenManager)
    {
        $this->facebookClient = $facebookClient;
        $this->userRepository = $userRepository;
        $this->encoderService = $encoderService;
        $this->JWTTokenManager = $JWTTokenManager;
    }

    /**
     * @throws FacebookSDKException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function authorize(string $accessToken): string
    {
        try {
            $response = $this->facebookClient->get(self::ENDPOINT, $accessToken);
        } catch (Exception $exception) {
            throw new BadRequestHttpException(sprintf('Facebook error. Details: %s', $exception->getMessage()));
        }

        $graphUser = $response->getGraphUser();
        if (null === $email = $graphUser->getEmail()) {
            throw new BadRequestHttpException('Facebook error. Details: that facebook account has no email');
        }

        try {
            $user = $this->userRepository->findOneByEmailOrFail($email);
        } catch (Exception $exception) {
            $user = $this->createUserFromFacebookData($graphUser->getName(), $email);
        }

        return $this->JWTTokenManager->create($user);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function createUserFromFacebookData(string $name, string $email): User
    {
        $user = new User($name, $email);
        $user->setPassword($this->encoderService->generateEncodedPassword($user, UidGenerator::generateUid()));
        $user->setActive(true);
        $user->setToken(null);

        $this->userRepository->save($user);

        return $user;
    }
}
