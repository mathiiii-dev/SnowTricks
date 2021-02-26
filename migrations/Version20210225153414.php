<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210225153414 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE figure_picture (figure_id INT NOT NULL, picture_id INT NOT NULL, INDEX IDX_1C84F60B5C011B5 (figure_id), INDEX IDX_1C84F60BEE45BDBF (picture_id), PRIMARY KEY(figure_id, picture_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE figure_picture ADD CONSTRAINT FK_1C84F60B5C011B5 FOREIGN KEY (figure_id) REFERENCES figure (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE figure_picture ADD CONSTRAINT FK_1C84F60BEE45BDBF FOREIGN KEY (picture_id) REFERENCES picture (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE figure_picture');
    }
}
