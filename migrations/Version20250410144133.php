<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250410144133 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__interruption AS SELECT id, service, start, end_date FROM interruption
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE interruption
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE interruption (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, service VARCHAR(255) NOT NULL, start DATETIME NOT NULL --(DC2Type:datetime_immutable)
            , end_date DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
            )
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO interruption (id, service, start, end_date) SELECT id, service, start, end_date FROM __temp__interruption
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__interruption
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__interruption AS SELECT id, service, start, end_date FROM interruption
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE interruption
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE interruption (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, service VARCHAR(255) NOT NULL, start DATETIME NOT NULL --(DC2Type:datetime_immutable)
            , end_date DATETIME NOT NULL --(DC2Type:datetime_immutable)
            )
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO interruption (id, service, start, end_date) SELECT id, service, start, end_date FROM __temp__interruption
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__interruption
        SQL);
    }
}
