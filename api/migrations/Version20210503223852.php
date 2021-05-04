<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210503223852 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates `user` table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE user (
                id CHAR(36) NOT NULL PRIMARY KEY,
                name VARCHAR(128) NOT NULL,
                email VARCHAR(128) NOT NULL,
                password VARCHAR(128) DEFAULT NULL,
                avatar VARCHAR(256) DEFAULT NULL,
                token VARCHAR(128) DEFAULT NULL,
                reset_password_token VARCHAR(128) DEFAULT NULL,
                active TINYINT(1) DEFAULT 0,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                INDEX IDX_user_email (email),
                CONSTRAINT U_user_email UNIQUE KEY (email)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE `user`');
    }
}
