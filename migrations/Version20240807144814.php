<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240807144814 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image ADD enclosure_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FD04FE1E5 FOREIGN KEY (enclosure_id) REFERENCES enclosure (id)');
        $this->addSql('CREATE INDEX IDX_C53D045FD04FE1E5 ON image (enclosure_id)');
        $this->addSql('ALTER TABLE service ADD short_description VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FD04FE1E5');
        $this->addSql('DROP INDEX IDX_C53D045FD04FE1E5 ON image');
        $this->addSql('ALTER TABLE image DROP enclosure_id');
        $this->addSql('ALTER TABLE service DROP short_description');
    }
}
