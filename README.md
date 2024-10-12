
# Glouton

Secret for now.

## Tech Highlights

- GitHub CI/CD
- Doctrine Discriminator

## Roadmap

<details>
<summary>Création du projet</summary>

- [ ] Création du projet Symfony 7.1
- [ ] Déploiement sur GitHub
- [ ] Intégrer PHPUnit
- [ ] Intégrer PHPStan
- [ ] Intégrer PHP CS Fixer

</details>

<details>
<summary>Création du repository GitHub</summary>

- [ ] Premier commit du projet
- [ ] Rédaction d’une première doc
- [ ] Rédaction de la roadmap
- [ ] Création d’une CI/CD GitHub

</details>

<details>
<summary>Création du modèle User</summary>

- [ ] Implémentation de l’authentification

</details>

<details>
<summary>Création du modèle Product</summary>

- [ ] Création du modèle Product parent
- [ ] Création des modèles enfant
- [ ] Get endpoint
- [ ] Post endpoint
- [ ] Patch endpoint
- [ ] Delete endpoint

</details>

<details>
<summary>Création du modèle Product</summary>

- [ ] Création du modèle ExpirationDate
- [ ] Post endpoint
- [ ] Patch endpoint
- [ ] Delete endpoint

</details>

## API Reference

#### Get all items

```http
  GET /api/items
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `api_key` | `string` | **Required**. Your API key |

#### Get item

```http
  GET /api/items/${id}
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `string` | **Required**. Id of item to fetch |

#### add(num1, num2)

Takes two numbers and returns the sum.


## Tech Stack

**Programming Language:** PHP 8.2

**Framework:** Symfony 7.1

**Server:** Heroku

**Database:** PHP Built-in Server, Mysql 8.0.33

**Authentication and Authorization:** Heroku

**Testing:** PHPUnit

**Deployment Tools:** GitHub Actions

**Code Quality Tools:** PHPStan, PHP CS Fixer