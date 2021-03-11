<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210225164316 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE picture DROP FOREIGN KEY FK_16DB4F895C7F3A37');
        $this->addSql('DROP INDEX IDX_16DB4F895C7F3A37 ON picture');
        $this->addSql('ALTER TABLE picture CHANGE figures_id figure_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT FK_16DB4F895C011B5 FOREIGN KEY (figure_id) REFERENCES figure (id)');
        $this->addSql('CREATE INDEX IDX_16DB4F895C011B5 ON picture (figure_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE picture DROP FOREIGN KEY FK_16DB4F895C011B5');
        $this->addSql('DROP INDEX IDX_16DB4F895C011B5 ON picture');
        $this->addSql('ALTER TABLE picture CHANGE figure_id figures_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT FK_16DB4F895C7F3A37 FOREIGN KEY (figures_id) REFERENCES figure (id)');
        $this->addSql('CREATE INDEX IDX_16DB4F895C7F3A37 ON picture (figures_id)');
    }
}
