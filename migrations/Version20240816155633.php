<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240816155633 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates the ticket table, its sequence, and sets up initial constraints and indexes.';
    }

    public function up(Schema $schema): void
    {
        // Create sequence only if it doesn't already exist
        $this->addSql('DO $$ BEGIN IF NOT EXISTS (SELECT 1 FROM pg_class WHERE relname = \'ticket_id_seq\') THEN CREATE SEQUENCE ticket_id_seq INCREMENT BY 1 MINVALUE 1 START 1; END IF; END $$;');
        
        // Create table only if it doesn't already exist
        $this->addSql('CREATE TABLE IF NOT EXISTS ticket (
            id INT NOT NULL, 
            game_id INT DEFAULT NULL, 
            buyer_id INT DEFAULT NULL, 
            purchase_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, 
            status VARCHAR(255) DEFAULT NULL, 
            PRIMARY KEY(id)
        )');
        
        // Create indexes
        $this->addSql('CREATE INDEX IF NOT EXISTS IDX_97A0ADA3E48FD905 ON ticket (game_id)');
        $this->addSql('CREATE INDEX IF NOT EXISTS IDX_97A0ADA36C755722 ON ticket (buyer_id)');
        
        // Add foreign key constraints
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT IF NOT EXISTS FK_97A0ADA3E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT IF NOT EXISTS FK_97A0ADA36C755722 FOREIGN KEY (buyer_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // Drop the table and sequence
        $this->addSql('DROP TABLE IF EXISTS ticket');
        $this->addSql('DROP SEQUENCE IF EXISTS ticket_id_seq CASCADE');
    }
}