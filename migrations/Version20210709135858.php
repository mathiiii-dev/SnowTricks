<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210709135858 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE picture (id INT AUTO_INCREMENT NOT NULL, figure_id INT DEFAULT NULL, link VARCHAR(255) NOT NULL, INDEX IDX_16DB4F895C011B5 (figure_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE report (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, discussion_id INT NOT NULL, figure_id INT NOT NULL, message VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_C42F7784A76ED395 (user_id), INDEX IDX_C42F77841ADED311 (discussion_id), INDEX IDX_C42F77845C011B5 (figure_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE video (id INT AUTO_INCREMENT NOT NULL, figure_id INT DEFAULT NULL, link VARCHAR(255) NOT NULL, INDEX IDX_7CC7DA2C5C011B5 (figure_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT FK_16DB4F895C011B5 FOREIGN KEY (figure_id) REFERENCES figure (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F77841ADED311 FOREIGN KEY (discussion_id) REFERENCES discussion (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F77845C011B5 FOREIGN KEY (figure_id) REFERENCES figure (id)');
        $this->addSql('ALTER TABLE video ADD CONSTRAINT FK_7CC7DA2C5C011B5 FOREIGN KEY (figure_id) REFERENCES figure (id)');
        $this->addSql('ALTER TABLE discussion DROP INDEX UNIQ_C0B9F90F5C011B5, ADD INDEX IDX_C0B9F90F5C011B5 (figure_id)');
        $this->addSql('ALTER TABLE figure DROP pictures, DROP videos');
        $this->addSql('ALTER TABLE user ADD profile_picture_name VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE picture');
        $this->addSql('DROP TABLE report');
        $this->addSql('DROP TABLE video');
        $this->addSql('ALTER TABLE discussion DROP INDEX IDX_C0B9F90F5C011B5, ADD UNIQUE INDEX UNIQ_C0B9F90F5C011B5 (figure_id)');
        $this->addSql('ALTER TABLE figure ADD pictures LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\', ADD videos LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE user DROP profile_picture_name');
    }
}
