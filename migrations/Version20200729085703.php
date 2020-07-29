<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200729085703 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add image_name field to pins table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE pins ADD image_name VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pins DROP image_name');
    }
}
