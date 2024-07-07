<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240707063905 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_6AAB231F5E237E06 ON animal');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F8AF884F5E237E06 ON breed (name)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_F8AF884F5E237E06 ON breed');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6AAB231F5E237E06 ON animal (name)');
    }
}
