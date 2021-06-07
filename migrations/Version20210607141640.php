<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210607141640 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE machine_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE operation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE order_purchase_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE piece_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE provider_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE range_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE realisation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE work_station_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE machine (id INT NOT NULL, work_station_id INT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1505DF843ADE1FE6 ON machine (work_station_id)');
        $this->addSql('CREATE TABLE operation (id INT NOT NULL, libelle VARCHAR(255) NOT NULL, time TIME(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE operation_range (operation_id INT NOT NULL, range_id INT NOT NULL, PRIMARY KEY(operation_id, range_id))');
        $this->addSql('CREATE INDEX IDX_14A089C644AC3583 ON operation_range (operation_id)');
        $this->addSql('CREATE INDEX IDX_14A089C62A82D0B1 ON operation_range (range_id)');
        $this->addSql('CREATE TABLE order_purchase (id INT NOT NULL, libelle VARCHAR(255) NOT NULL, date_delivery_predicted TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, date_delivery_real TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE piece (id INT NOT NULL, range_id INT NOT NULL, piece_id INT NOT NULL, provider_id INT NOT NULL, libelle VARCHAR(255) NOT NULL, quantity INT NOT NULL, price NUMERIC(5, 2) NOT NULL, type VARCHAR(255) NOT NULL, price_catalogue NUMERIC(5, 2) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_44CA0B232A82D0B1 ON piece (range_id)');
        $this->addSql('CREATE INDEX IDX_44CA0B23C40FCFA8 ON piece (piece_id)');
        $this->addSql('CREATE INDEX IDX_44CA0B23A53A8AA ON piece (provider_id)');
        $this->addSql('CREATE TABLE provider (id INT NOT NULL, libelle VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE range (id INT NOT NULL, user_workstation_id INT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_93875A4965AC6424 ON range (user_workstation_id)');
        $this->addSql('CREATE TABLE realisation (id INT NOT NULL, user_work_station_id INT NOT NULL, operation_id INT NOT NULL, machine_id INT NOT NULL, libelle VARCHAR(255) NOT NULL, time TIME(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_EAA5610E28027369 ON realisation (user_work_station_id)');
        $this->addSql('CREATE INDEX IDX_EAA5610E44AC3583 ON realisation (operation_id)');
        $this->addSql('CREATE INDEX IDX_EAA5610EF6B75B26 ON realisation (machine_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, work_station_id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('CREATE INDEX IDX_8D93D6493ADE1FE6 ON "user" (work_station_id)');
        $this->addSql('CREATE TABLE work_station (id INT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE machine ADD CONSTRAINT FK_1505DF843ADE1FE6 FOREIGN KEY (work_station_id) REFERENCES work_station (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE operation_range ADD CONSTRAINT FK_14A089C644AC3583 FOREIGN KEY (operation_id) REFERENCES operation (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE operation_range ADD CONSTRAINT FK_14A089C62A82D0B1 FOREIGN KEY (range_id) REFERENCES range (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE piece ADD CONSTRAINT FK_44CA0B232A82D0B1 FOREIGN KEY (range_id) REFERENCES range (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE piece ADD CONSTRAINT FK_44CA0B23C40FCFA8 FOREIGN KEY (piece_id) REFERENCES piece (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE piece ADD CONSTRAINT FK_44CA0B23A53A8AA FOREIGN KEY (provider_id) REFERENCES provider (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE range ADD CONSTRAINT FK_93875A4965AC6424 FOREIGN KEY (user_workstation_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE realisation ADD CONSTRAINT FK_EAA5610E28027369 FOREIGN KEY (user_work_station_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE realisation ADD CONSTRAINT FK_EAA5610E44AC3583 FOREIGN KEY (operation_id) REFERENCES operation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE realisation ADD CONSTRAINT FK_EAA5610EF6B75B26 FOREIGN KEY (machine_id) REFERENCES machine (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D6493ADE1FE6 FOREIGN KEY (work_station_id) REFERENCES work_station (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE realisation DROP CONSTRAINT FK_EAA5610EF6B75B26');
        $this->addSql('ALTER TABLE operation_range DROP CONSTRAINT FK_14A089C644AC3583');
        $this->addSql('ALTER TABLE realisation DROP CONSTRAINT FK_EAA5610E44AC3583');
        $this->addSql('ALTER TABLE piece DROP CONSTRAINT FK_44CA0B23C40FCFA8');
        $this->addSql('ALTER TABLE piece DROP CONSTRAINT FK_44CA0B23A53A8AA');
        $this->addSql('ALTER TABLE operation_range DROP CONSTRAINT FK_14A089C62A82D0B1');
        $this->addSql('ALTER TABLE piece DROP CONSTRAINT FK_44CA0B232A82D0B1');
        $this->addSql('ALTER TABLE range DROP CONSTRAINT FK_93875A4965AC6424');
        $this->addSql('ALTER TABLE realisation DROP CONSTRAINT FK_EAA5610E28027369');
        $this->addSql('ALTER TABLE machine DROP CONSTRAINT FK_1505DF843ADE1FE6');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D6493ADE1FE6');
        $this->addSql('DROP SEQUENCE machine_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE operation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE order_purchase_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE piece_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE provider_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE range_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE realisation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE work_station_id_seq CASCADE');
        $this->addSql('DROP TABLE machine');
        $this->addSql('DROP TABLE operation');
        $this->addSql('DROP TABLE operation_range');
        $this->addSql('DROP TABLE order_purchase');
        $this->addSql('DROP TABLE piece');
        $this->addSql('DROP TABLE provider');
        $this->addSql('DROP TABLE range');
        $this->addSql('DROP TABLE realisation');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE work_station');
    }
}
