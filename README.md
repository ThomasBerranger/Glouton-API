# Glouton

Secret for now.

## Tech Highlights

- [GitHub CI/CD](https://github.com/ThomasBerranger/Glouton-Back/blob/main/.github/workflows/symfony.yml)

Mise en place d'une intégration et d'un déploiement continue via les actions GitHub.

- [Authentification via Token](https://github.com/ThomasBerranger/Glouton-Back/blob/main/src/Security/AccessTokenHandler.php)

Intégration d'un système d'authentification via le AccessTokenHandler de Symfony et une gestion des Tokens.

- [Doctrine Discriminator](https://github.com/ThomasBerranger/Glouton-Back/blob/main/src/Entity/Product/Product.php)

Implémentation d'un heritage entre l'entité mère Product et les entitées filles ScannedProduct et CustomProduct.

- [Tests automatisés](https://github.com/ThomasBerranger/Glouton-Back/tree/main/tests)

Concéption de tests unitaires et fonctionnels avec PHPUnit.

- [Permissions utilisateurs](https://github.com/ThomasBerranger/Glouton-Back/blob/main/src/Security/ProductVoter.php)

Attribution de rôles aux utilisateurs et vérification des droits via des Voters.

- [Groupes de serialization et validation](https://github.com/ThomasBerranger/Glouton-Back/blob/main/src/Controller/ProductController.php)

Utilisation des groupes de serialization et validation sur les propriétés des modèles.

- [Mise en place de relations](https://github.com/ThomasBerranger/Glouton-Back/blob/main/src/Entity/Recipe.php)

Liaisons des entités via les types OneToOne, ManyToOne et ManyToMany avec la persistence configurée et la suppression d'éléments orphelins. 

- [Création de Fixtures](https://github.com/ThomasBerranger/Glouton-Back/blob/main/src/DataFixtures/RecipeFixtures.php)

Création de fixtures ordonnées et liées via références pour les tests.

- Mise en place de Listeners

ex: ... sur la création et l'update de chaque entité concernée ?

- Utilisation de Messenger

ex: ... gestion de mail en asynchrone - envoie de mail ?

## Tech Stack

**Programming Language:** PHP 8.2

**Framework:** Symfony 7.1

**Server:** PHP Built-in Server, Heroku

**Database:** Mysql 8.0.33

**Testing:** PHPUnit 9.5

**Deployment Tools:** GitHub Actions

**Code Quality Tools:** PHPStan, PHP CS Fixer

## Roadmap

<details>
<summary>Création du projet</summary>

- [x] Création du projet Symfony 7.1
- [x] Déploiement sur GitHub
- [x] Intégrer PHPUnit
- [x] Intégrer PHPStan
- [x] Intégrer PHP CS Fixer

</details>

<details>
<summary>Création du repository GitHub</summary>

- [x] Premier commit du projet
- [x] Rédaction d’une première doc
- [x] Rédaction de la roadmap
- [x] Création d’une CI/CD GitHub

</details>

<details>
<summary>Création du modèle User</summary>

- [x] Création du modèle User
- [x] Implémentation de l’authentification

</details>

<details>
<summary>Création du modèle Product</summary>

- [x] Création du modèle Product parent
- [x] Création des modèles enfant
- [x] Get endpoint
- [x] Post endpoint
- [x] Patch endpoint
- [x] Delete endpoint

</details>

<details>
<summary>Rédaction des premiers tests unitaires et fonctionnels</summary>

- [x] Rédaction des tests d'authentification
- [x] Rédaction des tests d'accès sur les endpoints de Product
- [x] Rédaction des tests de serialization sur Product
- [x] Rédaction des tests de validation sur Product

</details>

- [ ] Création du modèle Recipe
- [ ] Création des endpoints
- [ ] Création des tests

## API Reference

#### Register

```
  POST /login
```

| Parameter  | Type     | Required | Description   |
|:-----------|:---------|----------|:--------------|
| `email`    | `string` | **true** | Your email    |
| `password` | `string` | **true** | Your password |

```json
{
  "email": "user@gmail.com",
  "password": "your_password"
}
```

#### Login

```
  POST /register
```

| Parameter  | Type     | Required | Description   |
|:-----------|:---------|----------|:--------------|
| `username` | `string` | **true** | Your email    |
| `password` | `string` | **true** | Your password |

```json
{
  "username": "user@gmail.com",
  "password": "your_password"
}
```

#### Logout

```
  POST /logout
```

#### Create Scanned Product

```
  POST /scanned-products
```

```json
{
  "name": "Product name",
  "description": "Product description",
  "image": "http://product-image-url",
  "finishedAt": "01/01/2025 15:16:17",
  "addedToListAt": "02/01/2025",
  "barcode": "123",
  "nutriscore": "A",
  "novagroup": 2,
  "ecoscore": 3,
  "expirationDates": [
    {
      "date": "01/01/2025"
    },
    {
      "date": "01/01/2025"
    }
  ]
}
```

| Parameter          | Required | Type     | Description                       |
|:-------------------|----------|----------|:----------------------------------|
| `name`             | **true** | string   | Product name                      |
| `description`      | false    | string   | Product description               |
| `image`            | false    | string   | Url to product online image       |
| `finished_at`      | false    | datetime | Product consumption date          |
| `added_to_list_at` | false    | datetime | Product addition date to the list |
| `barcode`          | **true** | string   | Product barcode scanned           |
| `nutriscore`       | false    | string   | Product nutriscore                |
| `novagroup`        | false    | integer  | Product novagroup                 |
| `ecoscore`         | false    | integer  | Product ecoscore                  |
| `expirationDates`  | false    | array    | Product expiration dates          |

#### Create Custom Product

```
  POST /custom-products
```

```json
{
  "name": "Product name",
  "description": "Product description",
  "image": "http://product-image-url",
  "finished_at": "2024-10-15 15:16:17",
  "added_to_list_at": "2024-10-14 15:16:17",
  "expirationDates": [
    {
      "date": "01/01/2025"
    }
  ]
}
```

| Parameter          | Required | Type     | Description                       |
|:-------------------|----------|----------|:----------------------------------|
| `name`             | **true** | string   | Product name                      |
| `description`      | false    | string   | Product description               |
| `image`            | false    | string   | Url to product online image       |
| `finished_at`      | false    | datetime | Product consumption date          |
| `added_to_list_at` | false    | datetime | Product addition date to the list |
| `expirationDates`  | false    | array    | Product expiration dates          |

#### Show Product list

```
  GET /products
```

#### Show Product

```
  GET /products/${id}
```

| Parameter | Type     | Required | Description         |
|:----------|:---------|----------|:--------------------|
| `id`      | `string` | true     | Id of item to fetch |

#### Update Scanned Product

```
  PATCH /scanned-products
```

```json
{
  "name": "New product name",
  "description": "New product description",
  "image": "http://new-product-image-url",
  "finishedAt": "01/01/2025 15:16:17",
  "addedToListAt": "02/01/2025",
  "barcode": "123",
  "nutriscore": "A",
  "novagroup": 2,
  "ecoscore": 3,
  "expirationDates": []
}
```

| Parameter          | Required | Type     | Description                       |
|:-------------------|----------|----------|:----------------------------------|
| `name`             | false    | string   | Product name                      |
| `description`      | false    | string   | Product description               |
| `image`            | false    | string   | Url to product online image       |
| `finished_at`      | false    | datetime | Product consumption date          |
| `added_to_list_at` | false    | datetime | Product addition date to the list |
| `barcode`          | false    | string   | Product barcode scanned           |
| `nutriscore`       | false    | string   | Product nutriscore                |
| `novagroup`        | false    | integer  | Product novagroup                 |
| `ecoscore`         | false    | integer  | Product ecoscore                  |
| `expirationDates`  | false    | array    | Product expiration dates          |

#### Update Custom Product

```
  PATCH /custom-products
```

```json
{
  "name": "New product name",
  "description": "New product description",
  "image": "http://new-product-image-url",
  "finished_at": "2024-10-15 15:16:17",
  "added_to_list_at": "2024-10-14 15:16:17",
  "expirationDates": [
    {
      "date": "01/01/2025"
    }
  ]
}
```

| Parameter          | Required | Type     | Description                       |
|:-------------------|----------|----------|:----------------------------------|
| `name`             | false    | string   | Product name                      |
| `description`      | false    | string   | Product description               |
| `image`            | false    | string   | Url to product online image       |
| `finished_at`      | false    | datetime | Product consumption date          |
| `added_to_list_at` | false    | datetime | Product addition date to the list |
| `expirationDates`  | false    | array    | Product expiration dates          |

#### Delete Product

```
  DELETE /products/${id}
```

| Parameter | Type     | Required | Description          |
|:----------|:---------|----------|:---------------------|
| `id`      | `string` | true     | Id of item to delete |