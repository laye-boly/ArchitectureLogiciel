<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201113135659 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jeton DROP INDEX UNIQ_2CF647BA76ED395, ADD INDEX IDX_2CF647BA76ED395 (user_id)');
        $this->addSql('ALTER TABLE jeton ADD type VARCHAR(255) NOT NULL, DROP date_debut, DROP date_fin');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64934AF3301');
        $this->addSql('DROP INDEX UNIQ_8D93D64934AF3301 ON user');
        $this->addSql('ALTER TABLE user DROP jeton_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jeton DROP INDEX IDX_2CF647BA76ED395, ADD UNIQUE INDEX UNIQ_2CF647BA76ED395 (user_id)');
        $this->addSql('ALTER TABLE jeton ADD date_debut DATETIME NOT NULL, ADD date_fin DATETIME NOT NULL, DROP type');
        $this->addSql('ALTER TABLE user ADD jeton_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64934AF3301 FOREIGN KEY (jeton_id) REFERENCES jeton (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64934AF3301 ON user (jeton_id)');
    }
}
