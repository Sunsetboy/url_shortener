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
        $this->addSql('CREATE TABLE `url_code` (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, is_used TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin');
        $this->addSql('CREATE TABLE url (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, user_id INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4  COLLATE utf8mb4_bin');
        $this->addSql('CREATE TABLE view (id INT AUTO_INCREMENT NOT NULL, link_id INT NOT NULL, ts DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE `url_code`');
        $this->addSql('DROP TABLE url');
        $this->addSql('DROP TABLE view');
    }
}
