<?php

// declare(strict_types=1);

// namespace DoctrineMigrations;

// use Doctrine\DBAL\Schema\Schema;
// use Doctrine\Migrations\AbstractMigration;

// /**
//  * Auto-generated Migration: Please modify to your needs!
//  */
// final class Version20240816135145 extends AbstractMigration
// {
//     public function getDescription(): string
//     {
//         return '';
//     }

//     public function up(Schema $schema): void
//     {
//         // this up() migration is auto-generated, please modify it to your needs
//         $this->addSql('CREATE SEQUENCE game_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
//         $this->addSql('CREATE TABLE game (id INT NOT NULL, team_home VARCHAR(255) NOT NULL, team_away VARCHAR(255) NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, ticket_price NUMERIC(10, 0) NOT NULL, stadium_capacity INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
//         $this->addSql('COMMENT ON COLUMN game.date IS \'(DC2Type:datetime_immutable)\'');
//         $this->addSql('COMMENT ON COLUMN game.created_at IS \'(DC2Type:datetime_immutable)\'');
//         $this->addSql('COMMENT ON COLUMN game.updated_at IS \'(DC2Type:datetime_immutable)\'');
//     }

//     public function down(Schema $schema): void
//     {
//         // this down() migration is auto-generated, please modify it to your needs
//         $this->addSql('CREATE SCHEMA public');
//         $this->addSql('DROP SEQUENCE game_id_seq CASCADE');
//         $this->addSql('DROP TABLE game');
//     }
// }

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240816135145 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create game table and game_id_seq sequence';
    }

    public function up(Schema $schema): void
    {
        // Check for PostgreSQL platform
        $platform = $this->connection->getDatabasePlatform();

        if ($platform->getName() === 'postgresql') {
            // Check if the sequence already exists
            $sequences = $this->connection->fetchAllAssociative(
                "SELECT relname as sequence_name FROM pg_class WHERE relkind = 'S'"
            );

            $sequenceExists = false;
            foreach ($sequences as $sequence) {
                if ($sequence['sequence_name'] === 'game_id_seq') {
                    $sequenceExists = true;
                    break;
                }
            }

            if (!$sequenceExists) {
                $this->addSql('CREATE SEQUENCE game_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
            }
        }

        // Check if the table already exists
        if (!$schema->hasTable('game')) {
            $this->addSql('CREATE TABLE game (id INT NOT NULL, team_home VARCHAR(255) NOT NULL, team_away VARCHAR(255) NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, ticket_price NUMERIC(10, 0) NOT NULL, stadium_capacity INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
            $this->addSql('COMMENT ON COLUMN game.date IS \'(DC2Type:datetime_immutable)\'');
            $this->addSql('COMMENT ON COLUMN game.created_at IS \'(DC2Type:datetime_immutable)\'');
            $this->addSql('COMMENT ON COLUMN game.updated_at IS \'(DC2Type:datetime_immutable)\'');
        }
    }

    public function down(Schema $schema): void
    {
        // Drop the sequence if it exists
        $this->addSql('DROP SEQUENCE IF EXISTS game_id_seq CASCADE');

        // Drop the table if it exists
        if ($schema->hasTable('game')) {
            $this->addSql('DROP TABLE game');
        }
    }
}