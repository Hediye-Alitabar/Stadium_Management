<?php

// declare(strict_types=1);

// namespace DoctrineMigrations;

// use Doctrine\DBAL\Schema\Schema;
// use Doctrine\Migrations\AbstractMigration;

// final class Version20240816212247 extends AbstractMigration
// {
//     public function getDescription(): string
//     {
//         return '';
//     }

//     public function up(Schema $schema): void
//     {
//         // Add buyer_id and status columns if they do not already exist
//         $this->addSql('DO $$ BEGIN
//             IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name = \'ticket\' AND column_name = \'buyer_id\') THEN
//                 ALTER TABLE ticket ADD buyer_id INT DEFAULT NULL;
//             END IF;
//         END $$;');

//         $this->addSql('DO $$ BEGIN
//             IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name = \'ticket\' AND column_name = \'status\') THEN
//                 ALTER TABLE ticket ADD status VARCHAR(255) DEFAULT NULL;
//             END IF;
//         END $$;');

//         // Drop user_id column if it exists
//         $this->addSql('DO $$ BEGIN
//             IF EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name = \'ticket\' AND column_name = \'user_id\') THEN
//                 ALTER TABLE ticket DROP COLUMN user_id;
//             END IF;
//         END $$;');

//         // Update game_id column constraints
//         $this->addSql('ALTER TABLE ticket ALTER game_id DROP NOT NULL');
//         $this->addSql('ALTER TABLE ticket ALTER purchase_date TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
//         $this->addSql('COMMENT ON COLUMN ticket.purchase_date IS NULL');

//         // Add foreign key constraint for game_id if it does not already exist
//         $this->addSql('DO $$ BEGIN
//             IF NOT EXISTS (
//                 SELECT 1 FROM information_schema.table_constraints 
//                 WHERE table_name = \'ticket\' AND constraint_name = \'fk_97a0ada3e48fd905\'
//             ) THEN
//                 ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
//             END IF;
//         END $$;');

//         // Add foreign key constraint for buyer_id if it does not already exist
//         $this->addSql('DO $$ BEGIN
//             IF NOT EXISTS (
//                 SELECT 1 FROM information_schema.table_constraints 
//                 WHERE table_name = \'ticket\' AND constraint_name = \'fk_97a0ada36c755722\'
//             ) THEN
//                 ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA36C755722 FOREIGN KEY (buyer_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
//             END IF;
//         END $$;');

//         // Create indexes if they do not already exist
//         $this->addSql('DO $$ BEGIN
//             IF NOT EXISTS (
//                 SELECT 1 FROM pg_class WHERE relname = \'IDX_97A0ADA3E48FD905\'
//             ) THEN
//                 CREATE INDEX IDX_97A0ADA3E48FD905 ON ticket (game_id);
//             END IF;
//         END $$;');

//         $this->addSql('DO $$ BEGIN
//             IF NOT EXISTS (
//                 SELECT 1 FROM pg_class WHERE relname = \'IDX_97A0ADA36C755722\'
//             ) THEN
//                 CREATE INDEX IDX_97A0ADA36C755722 ON ticket (buyer_id);
//             END IF;
//         END $$;');
//     }

//     public function down(Schema $schema): void
//     {
//         // Drop constraints and indexes
//         $this->addSql('ALTER TABLE ticket DROP CONSTRAINT IF EXISTS FK_97A0ADA3E48FD905');
//         $this->addSql('ALTER TABLE ticket DROP CONSTRAINT IF EXISTS FK_97A0ADA36C755722');
//         $this->addSql('DROP INDEX IF EXISTS IDX_97A0ADA3E48FD905');
//         $this->addSql('DROP INDEX IF EXISTS IDX_97A0ADA36C755722');

//         // Drop buyer_id and status columns
//         $this->addSql('ALTER TABLE ticket DROP COLUMN IF EXISTS buyer_id');
//         $this->addSql('ALTER TABLE ticket DROP COLUMN IF EXISTS status');

//         // Restore user_id column
//         $this->addSql('ALTER TABLE ticket ADD user_id INT NOT NULL');

//         // Revert game_id column constraints
//         $this->addSql('ALTER TABLE ticket ALTER game_id SET NOT NULL');
//         $this->addSql('ALTER TABLE ticket ALTER purchase_date TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
//         $this->addSql('COMMENT ON COLUMN ticket.purchase_date IS \'(DC2Type:datetime_immutable)\'');
//     }
// }
// declare(strict_types=1);

// namespace DoctrineMigrations;

// use Doctrine\DBAL\Schema\Schema;
// use Doctrine\Migrations\AbstractMigration;

// /**
//  * Auto-generated Migration: Please modify to your needs!
//  */
// final class Version20240816212247 extends AbstractMigration
// {
//     public function getDescription(): string
//     {
//         return '';
//     }

//     public function up(Schema $schema): void
//     {
//         // this up() migration is auto-generated, please modify it to your needs
//         $this->addSql('ALTER TABLE ticket ADD buyer_id INT DEFAULT NULL');
//         $this->addSql('ALTER TABLE ticket ADD status VARCHAR(255) DEFAULT NULL');
//         $this->addSql('ALTER TABLE ticket DROP user_id');
//         $this->addSql('ALTER TABLE ticket ALTER game_id DROP NOT NULL');
//         $this->addSql('ALTER TABLE ticket ALTER purchase_date TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
//         $this->addSql('COMMENT ON COLUMN ticket.purchase_date IS NULL');
//         $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
//         $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA36C755722 FOREIGN KEY (buyer_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
//         $this->addSql('CREATE INDEX IDX_97A0ADA3E48FD905 ON ticket (game_id)');
//         $this->addSql('CREATE INDEX IDX_97A0ADA36C755722 ON ticket (buyer_id)');
//     }

//     public function down(Schema $schema): void
//     {
//         // this down() migration is auto-generated, please modify it to your needs
//         $this->addSql('CREATE SCHEMA public');
//         $this->addSql('ALTER TABLE ticket DROP CONSTRAINT FK_97A0ADA3E48FD905');
//         $this->addSql('ALTER TABLE ticket DROP CONSTRAINT FK_97A0ADA36C755722');
//         $this->addSql('DROP INDEX IDX_97A0ADA3E48FD905');
//         $this->addSql('DROP INDEX IDX_97A0ADA36C755722');
//         $this->addSql('ALTER TABLE ticket ADD user_id INT NOT NULL');
//         $this->addSql('ALTER TABLE ticket DROP buyer_id');
//         $this->addSql('ALTER TABLE ticket DROP status');
//         $this->addSql('ALTER TABLE ticket ALTER game_id SET NOT NULL');
//         $this->addSql('ALTER TABLE ticket ALTER purchase_date TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
//         $this->addSql('COMMENT ON COLUMN ticket.purchase_date IS \'(DC2Type:datetime_immutable)\'');
//     }
// }
