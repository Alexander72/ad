<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180620130638 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE flat (id INT AUTO_INCREMENT NOT NULL, region_id INT DEFAULT NULL, room_count INT DEFAULT NULL, floor INT DEFAULT NULL, floor_count INT DEFAULT NULL, address VARCHAR(255) NOT NULL, house_type VARCHAR(255) DEFAULT NULL, area INT NOT NULL, area_kitchen INT DEFAULT NULL, area_live INT DEFAULT NULL, lat DOUBLE PRECISION NOT NULL, lon DOUBLE PRECISION NOT NULL, unit_price INT NOT NULL, title VARCHAR(255) NOT NULL, price INT NOT NULL, type VARCHAR(255) NOT NULL, site VARCHAR(255) NOT NULL, data JSON DEFAULT NULL, url VARCHAR(255) NOT NULL, site_id INT NOT NULL, published DATETIME NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_554AAA4498260155 (region_id), UNIQUE INDEX site_id_unique (site, site_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE region (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, INDEX IDX_F62F176727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE flat ADD CONSTRAINT FK_554AAA4498260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE region ADD CONSTRAINT FK_F62F176727ACA70 FOREIGN KEY (parent_id) REFERENCES region (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE flat DROP FOREIGN KEY FK_554AAA4498260155');
        $this->addSql('ALTER TABLE region DROP FOREIGN KEY FK_F62F176727ACA70');
        $this->addSql('DROP TABLE flat');
        $this->addSql('DROP TABLE region');
    }
}
