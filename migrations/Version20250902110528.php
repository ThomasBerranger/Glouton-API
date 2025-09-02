<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250902110528 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajout des index pour optimiser les requêtes fréquentes.';
    }

    public function up(Schema $schema): void
    {
        // Index principal : filtre par owner_id (requête la plus fréquente)
        $this->addSql('CREATE INDEX idx_product_owner ON product(owner_id)');

        // Index pour les dates d'expiration : accélère le JOIN + MIN(date)
        $this->addSql('CREATE INDEX idx_expiration_product_date ON expiration_date(product_id, date)');

        // Index pour la recherche par nom (si utilisé)
        $this->addSql('CREATE INDEX idx_product_name ON product(name)');

        // Index pour les recettes par owner_id
        $this->addSql('CREATE INDEX idx_recipe_owner ON recipe(owner_id)');

        // Index pour la liste de courses (addedToListAt NOT NULL)
        $this->addSql('CREATE INDEX idx_product_shopping_list ON product(owner_id, added_to_list_at)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX idx_product_owner ON product');
        $this->addSql('DROP INDEX idx_expiration_product_date ON expiration_date');
        $this->addSql('DROP INDEX idx_product_name ON product');
        $this->addSql('DROP INDEX idx_recipe_owner ON recipe');
        $this->addSql('DROP INDEX idx_product_shopping_list ON product');
    }
}
