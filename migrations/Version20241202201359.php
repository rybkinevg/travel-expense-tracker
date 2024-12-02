<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241202201359 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tet_expenses (id BIGSERIAL NOT NULL, trip_id BIGINT DEFAULT NULL, payer_id BIGINT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, description VARCHAR(255) NOT NULL, amount VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6EE7C1E9A5BC2E0E ON tet_expenses (trip_id)');
        $this->addSql('CREATE INDEX IDX_6EE7C1E9C17AD9A9 ON tet_expenses (payer_id)');
        $this->addSql('CREATE TABLE tet_expenses_debtors (expense_id BIGINT NOT NULL, debtor_id BIGINT NOT NULL, PRIMARY KEY(expense_id, debtor_id))');
        $this->addSql('CREATE INDEX IDX_85732476F395DB7B ON tet_expenses_debtors (expense_id)');
        $this->addSql('CREATE INDEX IDX_85732476B043EC6B ON tet_expenses_debtors (debtor_id)');
        $this->addSql('CREATE TABLE tet_travelers (id BIGSERIAL NOT NULL, trip_id BIGINT NOT NULL, chat_member_username VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CA97983AA5BC2E0E ON tet_travelers (trip_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CA97983AA5BC2E0E6C933FCA ON tet_travelers (trip_id, chat_member_username)');
        $this->addSql('CREATE TABLE tet_trips (id BIGSERIAL NOT NULL, is_active BOOLEAN NOT NULL, started_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, completed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, chat_id VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE tet_expenses ADD CONSTRAINT FK_6EE7C1E9A5BC2E0E FOREIGN KEY (trip_id) REFERENCES tet_trips (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tet_expenses ADD CONSTRAINT FK_6EE7C1E9C17AD9A9 FOREIGN KEY (payer_id) REFERENCES tet_travelers (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tet_expenses_debtors ADD CONSTRAINT FK_85732476F395DB7B FOREIGN KEY (expense_id) REFERENCES tet_expenses (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tet_expenses_debtors ADD CONSTRAINT FK_85732476B043EC6B FOREIGN KEY (debtor_id) REFERENCES tet_travelers (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tet_travelers ADD CONSTRAINT FK_CA97983AA5BC2E0E FOREIGN KEY (trip_id) REFERENCES tet_trips (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tet_expenses DROP CONSTRAINT FK_6EE7C1E9A5BC2E0E');
        $this->addSql('ALTER TABLE tet_expenses DROP CONSTRAINT FK_6EE7C1E9C17AD9A9');
        $this->addSql('ALTER TABLE tet_expenses_debtors DROP CONSTRAINT FK_85732476F395DB7B');
        $this->addSql('ALTER TABLE tet_expenses_debtors DROP CONSTRAINT FK_85732476B043EC6B');
        $this->addSql('ALTER TABLE tet_travelers DROP CONSTRAINT FK_CA97983AA5BC2E0E');
        $this->addSql('DROP TABLE tet_expenses');
        $this->addSql('DROP TABLE tet_expenses_debtors');
        $this->addSql('DROP TABLE tet_travelers');
        $this->addSql('DROP TABLE tet_trips');
    }
}
