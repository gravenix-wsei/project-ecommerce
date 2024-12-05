<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241205164810AddCustomerContext extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add customer context table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE customer_api_context (
          id UUID NOT NULL,
          customer_id UUID NOT NULL,
          token VARCHAR(128) NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C28DA119395C3F3 ON customer_api_context (customer_id)');
        $this->addSql('CREATE INDEX idx_customer_api_context_token ON customer_api_context (token)');
        $this->addSql('COMMENT ON COLUMN customer_api_context.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN customer_api_context.customer_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE
          customer_api_context
        ADD
          CONSTRAINT FK_C28DA119395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE customer_api_context DROP CONSTRAINT FK_C28DA119395C3F3');
        $this->addSql('DROP TABLE customer_api_context');
    }
}
