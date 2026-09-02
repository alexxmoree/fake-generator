<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260831132623 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__loyalty_trigger AS SELECT id, points_earned, type, created_at, related_order_id FROM loyalty_trigger');
        $this->addSql('DROP TABLE loyalty_trigger');
        $this->addSql('CREATE TABLE loyalty_trigger (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, points_earned INTEGER NOT NULL, type VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, related_order_id INTEGER NOT NULL, CONSTRAINT FK_76AAE7EE2B1C2395 FOREIGN KEY (related_order_id) REFERENCES "order" (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO loyalty_trigger (id, points_earned, type, created_at, related_order_id) SELECT id, points_earned, type, created_at, related_order_id FROM __temp__loyalty_trigger');
        $this->addSql('DROP TABLE __temp__loyalty_trigger');
        $this->addSql('CREATE INDEX IDX_76AAE7EE2B1C2395 ON loyalty_trigger (related_order_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__loyalty_trigger AS SELECT id, points_earned, type, created_at, related_order_id FROM loyalty_trigger');
        $this->addSql('DROP TABLE loyalty_trigger');
        $this->addSql('CREATE TABLE loyalty_trigger (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, points_earned INTEGER NOT NULL, type VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL, related_order_id INTEGER NOT NULL, CONSTRAINT FK_76AAE7EE2B1C2395 FOREIGN KEY (related_order_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO loyalty_trigger (id, points_earned, type, created_at, related_order_id) SELECT id, points_earned, type, created_at, related_order_id FROM __temp__loyalty_trigger');
        $this->addSql('DROP TABLE __temp__loyalty_trigger');
        $this->addSql('CREATE INDEX IDX_76AAE7EE2B1C2395 ON loyalty_trigger (related_order_id)');
    }
}
