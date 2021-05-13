<?php

namespace App\Tests\Functional;

use App\Repository\UserRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;
use function json_decode;
use JsonException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use function sprintf;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TestBase extends WebTestCase
{
    use FixturesTrait;
    use RecreateDatabaseTrait;

    protected static ?KernelBrowser $client = null;
    protected static ?KernelBrowser $peter = null;
    protected static ?KernelBrowser $brian = null;
    protected static ?KernelBrowser $roger = null;

    protected function setUp(): void
    {
        if (null === self::$client) {
            self::$client = static::createClient();
            self::$client->setServerParameters([
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/ld+json',
            ]);
        }

        if (null === self::$peter) {
            self::$peter = clone self::$client;
            $this->createAuthenticatedUser(self::$peter, 'peter@api.com');
        }
        if (null === self::$brian) {
            self::$brian = clone self::$client;
            $this->createAuthenticatedUser(self::$brian, 'brian@api.com');
        }
        if (null === self::$roger) {
            self::$roger = clone self::$client;
            $this->createAuthenticatedUser(self::$roger, 'roger@api.com');
        }
    }

    private function createAuthenticatedUser(KernelBrowser $client, string $email): void
    {
        $user = $this->getContainer()->get(UserRepository::class)->findOneByEmailOrFail($email);
        $token = $this
            ->getContainer()
            ->get(JWTTokenManagerInterface::class)
            ->create($user)
        ;
        $client->setServerParameters([
            'HTTP_Authorization' => sprintf('Bearer %s', $token),
            'CONTENT_TYPE' => 'application/json',
            'HTTP_ACCEPT' => 'application/ld+json',
        ]);
    }

    /**
     * @throws JsonException
     */
    protected function getResponseData(Response $response): array
    {
        return json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
    }

    protected function initDbConnection(): Connection
    {
        return $this->getContainer()->get('doctrine')->getConnection();
    }

    /**
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws Exception
     */
    protected function getPeterId()
    {
        return $this->initDbConnection()->executeQuery(
            "SELECT id FROM user WHERE email = 'peter@api.com'"
        )->fetchOne();
    }

    /**
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws Exception
     */
    protected function getBrianId()
    {
        return $this->initDbConnection()->executeQuery(
            "SELECT id FROM user WHERE email = 'brian@api.com'"
        )->fetchOne();
    }

    /**
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws Exception
     */
    protected function getRogerId()
    {
        return $this->initDbConnection()->executeQuery(
            "SELECT id FROM user WHERE email = 'roger@api.com'"
        )->fetchOne();
    }
}
