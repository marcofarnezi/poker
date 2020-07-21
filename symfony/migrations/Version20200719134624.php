<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200719134624 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Hands';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE hands (id INT AUTO_INCREMENT NOT NULL, round_id INT DEFAULT NULL, player_id INT DEFAULT NULL, cards JSON NOT NULL COMMENT \'(DC2Type:json_array)\', INDEX IDX_662E33F8A6005CA0 (round_id), INDEX IDX_662E33F899E6F5DF (player_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rounds (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE hands ADD CONSTRAINT FK_662E33F8A6005CA0 FOREIGN KEY (round_id) REFERENCES rounds (id)');
        $this->addSql('ALTER TABLE hands ADD CONSTRAINT FK_662E33F899E6F5DF FOREIGN KEY (player_id) REFERENCES players (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hands DROP FOREIGN KEY FK_662E33F8A6005CA0');
        $this->addSql('DROP TABLE hands');
        $this->addSql('DROP TABLE rounds');
    }
}
