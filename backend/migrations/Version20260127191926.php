<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260127191926 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE procuct ADD created_by_id INT NOT NULL');
        $this->addSql('ALTER TABLE procuct ADD updated_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE procuct ADD deleted_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE procuct ADD CONSTRAINT FK_81200588B03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id) NOT DEFERRABLE');
        $this->addSql('ALTER TABLE procuct ADD CONSTRAINT FK_81200588896DBBDE FOREIGN KEY (updated_by_id) REFERENCES users (id) NOT DEFERRABLE');
        $this->addSql('ALTER TABLE procuct ADD CONSTRAINT FK_81200588C76F1F52 FOREIGN KEY (deleted_by_id) REFERENCES users (id) NOT DEFERRABLE');
        $this->addSql('CREATE INDEX IDX_81200588B03A8386 ON procuct (created_by_id)');
        $this->addSql('CREATE INDEX IDX_81200588896DBBDE ON procuct (updated_by_id)');
        $this->addSql('CREATE INDEX IDX_81200588C76F1F52 ON procuct (deleted_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Procuct DROP CONSTRAINT FK_81200588B03A8386');
        $this->addSql('ALTER TABLE Procuct DROP CONSTRAINT FK_81200588896DBBDE');
        $this->addSql('ALTER TABLE Procuct DROP CONSTRAINT FK_81200588C76F1F52');
        $this->addSql('DROP INDEX IDX_81200588B03A8386');
        $this->addSql('DROP INDEX IDX_81200588896DBBDE');
        $this->addSql('DROP INDEX IDX_81200588C76F1F52');
        $this->addSql('ALTER TABLE Procuct DROP created_by_id');
        $this->addSql('ALTER TABLE Procuct DROP updated_by_id');
        $this->addSql('ALTER TABLE Procuct DROP deleted_by_id');
    }
}
