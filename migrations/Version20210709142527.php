<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210709142527 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE discussion (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, figure_id INT NOT NULL, message VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_C0B9F90FA76ED395 (user_id), INDEX IDX_C0B9F90F5C011B5 (figure_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE figure (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, figure_group VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL, modified_at DATETIME DEFAULT NULL, INDEX IDX_2F57B37AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE picture (id INT AUTO_INCREMENT NOT NULL, figure_id INT DEFAULT NULL, link VARCHAR(255) NOT NULL, INDEX IDX_16DB4F895C011B5 (figure_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE report (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, discussion_id INT NOT NULL, figure_id INT NOT NULL, message VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_C42F7784A76ED395 (user_id), INDEX IDX_C42F77841ADED311 (discussion_id), INDEX IDX_C42F77845C011B5 (figure_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(150) NOT NULL, profile_picture_name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, token VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE video (id INT AUTO_INCREMENT NOT NULL, figure_id INT DEFAULT NULL, link VARCHAR(255) NOT NULL, INDEX IDX_7CC7DA2C5C011B5 (figure_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE discussion ADD CONSTRAINT FK_C0B9F90FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE discussion ADD CONSTRAINT FK_C0B9F90F5C011B5 FOREIGN KEY (figure_id) REFERENCES figure (id)');
        $this->addSql('ALTER TABLE figure ADD CONSTRAINT FK_2F57B37AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT FK_16DB4F895C011B5 FOREIGN KEY (figure_id) REFERENCES figure (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F77841ADED311 FOREIGN KEY (discussion_id) REFERENCES discussion (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F77845C011B5 FOREIGN KEY (figure_id) REFERENCES figure (id)');
        $this->addSql('ALTER TABLE video ADD CONSTRAINT FK_7CC7DA2C5C011B5 FOREIGN KEY (figure_id) REFERENCES figure (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F77841ADED311');
        $this->addSql('ALTER TABLE discussion DROP FOREIGN KEY FK_C0B9F90F5C011B5');
        $this->addSql('ALTER TABLE picture DROP FOREIGN KEY FK_16DB4F895C011B5');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F77845C011B5');
        $this->addSql('ALTER TABLE video DROP FOREIGN KEY FK_7CC7DA2C5C011B5');
        $this->addSql('ALTER TABLE discussion DROP FOREIGN KEY FK_C0B9F90FA76ED395');
        $this->addSql('ALTER TABLE figure DROP FOREIGN KEY FK_2F57B37AA76ED395');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F7784A76ED395');
        $this->addSql('DROP TABLE discussion');
        $this->addSql('DROP TABLE figure');
        $this->addSql('DROP TABLE picture');
        $this->addSql('DROP TABLE report');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE video');
    }
}
