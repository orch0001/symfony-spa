<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250410104638 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE service_status_logs ADD COLUMN content_response CLOB DEFAULT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__service_status_logs AS SELECT id, service_id, status, checked_at FROM service_status_logs
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE service_status_logs
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE service_status_logs (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, service_id INTEGER DEFAULT NULL, status VARCHAR(50) NOT NULL, checked_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
            , CONSTRAINT FK_4134D64ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO service_status_logs (id, service_id, status, checked_at) SELECT id, service_id, status, checked_at FROM __temp__service_status_logs
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__service_status_logs
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_4134D64ED5CA9E6 ON service_status_logs (service_id)
        SQL);
    }
}
