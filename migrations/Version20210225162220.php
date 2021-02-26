<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210225162220 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE picture_picture (picture_source INT NOT NULL, picture_target INT NOT NULL, INDEX IDX_BFDBB5BCE6EC963 (picture_source), INDEX IDX_BFDBB5BD78B99EC (picture_target), PRIMARY KEY(picture_source, picture_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE picture_picture ADD CONSTRAINT FK_BFDBB5BCE6EC963 FOREIGN KEY (picture_source) REFERENCES picture (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE picture_picture ADD CONSTRAINT FK_BFDBB5BD78B99EC FOREIGN KEY (picture_target) REFERENCES picture (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE picture_picture');
    }
}
