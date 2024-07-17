<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240717174906AddStoreSettings extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adds basic store config';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE store_setting (id UUID NOT NULL, key VARCHAR(255) NOT NULL, value TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN store_setting.id IS \'(DC2Type:uuid)\'');
        $this->addSql('INSERT INTO store_setting VALUES(gen_random_uuid(), :key, :value)', ['key' => 'doubleOptIn', 'value' => 0]);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE store_setting');
    }
}
