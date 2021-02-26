<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210225163555 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE picture DROP FOREIGN KEY FK_16DB4F895C7F3A37');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT FK_16DB4F895C7F3A37 FOREIGN KEY (figures_id) REFERENCES figure (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE picture DROP FOREIGN KEY FK_16DB4F895C7F3A37');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT FK_16DB4F895C7F3A37 FOREIGN KEY (figures_id) REFERENCES picture (id)');
    }
}
