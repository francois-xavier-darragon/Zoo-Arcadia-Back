<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241121151938 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE food_food_administration (food_id INT NOT NULL, food_administration_id INT NOT NULL, INDEX IDX_D767E7E7BA8E87C4 (food_id), INDEX IDX_D767E7E7F44C346F (food_administration_id), PRIMARY KEY(food_id, food_administration_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE food_food_administration ADD CONSTRAINT FK_D767E7E7BA8E87C4 FOREIGN KEY (food_id) REFERENCES food (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE food_food_administration ADD CONSTRAINT FK_D767E7E7F44C346F FOREIGN KEY (food_administration_id) REFERENCES food_administration (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE doctrine_migration_version');
        $this->addSql('DROP TABLE food_administration_link');
        $this->addSql('ALTER TABLE animal CHANGE name name VARCHAR(50) NOT NULL, CHANGE health health VARCHAR(50) NOT NULL, CHANGE description description LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE animal RENAME INDEX fk_animal_breed TO IDX_6AAB231FA8B4A30F');
        $this->addSql('ALTER TABLE animal RENAME INDEX fk_animal_habitat TO IDX_6AAB231FAFFE2D26');
        $this->addSql('ALTER TABLE animal RENAME INDEX fk_animal_enclosure TO IDX_6AAB231FD04FE1E5');
        $this->addSql('ALTER TABLE breed CHANGE name name VARCHAR(50) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F8AF884F5E237E06 ON breed (name)');
        $this->addSql('ALTER TABLE enclosure CHANGE description description LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE enclosure RENAME INDEX fk_enclosure_habitat TO IDX_E0F73063AFFE2D26');
        $this->addSql('ALTER TABLE food DROP FOREIGN KEY FK_food_prescribedBy');
        $this->addSql('DROP INDEX FK_food_prescribedBy ON food');
        $this->addSql('ALTER TABLE food CHANGE instructions instructions LONGTEXT DEFAULT NULL, CHANGE prescribedBy_id prescribed_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE food ADD CONSTRAINT FK_D43829F7424D1A3F FOREIGN KEY (prescribed_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D43829F7424D1A3F ON food (prescribed_by_id)');
        $this->addSql('ALTER TABLE food RENAME INDEX fk_food_animal TO IDX_D43829F78E962C16');
        $this->addSql('ALTER TABLE food_administration DROP FOREIGN KEY FK_food_administration_administeredBy');
        $this->addSql('ALTER TABLE food_administration DROP FOREIGN KEY FK_food_administration_food');
        $this->addSql('DROP INDEX FK_food_administration_food ON food_administration');
        $this->addSql('DROP INDEX FK_food_administration_administeredBy ON food_administration');
        $this->addSql('ALTER TABLE food_administration ADD administered_by_id INT DEFAULT NULL, DROP food_id, DROP administeredBy_id');
        $this->addSql('ALTER TABLE food_administration ADD CONSTRAINT FK_F18CC2D32753AB70 FOREIGN KEY (administered_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_F18CC2D32753AB70 ON food_administration (administered_by_id)');
        $this->addSql('ALTER TABLE habitat CHANGE name name VARCHAR(50) NOT NULL, CHANGE description description LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE image RENAME INDEX fk_image_animal TO IDX_C53D045F8E962C16');
        $this->addSql('ALTER TABLE image RENAME INDEX fk_image_habitat TO IDX_C53D045FAFFE2D26');
        $this->addSql('ALTER TABLE image RENAME INDEX fk_image_service TO IDX_C53D045FED5CA9E6');
        $this->addSql('ALTER TABLE image RENAME INDEX fk_image_enclosure TO IDX_C53D045FD04FE1E5');
        $this->addSql('ALTER TABLE notice CHANGE nickname nickname VARCHAR(50) NOT NULL, CHANGE comment comment VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE notice RENAME INDEX fk_notice_user TO IDX_480D45C2A76ED395');
        $this->addSql('ALTER TABLE service CHANGE description description LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE user DROP INDEX FK_user_avatar, ADD UNIQUE INDEX UNIQ_8D93D64986383B10 (avatar_id)');
        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(180) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON user (email)');
        $this->addSql('ALTER TABLE veterinary_report CHANGE detail detail LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE veterinary_report RENAME INDEX fk_veterinary_report_user TO IDX_53C7E56BA76ED395');
        $this->addSql('ALTER TABLE veterinary_report RENAME INDEX fk_veterinary_report_animal TO IDX_53C7E56B8E962C16');
        $this->addSql('ALTER TABLE messenger_messages CHANGE id id BIGINT AUTO_INCREMENT NOT NULL, CHANGE headers headers LONGTEXT NOT NULL, CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE available_at available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE delivered_at delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE doctrine_migration_version (version VARCHAR(191) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, executed_at DATETIME DEFAULT NULL, executed_time INT DEFAULT NULL, PRIMARY KEY(version)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE food_administration_link (food_administration_id INT NOT NULL, food_id INT NOT NULL, PRIMARY KEY(food_administration_id, food_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE food_food_administration DROP FOREIGN KEY FK_D767E7E7BA8E87C4');
        $this->addSql('ALTER TABLE food_food_administration DROP FOREIGN KEY FK_D767E7E7F44C346F');
        $this->addSql('DROP TABLE food_food_administration');
        $this->addSql('ALTER TABLE animal CHANGE name name VARCHAR(255) NOT NULL, CHANGE health health VARCHAR(255) NOT NULL, CHANGE description description TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE animal RENAME INDEX idx_6aab231fd04fe1e5 TO FK_animal_enclosure');
        $this->addSql('ALTER TABLE animal RENAME INDEX idx_6aab231faffe2d26 TO FK_animal_habitat');
        $this->addSql('ALTER TABLE animal RENAME INDEX idx_6aab231fa8b4a30f TO FK_animal_breed');
        $this->addSql('DROP INDEX UNIQ_F8AF884F5E237E06 ON breed');
        $this->addSql('ALTER TABLE breed CHANGE name name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE enclosure CHANGE description description TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE enclosure RENAME INDEX idx_e0f73063affe2d26 TO FK_enclosure_habitat');
        $this->addSql('ALTER TABLE food DROP FOREIGN KEY FK_D43829F7424D1A3F');
        $this->addSql('DROP INDEX IDX_D43829F7424D1A3F ON food');
        $this->addSql('ALTER TABLE food CHANGE instructions instructions TEXT DEFAULT NULL, CHANGE prescribed_by_id prescribedBy_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE food ADD CONSTRAINT FK_food_prescribedBy FOREIGN KEY (prescribedBy_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX FK_food_prescribedBy ON food (prescribedBy_id)');
        $this->addSql('ALTER TABLE food RENAME INDEX idx_d43829f78e962c16 TO FK_food_animal');
        $this->addSql('ALTER TABLE food_administration DROP FOREIGN KEY FK_F18CC2D32753AB70');
        $this->addSql('DROP INDEX IDX_F18CC2D32753AB70 ON food_administration');
        $this->addSql('ALTER TABLE food_administration ADD administeredBy_id INT DEFAULT NULL, CHANGE administered_by_id food_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE food_administration ADD CONSTRAINT FK_food_administration_administeredBy FOREIGN KEY (administeredBy_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE food_administration ADD CONSTRAINT FK_food_administration_food FOREIGN KEY (food_id) REFERENCES food (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX FK_food_administration_food ON food_administration (food_id)');
        $this->addSql('CREATE INDEX FK_food_administration_administeredBy ON food_administration (administeredBy_id)');
        $this->addSql('ALTER TABLE habitat CHANGE name name VARCHAR(255) NOT NULL, CHANGE description description TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE image RENAME INDEX idx_c53d045fed5ca9e6 TO FK_image_service');
        $this->addSql('ALTER TABLE image RENAME INDEX idx_c53d045faffe2d26 TO FK_image_habitat');
        $this->addSql('ALTER TABLE image RENAME INDEX idx_c53d045fd04fe1e5 TO FK_image_enclosure');
        $this->addSql('ALTER TABLE image RENAME INDEX idx_c53d045f8e962c16 TO FK_image_animal');
        $this->addSql('DROP INDEX IDX_75EA56E0FB7336F0 ON messenger_messages');
        $this->addSql('DROP INDEX IDX_75EA56E0E3BD61CE ON messenger_messages');
        $this->addSql('DROP INDEX IDX_75EA56E016BA31DB ON messenger_messages');
        $this->addSql('ALTER TABLE messenger_messages CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE headers headers LONGTEXT DEFAULT NULL, CHANGE created_at created_at DATETIME NOT NULL, CHANGE available_at available_at DATETIME NOT NULL, CHANGE delivered_at delivered_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE notice CHANGE nickname nickname VARCHAR(255) NOT NULL, CHANGE comment comment VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE notice RENAME INDEX idx_480d45c2a76ed395 TO FK_notice_user');
        $this->addSql('ALTER TABLE service CHANGE description description TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE user DROP INDEX UNIQ_8D93D64986383B10, ADD INDEX FK_user_avatar (avatar_id)');
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_EMAIL ON user');
        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE veterinary_report CHANGE detail detail TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE veterinary_report RENAME INDEX idx_53c7e56ba76ed395 TO FK_veterinary_report_user');
        $this->addSql('ALTER TABLE veterinary_report RENAME INDEX idx_53c7e56b8e962c16 TO FK_veterinary_report_animal');
    }
}
