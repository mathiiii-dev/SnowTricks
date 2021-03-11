<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210311125508 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE discussion DROP INDEX UNIQ_C0B9F90F5C011B5, ADD INDEX IDX_C0B9F90F5C011B5 (figure_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE discussion DROP INDEX IDX_C0B9F90F5C011B5, ADD UNIQUE INDEX UNIQ_C0B9F90F5C011B5 (figure_id)');
    }
}
