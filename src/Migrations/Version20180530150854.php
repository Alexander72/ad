<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180530150854 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE flat ADD region_id INT DEFAULT NULL, DROP region');
        $this->addSql('ALTER TABLE flat ADD CONSTRAINT FK_554AAA4498260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('CREATE INDEX IDX_554AAA4498260155 ON flat (region_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE flat DROP FOREIGN KEY FK_554AAA4498260155');
        $this->addSql('DROP INDEX IDX_554AAA4498260155 ON flat');
        $this->addSql('ALTER TABLE flat ADD region VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, DROP region_id');
    }
}
