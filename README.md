<div align="center">
  <a href="https://glouton-1.web.app">
      <img src="https://github.com/ThomasBerranger/Glouton-Front/assets/15357887/0e3494c1-36f9-492d-be39-586d18905de7" alt="Glouton API logo" title="Glouton API" />
  </a>
</div>
<br>

### 🍏 Glouton - API Symfony 7.1

API RESTful alimentant l'application [Glouton](https://github.com/ThomasBerranger/Glouton) pour réduire le gaspillage alimentaire en aidant les utilisateurs à mieux gérer leurs aliments du quotidien.

Permet d'enregistrer les produits scannés, créer des recettes personnalisées, générer des listes de courses intelligentes et suivre les dates d'expiration pour éviter le gaspillage.

### 🛠 Stack Technique 

| Catégorie | Technologies |
|-----------|--------------|
| **Core Framework** | PHP 8.2+ (types stricts + attributs)<br>Symfony 7.1.* (framework bundle + components)<br>Doctrine ORM 3.2 (entités + migrations) |
| **Persistance & BDD** | MySQL 8.0.33<br>Doctrine ORM 3.* (discriminator)<br>Doctrine Migrations Bundle 3.3 (schéma versioning)<br>Doctrine Fixtures + Faker (jeux données) |
| **Sécurité & Auth** | Symfony Security Bundle 7.1 (JWT + Voters)<br>AccessTokenHandler custom (auth stateless) |
| **Testing & Qualité** | PHPUnit 9.5 (tests unitaires + fonctionnels)<br>PHPStan 1.12 niveau max (analyse statique)<br>PHP CS Fixer 3.66 (PSR-12 + standards) |
| **DevOps & Déploiement** | GitHub Actions (CI/CD pipeline)<br>Heroku (déploiement continu)<br>Symfony Runtime (optimisation prod) |
| **API & Serialization** | Symfony Serializer 7.1 (groupes contextuels + normalizer custom)<br>Validator Component 7.1 (validation métier)<br> |

### 🚀 Implémentations Techniques


- [GitHub CI/CD](https://github.com/ThomasBerranger/Glouton-API/blob/main/.github/workflows/symfony.yml)
Mise en place d'une intégration et d'un déploiement continu via les actions GitHub.

- [Authentification via Token](https://github.com/ThomasBerranger/Glouton-API/blob/main/src/Security/AccessTokenHandler.php)
Intégration d'un système d'authentification via le AccessTokenHandler de Symfony et une gestion des Tokens.

- [Doctrine Discriminator](https://github.com/ThomasBerranger/Glouton-API/blob/main/src/Entity/Product/Product.php)
Implémentation d'un héritage entre l'entité mère Product et les entités filles ScannedProduct et CustomProduct.

- [Tests automatisés](https://github.com/ThomasBerranger/Glouton-API/tree/main/tests) et [DataFixtures](https://github.com/ThomasBerranger/Glouton-API/blob/main/src/DataFixtures/RecipeFixtures.php)
Conception de tests unitaires et fonctionnels avec PHPUnit.
Création de fixtures ordonnées et liées via références pour les tests et utilisation du package Faker.

- [Permissions utilisateurs](https://github.com/ThomasBerranger/Glouton-API/blob/main/src/Security/Voter/ProductVoter.php)
Attribution de rôles aux utilisateurs et vérification des droits via des Voters.

- [Groupes de serialisation et validation](https://github.com/ThomasBerranger/Glouton-API/blob/main/src/Controller/ProductController.php)
Utilisation des groupes de serialisation et validation sur les propriétés des modèles.

- [Normalizers custom](https://github.com/ThomasBerranger/Glouton-API/blob/main/src/Normalizer/CategoryNormalizer.php)
Développement de normalizers spécialisés pour la dénormalisation des entités Category et des collections de Product.

- [Relations](https://github.com/ThomasBerranger/Glouton-API/blob/main/src/Entity/Recipe.php)
Liaisons des entités via les types OneToOne, ManyToOne et ManyToMany avec la persistence de données configurée et la suppression d'éléments orphelins.

- [Listeners](https://github.com/ThomasBerranger/Glouton-API/blob/main/src/EventListener/ProductListener.php)
Mise en place de Listeners Doctrine sur la création d'objets.

- [DTOs et MapRequestPayload](https://github.com/ThomasBerranger/Glouton-API/blob/main/src/DTO/RegistrationDTO.php)
Utilisation de DTOs et de l'attribut MapRequestPayload avec contextes de sérialisation pour une validation structurée des données entrantes.

- [Enum et MapQueryParameter](https://github.com/ThomasBerranger/Glouton-API/blob/main/src/Controller/ProductController.php)
Implémentation d'enums typés et utilisation de MapQueryParameter pour les paramètres de requête avec validation automatique.

- [Repository custom](https://github.com/ThomasBerranger/Glouton-API/blob/main/src/Repository/Product/ProductRepository.php)
Développement de repositories spécialisés avec méthodes de recherche avancées incluant recherche, tri et pagination.

- [Traits réutilisables](https://github.com/ThomasBerranger/Glouton-API/blob/main/src/Utils/ValidatorTrait.php)
Création de traits pour mutualiser la logique de validation et autres fonctionnalités communes entre contrôleurs.

- [Attributs PHP 8.2+](https://github.com/ThomasBerranger/Glouton-API/blob/main/src/Controller/SecurityController.php)
Usage systématique des attributs modernes pour une configuration déclarative des routes, sécurité et mapping de données.

- Prochainement

Implémentation du cache Redis

Utilisation de Messenger pour l'envoi d'emails asynchrones et la gestion des tâches en arrière-plan

### 📋 Documentation API - Endpoints Principaux
  
| Endpoint | Méthode | Description |
|----------|---------|-------------|
| **POST** `/login` | Authentification | Génération token JWT |
| **POST** `/scanned-products` | Création produit | Discriminator + validation contexte |
| **GET** `/products` | Liste avec filtres | Repository custom + enums + pagination |
| **PATCH** `/products/{id}` | Modification produit | Sérialisation groupes + validation + voters |
| **GET** `/products/shopping-list` | Liste de courses | Logique métier + filtres |
| **DELETE** `/products/{id}` | Suppression | Voters + cascade relations |
| **POST** `/recipes` | Création recette | MapRequestPayload + validation + relations |

**Exemples d'appels**

POST /login
```json
{
 "email": "user@example.com",
 "password": "password123"
}
```

POST /scanned-products
```json
{
 "name": "Coca Cola Original",
 "barcode": "54491472",
 "nutriscore": "e",
 "novagroup": 4,
 "ecoscore": "d",
 "description": "Boisson gazeuse sucrée au cola",
 "image": "https://static.openfoodfacts.org/images/products/544/914/72/front_fr.jpg",
 "finishedAt": "2024-12-10T14:30:00Z",
 "addedToListAt": "2024-12-01T08:00:00Z",
 "category": "01234567-89ab-cdef-0123-456789abcdef",
 "expirationDates": [
   {"date": "2024-12-15T10:30:00Z"},
   {"date": "2024-12-20T10:30:00Z"}
 ]
}
```

POST /recipes
```json
{
 "name": "Salade César",
 "description": "Salade fraîche avec croûtons",
 "duration": "00:15:00",
 "products": ["01234567-89ab-cdef-0123-456789abcdef", "fedcba98-7654-3210-fedc-ba9876543210"]
}
```

**Exemples de réponses**

Login successful
```json
{
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
}
```

Produit créé
```json
{
  "id": "01234567-89ab-cdef-0123-456789abcdef",
  "name": "Coca Cola",
  "scanned": true,
  "nutriscore": "e",
  "closestExpirationDate": "2024-12-15",
  "category": {"id": "uuid", "name": "Boissons"}
}
```

Liste produits
```json
[
  {
    "id": "01234567-89ab-cdef-0123-456789abcdef",
    "name": "Coca Cola",
    "closestExpirationDate": "2024-12-15",
    "scanned": true
  }
]
```
