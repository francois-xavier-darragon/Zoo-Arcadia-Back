<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240703054558 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE image_habitat (image_id INT NOT NULL, habitat_id INT NOT NULL, INDEX IDX_AE27E5343DA5256D (image_id), INDEX IDX_AE27E534AFFE2D26 (habitat_id), PRIMARY KEY(image_id, habitat_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image_animal (image_id INT NOT NULL, animal_id INT NOT NULL, INDEX IDX_C5B67DD73DA5256D (image_id), INDEX IDX_C5B67DD78E962C16 (animal_id), PRIMARY KEY(image_id, animal_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE image_habitat ADD CONSTRAINT FK_AE27E5343DA5256D FOREIGN KEY (image_id) REFERENCES image (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE image_habitat ADD CONSTRAINT FK_AE27E534AFFE2D26 FOREIGN KEY (habitat_id) REFERENCES habitat (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE image_animal ADD CONSTRAINT FK_C5B67DD73DA5256D FOREIGN KEY (image_id) REFERENCES image (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE image_animal ADD CONSTRAINT FK_C5B67DD78E962C16 FOREIGN KEY (animal_id) REFERENCES animal (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE animal_image');
        $this->addSql('DROP TABLE habitat_image');
        $this->addSql('ALTER TABLE animal CHANGE name name VARCHAR(50) NOT NULL, CHANGE health health VARCHAR(50) NOT NULL, CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE updated_at updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE deleted_at deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE animal RENAME INDEX fk_animal_breed TO IDX_6AAB231FA8B4A30F');
        $this->addSql('ALTER TABLE animal RENAME INDEX fk_animal_habitat TO IDX_6AAB231FAFFE2D26');
        $this->addSql('ALTER TABLE breed CHANGE name name VARCHAR(50) NOT NULL, CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE updated_at updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE deleted_at deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE habitat CHANGE name name VARCHAR(50) NOT NULL, CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE updated_at updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE deleted_at deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE image CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE updated_at updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE deleted_at deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE notice CHANGE nickname nickname VARCHAR(50) NOT NULL, CHANGE comment comment VARCHAR(50) NOT NULL, CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE updated_at updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE deleted_at deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE notice RENAME INDEX fk_notice_user TO IDX_480D45C2A76ED395');
        $this->addSql('ALTER TABLE service CHANGE description description LONGTEXT DEFAULT NULL, CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE updated_at updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE deleted_at deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE user DROP INDEX FK_user_avatar, ADD UNIQUE INDEX UNIQ_8D93D64986383B10 (avatar_id)');
        $this->addSql('ALTER TABLE user ADD src VARCHAR(255) DEFAULT NULL, CHANGE email email VARCHAR(180) NOT NULL, CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE updated_at updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE deleted_at deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON user (email)');
        $this->addSql('ALTER TABLE veterinary_report CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE updated_at updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE deleted_at deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE veterinary_report RENAME INDEX fk_veterinary_report_user TO IDX_53C7E56BA76ED395');
        $this->addSql('ALTER TABLE veterinary_report RENAME INDEX fk_veterinary_report_animal TO IDX_53C7E56B8E962C16');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE animal_image (animal_id INT NOT NULL, image_id INT NOT NULL, PRIMARY KEY(animal_id, image_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE habitat_image (habitat_id INT NOT NULL, image_id INT NOT NULL, PRIMARY KEY(habitat_id, image_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE image_habitat DROP FOREIGN KEY FK_AE27E5343DA5256D');
        $this->addSql('ALTER TABLE image_habitat DROP FOREIGN KEY FK_AE27E534AFFE2D26');
        $this->addSql('ALTER TABLE image_animal DROP FOREIGN KEY FK_C5B67DD73DA5256D');
        $this->addSql('ALTER TABLE image_animal DROP FOREIGN KEY FK_C5B67DD78E962C16');
        $this->addSql('DROP TABLE image_habitat');
        $this->addSql('DROP TABLE image_animal');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE image CHANGE created_at created_at DATETIME NOT NULL, CHANGE updated_at updated_at DATETIME NOT NULL, CHANGE deleted_at deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE veterinary_report CHANGE created_at created_at DATETIME NOT NULL, CHANGE updated_at updated_at DATETIME NOT NULL, CHANGE deleted_at deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE veterinary_report RENAME INDEX idx_53c7e56ba76ed395 TO FK_veterinary_report_user');
        $this->addSql('ALTER TABLE veterinary_report RENAME INDEX idx_53c7e56b8e962c16 TO FK_veterinary_report_animal');
        $this->addSql('ALTER TABLE user DROP INDEX UNIQ_8D93D64986383B10, ADD INDEX FK_user_avatar (avatar_id)');
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_EMAIL ON user');
        $this->addSql('ALTER TABLE user DROP src, CHANGE email email VARCHAR(255) NOT NULL, CHANGE created_at created_at DATETIME NOT NULL, CHANGE updated_at updated_at DATETIME NOT NULL, CHANGE deleted_at deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE animal CHANGE name name VARCHAR(255) NOT NULL, CHANGE health health VARCHAR(255) NOT NULL, CHANGE created_at created_at DATETIME NOT NULL, CHANGE updated_at updated_at DATETIME NOT NULL, CHANGE deleted_at deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE animal RENAME INDEX idx_6aab231fa8b4a30f TO FK_animal_breed');
        $this->addSql('ALTER TABLE animal RENAME INDEX idx_6aab231faffe2d26 TO FK_animal_habitat');
        $this->addSql('ALTER TABLE notice CHANGE nickname nickname VARCHAR(255) NOT NULL, CHANGE comment comment VARCHAR(255) NOT NULL, CHANGE created_at created_at DATETIME NOT NULL, CHANGE updated_at updated_at DATETIME NOT NULL, CHANGE deleted_at deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE notice RENAME INDEX idx_480d45c2a76ed395 TO FK_notice_user');
        $this->addSql('ALTER TABLE service CHANGE description description TEXT DEFAULT NULL, CHANGE created_at created_at DATETIME NOT NULL, CHANGE updated_at updated_at DATETIME NOT NULL, CHANGE deleted_at deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE breed CHANGE name name VARCHAR(255) NOT NULL, CHANGE created_at created_at DATETIME NOT NULL, CHANGE updated_at updated_at DATETIME NOT NULL, CHANGE deleted_at deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE habitat CHANGE name name VARCHAR(255) NOT NULL, CHANGE created_at created_at DATETIME NOT NULL, CHANGE updated_at updated_at DATETIME NOT NULL, CHANGE deleted_at deleted_at DATETIME DEFAULT NULL');
    }
}
