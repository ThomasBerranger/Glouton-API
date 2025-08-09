<?php

declare(strict_types=1);
namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Merged Migration: Create category table, add relationship to product, and make category_id NOT NULL
 */
final class Version20250809135020 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create category table, add category_id to product table with foreign key, insert default categories, and make category_id NOT NULL';
    }

    public function up(Schema $schema): void
    {
        // Create the category table
        $this->addSql(<<<'SQL'
        CREATE TABLE category (
            id INT AUTO_INCREMENT NOT NULL, 
            name VARCHAR(255) NOT NULL, 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);

        // Insert default categories
        $this->addSql(<<<'SQL'
        INSERT INTO category (id, name) VALUES
            (1, 'Fruits & Légumes'),
            (2, 'Produits Laitiers'),
            (3, 'Céréales & Féculents'),
            (4, 'Viandes & Poissons'),
            (5, 'Produits Sucrés'),
            (6, 'Boissons'),
            (7, 'Autre')
        SQL);

        // Add category_id column to product table (nullable initially)
        $this->addSql(<<<'SQL'
        ALTER TABLE product ADD category_id INT DEFAULT NULL
        SQL);

        // Update any existing products to have default category (Autre)
        $this->addSql(<<<'SQL'
        UPDATE product SET category_id = 7 WHERE category_id IS NULL
        SQL);

        // Now make the column NOT NULL
        $this->addSql(<<<'SQL'
        ALTER TABLE product CHANGE category_id category_id INT NOT NULL
        SQL);

        // Create index on category_id
        $this->addSql(<<<'SQL'
        CREATE INDEX IDX_D34A04AD12469DE2 ON product (category_id)
        SQL);

        // Add foreign key constraint
        $this->addSql(<<<'SQL'
        ALTER TABLE product 
        ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // Drop foreign key constraint
        $this->addSql(<<<'SQL'
        ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2
        SQL);

        // Drop index
        $this->addSql(<<<'SQL'
        DROP INDEX IDX_D34A04AD12469DE2 ON product
        SQL);

        // Drop category_id column
        $this->addSql(<<<'SQL'
        ALTER TABLE product DROP category_id
        SQL);

        // Drop category table
        $this->addSql(<<<'SQL'
        DROP TABLE category
        SQL);
    }
}