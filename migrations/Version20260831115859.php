<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260831115859 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE customer (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) DEFAULT NULL, phone VARCHAR(20) NOT NULL, email VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE loyalty_trigger (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, points_earned INTEGER NOT NULL, type VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL, related_order_id INTEGER NOT NULL, CONSTRAINT FK_76AAE7EE2B1C2395 FOREIGN KEY (related_order_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_76AAE7EE2B1C2395 ON loyalty_trigger (related_order_id)');
        $this->addSql('CREATE TABLE "order" (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, country_iso VARCHAR(255) NOT NULL, order_method VARCHAR(255) DEFAULT NULL, order_type VARCHAR(255) NOT NULL, order_site VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, total_amount NUMERIC(10, 2) NOT NULL, customer_id INTEGER NOT NULL, CONSTRAINT FK_F52993989395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_F52993989395C3F3 ON "order" (customer_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE loyalty_trigger');
        $this->addSql('DROP TABLE "order"');
    }
}
