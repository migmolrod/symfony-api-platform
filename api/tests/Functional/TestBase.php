<?php

namespace App\Tests\Functional;

use App\Repository\UserRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Exception as DoctrineDbalDriverException;
use Doctrine\DBAL\Exception as DoctrineDbalException;
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
            ->create($user);
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

    /**
     * @throws DoctrineDbalDriverException
     * @throws DoctrineDbalException
     */
    protected function getPeterId(): string
    {
        return $this->initDbConnection()->executeQuery(
            "SELECT id FROM user WHERE email = 'peter@api.com'"
        )->fetchOne();
    }

    protected function initDbConnection(): Connection
    {
        return $this->getContainer()->get('doctrine')->getConnection();
    }

    /**
     * @throws DoctrineDbalDriverException
     * @throws DoctrineDbalException
     */
    protected function getBrianId(): string
    {
        return $this->initDbConnection()->executeQuery(
            "SELECT id FROM user WHERE email = 'brian@api.com'"
        )->fetchOne();
    }

    /**
     * @throws DoctrineDbalDriverException
     * @throws DoctrineDbalException
     */
    protected function getRogerId(): string
    {
        return $this->initDbConnection()->executeQuery(
            "SELECT id FROM user WHERE email = 'roger@api.com'"
        )->fetchOne();
    }

    /**
     * @throws DoctrineDbalDriverException
     * @throws DoctrineDbalException
     */
    protected function getPeterToken(): string
    {
        return $this->initDbConnection()->executeQuery(
            "SELECT token FROM user WHERE email = 'peter@api.com'"
        )->fetchOne();
    }

    /**
     * @throws DoctrineDbalDriverException
     * @throws DoctrineDbalException
     */
    protected function getBrianToken(): string
    {
        return $this->initDbConnection()->executeQuery(
            "SELECT token FROM user WHERE email = 'brian@api.com'"
        )->fetchOne();
    }

    /**
     * @throws DoctrineDbalDriverException
     * @throws DoctrineDbalException
     */
    protected function getRogerToken(): string
    {
        return $this->initDbConnection()->executeQuery(
            "SELECT token FROM user WHERE email = 'roger@api.com'"
        )->fetchOne();
    }

    /**
     * @throws DoctrineDbalDriverException
     * @throws DoctrineDbalException
     */
    protected function getPeterGroupId()
    {
        return $this->initDbConnection()->executeQuery(
            "SELECT id FROM user_group WHERE name = 'Peter Group'"
        )->fetchOne();
    }

    /**
     * @throws DoctrineDbalDriverException
     * @throws DoctrineDbalException
     */
    protected function getBrianGroupId()
    {
        return $this->initDbConnection()->executeQuery(
            "SELECT id FROM user_group WHERE name = 'Brian Group'"
        )->fetchOne();
    }

    /**
     * @throws DoctrineDbalDriverException
     * @throws DoctrineDbalException
     */
    protected function getPeterExpenseCategoryId()
    {
        return $this->initDbConnection()->executeQuery(
            "SELECT id FROM category WHERE name = 'Peter Expense Category'"
        )->fetchOne();
    }

    /**
     * @throws DoctrineDbalDriverException
     * @throws DoctrineDbalException
     */
    protected function getPeterIncomeCategoryId()
    {
        return $this->initDbConnection()->executeQuery(
            "SELECT id FROM category WHERE name = 'Peter Income Category'"
        )->fetchOne();
    }

    /**
     * @throws DoctrineDbalDriverException
     * @throws DoctrineDbalException
     */
    protected function getPeterGroupExpenseCategoryId()
    {
        return $this->initDbConnection()->executeQuery(
            "SELECT id FROM category WHERE name = 'Peter Group Expense Category'"
        )->fetchOne();
    }

    /**
     * @throws DoctrineDbalDriverException
     * @throws DoctrineDbalException
     */
    protected function getPeterGroupIncomeCategoryId()
    {
        return $this->initDbConnection()->executeQuery(
            "SELECT id FROM category WHERE name = 'Peter Group Income Category'"
        )->fetchOne();
    }

    /**
     * @throws DoctrineDbalDriverException
     * @throws DoctrineDbalException
     */
    protected function getBrianExpenseCategoryId()
    {
        return $this->initDbConnection()->executeQuery(
            "SELECT id FROM category WHERE name = 'Brian Expense Category'"
        )->fetchOne();
    }

    /**
     * @throws DoctrineDbalDriverException
     * @throws DoctrineDbalException
     */
    protected function getBrianIncomeCategoryId()
    {
        return $this->initDbConnection()->executeQuery(
            "SELECT id FROM category WHERE name = 'Brian Income Category'"
        )->fetchOne();
    }

    /**
     * @throws DoctrineDbalDriverException
     * @throws DoctrineDbalException
     */
    protected function getBrianGroupExpenseCategoryId()
    {
        return $this->initDbConnection()->executeQuery(
            "SELECT id FROM category WHERE name = 'Brian Group Expense Category'"
        )->fetchOne();
    }

    /**
     * @throws DoctrineDbalDriverException
     * @throws DoctrineDbalException
     */
    protected function getBrianGroupIncomeCategoryId()
    {
        return $this->initDbConnection()->executeQuery(
            "SELECT id FROM category WHERE name = 'Brian Group Income Category'"
        )->fetchOne();
    }
}
