<?php declare(strict_types=1);

namespace Sylius\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190207095505 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE brille24_channel_special_pricing (id INT AUTO_INCREMENT NOT NULL, product_variant_id INT NOT NULL, price INT NOT NULL, channelCode VARCHAR(255) NOT NULL, startsAt DATETIME NOT NULL, endsAt DATETIME NOT NULL, INDEX IDX_1FF2651CA80EF684 (product_variant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE brille24_channel_special_pricing ADD CONSTRAINT FK_1FF2651CA80EF684 FOREIGN KEY (product_variant_id) REFERENCES sylius_product_variant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_channel DROP taxId');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE brille24_channel_special_pricing');
        $this->addSql('ALTER TABLE sylius_channel ADD taxId VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
    }
}
