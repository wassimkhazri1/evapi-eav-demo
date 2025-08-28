<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250817145353 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project_value DROP FOREIGN KEY FK_E2251AAB6E62EFA');
        $this->addSql('DROP INDEX IDX_E2251AAB6E62EFA ON project_value');
        $this->addSql('ALTER TABLE project_value CHANGE attribute_id project_attribute_id INT NOT NULL');
        $this->addSql('ALTER TABLE project_value ADD CONSTRAINT FK_E2251AA9C41CA99 FOREIGN KEY (project_attribute_id) REFERENCES project_attribute (id)');
        $this->addSql('CREATE INDEX IDX_E2251AA9C41CA99 ON project_value (project_attribute_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project_value DROP FOREIGN KEY FK_E2251AA9C41CA99');
        $this->addSql('DROP INDEX IDX_E2251AA9C41CA99 ON project_value');
        $this->addSql('ALTER TABLE project_value CHANGE project_attribute_id attribute_id INT NOT NULL');
        $this->addSql('ALTER TABLE project_value ADD CONSTRAINT FK_E2251AAB6E62EFA FOREIGN KEY (attribute_id) REFERENCES project_attribute (id)');
        $this->addSql('CREATE INDEX IDX_E2251AAB6E62EFA ON project_value (attribute_id)');
    }
}
