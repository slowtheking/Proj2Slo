<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220721094723 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE photo ADD name VARCHAR(255) NOT NULL, ADD type ENUM(\'realisation\', \'ambiance\'), DROP photo_ambiance, DROP photo_rea');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE photo ADD photo_ambiance VARCHAR(255) DEFAULT NULL, ADD photo_rea VARCHAR(255) DEFAULT NULL, DROP name, DROP type');
    }
}
