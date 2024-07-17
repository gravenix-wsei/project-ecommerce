<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240717162011AddPromotion extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adds promotion entity';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE promotion (id UUID NOT NULL, name VARCHAR(255) NOT NULL, valid_since TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, valid_until TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN promotion.id IS \'(DC2Type:uuid)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE promotion');
    }
}
