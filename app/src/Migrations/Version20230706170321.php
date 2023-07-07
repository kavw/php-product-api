<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230706170321 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE events (id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, name VARCHAR(255) NOT NULL, data JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN events.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN events.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE order_payment_statuses (id INT NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE order_payments (id UUID NOT NULL, order_id UUID NOT NULL, status_id INT NOT NULL, sum BIGINT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, paid_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_842A79838D9F6D38 ON order_payments (order_id)');
        $this->addSql('CREATE INDEX IDX_842A79836BF700BD ON order_payments (status_id)');
        $this->addSql('COMMENT ON COLUMN order_payments.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN order_payments.order_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN order_payments.sum IS \'(DC2Type:money)\'');
        $this->addSql('COMMENT ON COLUMN order_payments.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN order_payments.paid_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE order_products (id UUID NOT NULL, order_id UUID NOT NULL, product_id UUID NOT NULL, name VARCHAR(255) NOT NULL, price BIGINT NOT NULL, count INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5242B8EB8D9F6D38 ON order_products (order_id)');
        $this->addSql('COMMENT ON COLUMN order_products.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN order_products.order_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN order_products.product_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN order_products.price IS \'(DC2Type:money)\'');
        $this->addSql('CREATE TABLE order_statuses (id INT NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE orders (id UUID NOT NULL, status_id INT NOT NULL, user_id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, paid_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E52FFDEE6BF700BD ON orders (status_id)');
        $this->addSql('COMMENT ON COLUMN orders.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN orders.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN orders.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN orders.paid_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE products (id UUID NOT NULL, name VARCHAR(255) NOT NULL, price BIGINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN products.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN products.price IS \'(DC2Type:money)\'');
        $this->addSql('ALTER TABLE order_payments ADD CONSTRAINT FK_842A79838D9F6D38 FOREIGN KEY (order_id) REFERENCES orders (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_payments ADD CONSTRAINT FK_842A79836BF700BD FOREIGN KEY (status_id) REFERENCES order_payment_statuses (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_products ADD CONSTRAINT FK_5242B8EB8D9F6D38 FOREIGN KEY (order_id) REFERENCES orders (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE6BF700BD FOREIGN KEY (status_id) REFERENCES order_statuses (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

        $this->addSql('ALTER TABLE order_payments DROP CONSTRAINT FK_842A79838D9F6D38');
        $this->addSql('ALTER TABLE order_payments DROP CONSTRAINT FK_842A79836BF700BD');
        $this->addSql('ALTER TABLE order_products DROP CONSTRAINT FK_5242B8EB8D9F6D38');
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT FK_E52FFDEE6BF700BD');
        $this->addSql('DROP TABLE events');
        $this->addSql('DROP TABLE order_payment_statuses');
        $this->addSql('DROP TABLE order_payments');
        $this->addSql('DROP TABLE order_products');
        $this->addSql('DROP TABLE order_statuses');
        $this->addSql('DROP TABLE orders');
        $this->addSql('DROP TABLE products');
    }
}
