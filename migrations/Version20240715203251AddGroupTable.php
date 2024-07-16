<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240715203251AddGroupTable extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adds group table and relation with administration_user';
    }
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "group_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "group" (id UUID NOT NULL, name VARCHAR(255) NOT NULL, privileges TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN "group".id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "group".privileges IS \'(DC2Type:array)\'');
        $this->addSql('CREATE TABLE group_administration_user (group_id UUID NOT NULL, administration_user_id UUID NOT NULL, PRIMARY KEY(group_id, administration_user_id))');
        $this->addSql('CREATE INDEX IDX_D9E60898FE54D947 ON group_administration_user (group_id)');
        $this->addSql('CREATE INDEX IDX_D9E60898E059C686 ON group_administration_user (administration_user_id)');
        $this->addSql('COMMENT ON COLUMN group_administration_user.group_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN group_administration_user.administration_user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE group_administration_user ADD CONSTRAINT FK_D9E60898FE54D947 FOREIGN KEY (group_id) REFERENCES "group" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE group_administration_user ADD CONSTRAINT FK_D9E60898E059C686 FOREIGN KEY (administration_user_id) REFERENCES administration_user (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE "group_id_seq" CASCADE');
        $this->addSql('ALTER TABLE group_administration_user DROP CONSTRAINT FK_D9E60898FE54D947');
        $this->addSql('ALTER TABLE group_administration_user DROP CONSTRAINT FK_D9E60898E059C686');
        $this->addSql('DROP TABLE "group"');
        $this->addSql('DROP TABLE group_administration_user');
    }
}
