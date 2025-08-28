<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250817064009 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME DEFAULT NULL, DROP status, DROP feedback');
        $this->addSql('ALTER TABLE project_value CHANGE project_id project_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project ADD status VARCHAR(20) NOT NULL, ADD feedback LONGTEXT DEFAULT NULL, DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE project_value CHANGE project_id project_id INT DEFAULT NULL');
    }
}
