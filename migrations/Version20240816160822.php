<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240816160822 extends AbstractMigration
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
            purchase_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, 
            PRIMARY KEY(id)
        )');
    }

    public function down(Schema $schema): void
    {
        // Drop the table and sequence
        $this->addSql('DROP TABLE IF EXISTS ticket');
        $this->addSql('DROP SEQUENCE IF EXISTS ticket_id_seq CASCADE');
    }
}

// declare(strict_types=1);

// namespace DoctrineMigrations;

// use Doctrine\DBAL\Schema\Schema;
// use Doctrine\Migrations\AbstractMigration;

// /**
//  * Auto-generated Migration: Please modify to your needs!
//  */
// final class Version20240816160822 extends AbstractMigration
// {
//     public function getDescription(): string
//     {
//         return '';
//     }

//     public function up(Schema $schema): void
//     {
//         // this up() migration is auto-generated, please modify it to your needs
//         $this->addSql('CREATE SEQUENCE ticket_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
//         $this->addSql('CREATE TABLE ticket (id INT NOT NULL, game_id INT DEFAULT NULL, buyer_id INT DEFAULT NULL, purchase_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, status VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
//         $this->addSql('CREATE INDEX IDX_97A0ADA3E48FD905 ON ticket (game_id)');
//         $this->addSql('CREATE INDEX IDX_97A0ADA36C755722 ON ticket (buyer_id)');
//         $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
//         $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA36C755722 FOREIGN KEY (buyer_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
//     }

//     public function down(Schema $schema): void
//     {
//         // this down() migration is auto-generated, please modify it to your needs
//         $this->addSql('CREATE SCHEMA public');
//         $this->addSql('DROP SEQUENCE ticket_id_seq CASCADE');
//         $this->addSql('ALTER TABLE ticket DROP CONSTRAINT FK_97A0ADA3E48FD905');
//         $this->addSql('ALTER TABLE ticket DROP CONSTRAINT FK_97A0ADA36C755722');
//         $this->addSql('DROP TABLE ticket');
//     }
// }
