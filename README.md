# Glouton

Secret for now.

## Tech Highlights

- GitHub CI/CD

Mise en place d'une intégration et d'un déploiement continue via les actions GitHub.

- Authentification via Token

Intégration d'un système d'authentification via le AccessTokenHandler de Symfony et une gestion des Tokens.

- Doctrine Discriminator

Implémentation d'un heritage entre l'entité mère Product et les entitées filles ScannedProduct et CustomProduct.

- Tests automatisés

Concéption de tests automatisés avec PHPUnit.

- Bonus

#[MapRequestPayload] \ ?

## Tech Stack

**Programming Language:** PHP 8.2

**Framework:** Symfony 7.1

**Server:** Heroku

**Database:** PHP Built-in Server, Mysql 8.0.33

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
<summary>Rédaction des premiers tests</summary>

- [x] Rédaction des tests d'authentification
- [x] Rédaction des tests d'accès sur les endpoints de Product
- [x] Rédaction des tests sur les endpoints de Product

</details>

Rédaction des premiers tests
- [ ] Création du modèle Expiration date
- [ ] Modification des endpoints Product

## API Reference

#### Register

```
  POST /register
```

```json
{
  "email": "user@gmail.com",
  "password": "your_password"
}
```

| Parameter  | Type     | Required | Description   |
|:-----------|:---------|----------|:--------------|
| `email`    | `string` | true     | Your email    |
| `password` | `string` | true     | Your password |

#### Login

```
  POST /login
```

| Parameter  | Type     | Required | Description   |
|:-----------|:---------|----------|:--------------|
| `username` | `string` | true     | Your email    |
| `password` | `string` | true     | Your password |

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

#### Create Product

```
  POST /products
```

```json
{
  "name": "Product name",
  "description": "Product description",
  "image": "http://product-image-url",
  "finished_at": "2024-10-15 15:16:17",
  "added_to_list_at": "2024-10-14 15:16:17"
}
```

| Parameter          | Required | Description                       |
|:-------------------|----------|:----------------------------------|
| `name`             | true     | Product name                      |
| `description`      | false    | Product description               |
| `image`            | false    | Url to product online image       |
| `finished_at`      | false    | Product consumption date          |
| `added_to_list_at` | false    | Product addition date to the list |

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

#### Update Product

```
  PATCH /products/${id}
```

| Parameter | Type     | Required | Description        |
|:----------|:---------|----------|:-------------------|
| `id`      | `string` | true     | Id of item to edit |

```json
{
  "name": "Product name",
  "description": "Product description",
  "image": "http://product-image-url",
  "finished_at": "2024-10-15 15:16:17",
  "added_to_list_at": "2024-10-14 15:16:17"
}
```

| Parameter          | Required | Description                       |
|:-------------------|----------|:----------------------------------|
| `name`             | true     | Product name                      |
| `description`      | false    | Product description               |
| `image`            | false    | Url to product online image       |
| `finished_at`      | false    | Product consumption date          |
| `added_to_list_at` | false    | Product addition date to the list |

#### Delete Product

```
  DELETE /products/${id}
```

| Parameter | Type     | Required | Description          |
|:----------|:---------|----------|:---------------------|
| `id`      | `string` | true     | Id of item to delete |