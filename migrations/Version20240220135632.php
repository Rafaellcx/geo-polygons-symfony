<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240220135632 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE municipal_geometry (id BIGSERIAL NOT NULL, name VARCHAR(255) NOT NULL, uf VARCHAR(2) NOT NULL, geom geometry(GEOMETRY, 0) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE state_geometry (id BIGSERIAL NOT NULL, name VARCHAR(255) NOT NULL, geom geometry(GEOMETRY, 0) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE user_point (id BIGSERIAL NOT NULL, municipal_id BIGINT DEFAULT NULL, latitude NUMERIC(10, 7) NOT NULL, longitude NUMERIC(10, 7) NOT NULL, geom geometry(GEOMETRY, 0) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5567087C7A79FFCF ON user_point (municipal_id)');
        $this->addSql('ALTER TABLE user_point ADD CONSTRAINT FK_5567087C7A79FFCF FOREIGN KEY (municipal_id) REFERENCES municipal_geometry (id) NOT DEFERRABLE INITIALLY IMMEDIATE');

        $this->addSql('ALTER TABLE municipal_geometry ALTER COLUMN geom TYPE geometry(Geometry, 4326) USING ST_SetSRID(geom::geometry, 4326)');
        $this->addSql('ALTER TABLE state_geometry ALTER COLUMN geom TYPE geometry(Geometry, 4326) USING ST_SetSRID(geom::geometry, 4326)');
        $this->addSql('ALTER TABLE user_point ALTER COLUMN geom TYPE geometry(Geometry, 4326) USING ST_SetSRID(geom::geometry, 4326)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_point DROP CONSTRAINT FK_5567087C7A79FFCF');
        $this->addSql('DROP TABLE municipal_geometry');
        $this->addSql('DROP TABLE state_geometry');
        $this->addSql('DROP TABLE user_point');
    }
}
