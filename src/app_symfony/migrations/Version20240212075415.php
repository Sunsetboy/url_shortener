<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240212075415 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE url_code (id SERIAL NOT NULL PRIMARY KEY, code VARCHAR(255) NOT NULL, is_used SMALLINT NOT NULL)');
        $this->addSql('CREATE TABLE url (id SERIAL NOT NULL PRIMARY KEY, code VARCHAR(255) NOT NULL, user_id INT DEFAULT NULL) ');
        $this->addSql('CREATE TABLE view (id SERIAL NOT NULL PRIMARY KEY, link_id INT NOT NULL, ts TIMESTAMP NOT NULL)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE url_code');
        $this->addSql('DROP TABLE url');
        $this->addSql('DROP TABLE view');
    }
}
