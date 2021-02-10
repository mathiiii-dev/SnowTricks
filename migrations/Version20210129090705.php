<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210129090705 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE discussion ADD user_id_id INT NOT NULL, ADD figure_id_id INT NOT NULL, DROP user_id, DROP figure_id');
        $this->addSql('ALTER TABLE discussion ADD CONSTRAINT FK_C0B9F90F9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE discussion ADD CONSTRAINT FK_C0B9F90F6D69186E FOREIGN KEY (figure_id_id) REFERENCES figure (id)');
        $this->addSql('CREATE INDEX IDX_C0B9F90F9D86650F ON discussion (user_id_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C0B9F90F6D69186E ON discussion (figure_id_id)');
        $this->addSql('ALTER TABLE figure CHANGE user_id user_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE figure ADD CONSTRAINT FK_2F57B37A9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_2F57B37A9D86650F ON figure (user_id_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE discussion DROP FOREIGN KEY FK_C0B9F90F9D86650F');
        $this->addSql('ALTER TABLE discussion DROP FOREIGN KEY FK_C0B9F90F6D69186E');
        $this->addSql('DROP INDEX IDX_C0B9F90F9D86650F ON discussion');
        $this->addSql('DROP INDEX UNIQ_C0B9F90F6D69186E ON discussion');
        $this->addSql('ALTER TABLE discussion ADD user_id INT NOT NULL, ADD figure_id INT NOT NULL, DROP user_id_id, DROP figure_id_id');
        $this->addSql('ALTER TABLE figure DROP FOREIGN KEY FK_2F57B37A9D86650F');
        $this->addSql('DROP INDEX IDX_2F57B37A9D86650F ON figure');
        $this->addSql('ALTER TABLE figure CHANGE user_id_id user_id INT NOT NULL');
    }
}
