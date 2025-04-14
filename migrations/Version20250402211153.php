<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250402211153 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product ADD related_products_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADA761FF2D FOREIGN KEY (related_products_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADA761FF2D ON product (related_products_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADA761FF2D');
        $this->addSql('DROP INDEX IDX_D34A04ADA761FF2D ON product');
        $this->addSql('ALTER TABLE product DROP related_products_id');
    }
}
