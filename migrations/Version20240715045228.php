<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240715045228 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image_animal DROP FOREIGN KEY FK_C5B67DD73DA5256D');
        $this->addSql('ALTER TABLE image_animal DROP FOREIGN KEY FK_C5B67DD78E962C16');
        $this->addSql('ALTER TABLE image_habitat DROP FOREIGN KEY FK_AE27E5343DA5256D');
        $this->addSql('ALTER TABLE image_habitat DROP FOREIGN KEY FK_AE27E534AFFE2D26');
        $this->addSql('DROP TABLE image_animal');
        $this->addSql('DROP TABLE image_habitat');
        $this->addSql('ALTER TABLE image ADD animal_id INT DEFAULT NULL, ADD habitat_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F8E962C16 FOREIGN KEY (animal_id) REFERENCES animal (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FAFFE2D26 FOREIGN KEY (habitat_id) REFERENCES habitat (id)');
        $this->addSql('CREATE INDEX IDX_C53D045F8E962C16 ON image (animal_id)');
        $this->addSql('CREATE INDEX IDX_C53D045FAFFE2D26 ON image (habitat_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE image_animal (image_id INT NOT NULL, animal_id INT NOT NULL, INDEX IDX_C5B67DD78E962C16 (animal_id), INDEX IDX_C5B67DD73DA5256D (image_id), PRIMARY KEY(image_id, animal_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE image_habitat (image_id INT NOT NULL, habitat_id INT NOT NULL, INDEX IDX_AE27E534AFFE2D26 (habitat_id), INDEX IDX_AE27E5343DA5256D (image_id), PRIMARY KEY(image_id, habitat_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE image_animal ADD CONSTRAINT FK_C5B67DD73DA5256D FOREIGN KEY (image_id) REFERENCES image (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE image_animal ADD CONSTRAINT FK_C5B67DD78E962C16 FOREIGN KEY (animal_id) REFERENCES animal (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE image_habitat ADD CONSTRAINT FK_AE27E5343DA5256D FOREIGN KEY (image_id) REFERENCES image (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE image_habitat ADD CONSTRAINT FK_AE27E534AFFE2D26 FOREIGN KEY (habitat_id) REFERENCES habitat (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F8E962C16');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FAFFE2D26');
        $this->addSql('DROP INDEX IDX_C53D045F8E962C16 ON image');
        $this->addSql('DROP INDEX IDX_C53D045FAFFE2D26 ON image');
        $this->addSql('ALTER TABLE image DROP animal_id, DROP habitat_id');
    }
}
