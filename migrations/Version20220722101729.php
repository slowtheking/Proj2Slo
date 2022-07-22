<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220722101729 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commande_details (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commande_detail ADD article_id INT NOT NULL');
        $this->addSql('ALTER TABLE commande_detail ADD CONSTRAINT FK_2C5284467294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('CREATE INDEX IDX_2C5284467294869C ON commande_detail (article_id)');
        $this->addSql('ALTER TABLE photo CHANGE type type ENUM(\'realisation\', \'ambiance\')');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE commande_details');
        $this->addSql('ALTER TABLE commande_detail DROP FOREIGN KEY FK_2C5284467294869C');
        $this->addSql('DROP INDEX IDX_2C5284467294869C ON commande_detail');
        $this->addSql('ALTER TABLE commande_detail DROP article_id');
        $this->addSql('ALTER TABLE photo CHANGE type type VARCHAR(255) DEFAULT NULL');
    }
}
