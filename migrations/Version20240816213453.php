<?php

// declare(strict_types=1);

// namespace DoctrineMigrations;

// use Doctrine\DBAL\Schema\Schema;
// use Doctrine\Migrations\AbstractMigration;

// /**
//  * Auto-generated Migration: Please modify to your needs!
//  */
// final class Version20240816213453 extends AbstractMigration
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
