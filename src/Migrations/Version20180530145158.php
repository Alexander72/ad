<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180530145158 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE flat CHANGE room_count room_count INT DEFAULT NULL, CHANGE floor floor INT DEFAULT NULL, CHANGE floor_count floor_count INT DEFAULT NULL, CHANGE house_type house_type VARCHAR(255) DEFAULT NULL, CHANGE area_kitchen area_kitchen INT DEFAULT NULL, CHANGE area_live area_live INT DEFAULT NULL, CHANGE data data JSON DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE flat CHANGE room_count room_count INT NOT NULL, CHANGE floor floor INT NOT NULL, CHANGE floor_count floor_count INT NOT NULL, CHANGE house_type house_type VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE area_kitchen area_kitchen INT NOT NULL, CHANGE area_live area_live INT NOT NULL, CHANGE data data JSON NOT NULL');
    }
}
