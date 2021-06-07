<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210607142454 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE order_purchase_piece (order_purchase_id INT NOT NULL, piece_id INT NOT NULL, PRIMARY KEY(order_purchase_id, piece_id))');
        $this->addSql('CREATE INDEX IDX_F87158B94E3DC13F ON order_purchase_piece (order_purchase_id)');
        $this->addSql('CREATE INDEX IDX_F87158B9C40FCFA8 ON order_purchase_piece (piece_id)');
        $this->addSql('ALTER TABLE order_purchase_piece ADD CONSTRAINT FK_F87158B94E3DC13F FOREIGN KEY (order_purchase_id) REFERENCES order_purchase (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_purchase_piece ADD CONSTRAINT FK_F87158B9C40FCFA8 FOREIGN KEY (piece_id) REFERENCES piece (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE order_purchase_piece');
    }
}
