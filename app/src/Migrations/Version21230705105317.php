<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Ramsey\Uuid\Uuid;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version21230705105317 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql("INSERT INTO order_statuses VALUES (1, 'created')");
        $this->addSql("INSERT INTO order_statuses VALUES (2, 'paid')");

        $this->addSql("INSERT INTO order_payment_statuses VALUES (1, 'created')");
        $this->addSql("INSERT INTO order_payment_statuses VALUES (2, 'done')");

        $id = Uuid::uuid7()->toString();
        $this->addSql("INSERT INTO products VALUES ('$id', 'The first product', '1000')");
        $id = Uuid::uuid7()->toString();
        $this->addSql("INSERT INTO products VALUES ('$id', 'The second product', '2000')");
        $id = Uuid::uuid7()->toString();
        $this->addSql("INSERT INTO products VALUES ('$id', 'The third product', '1055')");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
