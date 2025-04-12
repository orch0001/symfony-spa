<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250410133641 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE availability_rate_days (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, service VARCHAR(255) NOT NULL, rate DOUBLE PRECISION NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE availability_rate_hours (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, service VARCHAR(255) NOT NULL, rate DOUBLE PRECISION NOT NULL)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE availability_rate_days
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE availability_rate_hours
        SQL);
    }
}
