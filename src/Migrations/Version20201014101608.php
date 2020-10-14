<?php

declare(strict_types=1);

namespace Brille24\SyliusSpecialPricePlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201014101608 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE brille24_channel_special_pricing CHANGE startsAt startsAt DATETIME DEFAULT NULL, CHANGE endsAt endsAt DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE brille24_channel_special_pricing CHANGE startsAt startsAt DATETIME NOT NULL, CHANGE endsAt endsAt DATETIME NOT NULL');
    }
}
