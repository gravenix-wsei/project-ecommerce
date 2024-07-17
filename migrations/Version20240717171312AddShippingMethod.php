<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240717171312AddShippingMethod extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adds shipping methods';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE shipping_method (id UUID NOT NULL, name VARCHAR(255) NOT NULL, price_net DOUBLE PRECISION NOT NULL, price_gross DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7503FF2F5E237E06 ON shipping_method (name)');
        $this->addSql('COMMENT ON COLUMN shipping_method.id IS \'(DC2Type:uuid)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE shipping_method');
    }
}
