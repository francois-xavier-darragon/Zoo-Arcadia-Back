<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240725054953 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE doctrine_migration_version');
        $this->addSql('ALTER TABLE animal CHANGE name name VARCHAR(50) NOT NULL, CHANGE health health VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE animal RENAME INDEX fk_animal_breed TO IDX_6AAB231FA8B4A30F');
        $this->addSql('ALTER TABLE animal RENAME INDEX fk_animal_habitat TO IDX_6AAB231FAFFE2D26');
        $this->addSql('ALTER TABLE animal RENAME INDEX fk_animal_enclosure TO IDX_6AAB231FD04FE1E5');
        $this->addSql('ALTER TABLE breed CHANGE name name VARCHAR(50) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F8AF884F5E237E06 ON breed (name)');
        $this->addSql('ALTER TABLE enclosure RENAME INDEX fk_enclosure_habitat TO IDX_E0F73063AFFE2D26');
        $this->addSql('ALTER TABLE habitat CHANGE name name VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE image RENAME INDEX fk_image_animal TO IDX_C53D045F8E962C16');
        $this->addSql('ALTER TABLE image RENAME INDEX fk_image_habitat TO IDX_C53D045FAFFE2D26');
        $this->addSql('ALTER TABLE image RENAME INDEX fk_image_service TO IDX_C53D045FED5CA9E6');
        $this->addSql('ALTER TABLE notice CHANGE nickname nickname VARCHAR(50) NOT NULL, CHANGE comment comment VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE notice RENAME INDEX fk_notice_user TO IDX_480D45C2A76ED395');
        $this->addSql('ALTER TABLE service CHANGE description description LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE user DROP INDEX FK_user_avatar, ADD UNIQUE INDEX UNIQ_8D93D64986383B10 (avatar_id)');
        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(180) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON user (email)');
        $this->addSql('ALTER TABLE veterinary_report RENAME INDEX fk_veterinary_report_user TO IDX_53C7E56BA76ED395');
        $this->addSql('ALTER TABLE veterinary_report RENAME INDEX fk_veterinary_report_animal TO IDX_53C7E56B8E962C16');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE doctrine_migration_version (version VARCHAR(191) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, executed_at DATETIME DEFAULT NULL, executed_time INT DEFAULT NULL, PRIMARY KEY(version)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE veterinary_report RENAME INDEX idx_53c7e56ba76ed395 TO FK_veterinary_report_user');
        $this->addSql('ALTER TABLE veterinary_report RENAME INDEX idx_53c7e56b8e962c16 TO FK_veterinary_report_animal');
        $this->addSql('ALTER TABLE notice CHANGE nickname nickname VARCHAR(255) NOT NULL, CHANGE comment comment VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE notice RENAME INDEX idx_480d45c2a76ed395 TO FK_notice_user');
        $this->addSql('ALTER TABLE service CHANGE description description TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE animal CHANGE name name VARCHAR(255) NOT NULL, CHANGE health health VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE animal RENAME INDEX idx_6aab231fa8b4a30f TO FK_animal_breed');
        $this->addSql('ALTER TABLE animal RENAME INDEX idx_6aab231faffe2d26 TO FK_animal_habitat');
        $this->addSql('ALTER TABLE animal RENAME INDEX idx_6aab231fd04fe1e5 TO FK_animal_enclosure');
        $this->addSql('ALTER TABLE image RENAME INDEX idx_c53d045faffe2d26 TO FK_image_habitat');
        $this->addSql('ALTER TABLE image RENAME INDEX idx_c53d045fed5ca9e6 TO FK_image_service');
        $this->addSql('ALTER TABLE image RENAME INDEX idx_c53d045f8e962c16 TO FK_image_animal');
        $this->addSql('ALTER TABLE user DROP INDEX UNIQ_8D93D64986383B10, ADD INDEX FK_user_avatar (avatar_id)');
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_EMAIL ON user');
        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(255) NOT NULL');
        $this->addSql('DROP INDEX UNIQ_F8AF884F5E237E06 ON breed');
        $this->addSql('ALTER TABLE breed CHANGE name name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE habitat CHANGE name name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE enclosure RENAME INDEX idx_e0f73063affe2d26 TO FK_enclosure_habitat');
    }
}
