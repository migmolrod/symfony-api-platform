<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210602204941 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates `user_group_request` table and its relationships';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE user_group_request (
                id CHAR(36) NOT NULL PRIMARY KEY,
                group_id CHAR(36) NOT NULL,
                user_id CHAR(36) NOT NULL,
                token VARCHAR(128) NOT NULL,
                status VARCHAR(16) NOT NULL,
                accepted_at DATETIME DEFAULT NULL,
                INDEX IDX_user_group_request_group_id (group_id),
                INDEX IDX_user_group_request_user_id (user_id),
                CONSTRAINT FK_user_group_request_group_id FOREIGN KEY (group_id) REFERENCES `user_group` (id) ON UPDATE CASCADE ON DELETE CASCADE,
                CONSTRAINT FK_user_group_request_user_id FOREIGN KEY (user_id) REFERENCES `user` (id) ON UPDATE CASCADE ON DELETE CASCADE,
                CONSTRAINT U_user_group_request UNIQUE KEY (group_id, user_id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE `user_group_request`');
    }
}
