<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240709191953CreateAdminUser extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create basic admin user';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('INSERT INTO administration_user VALUES(gen_random_uuid(), :defaultUsername, \'[]\', :hashedPassword)',
                     [
                         'defaultUsername' => 'admin',
                         'hashedPassword' => $this->getHashedPassword('password'),
                     ]);

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
    }

    private function getHashedPassword(string $plainPassword): string
    {
        // FIXME: password is correct with different salt used and generating along with hasher
        return '$2y$13$TlS5EKwdLlQR2vd7Pa2kQuGkxPGes08ionTcIgZ2qp0/2PXlAHnNu'; // hash('password')
    }
}
