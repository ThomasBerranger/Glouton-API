
# Glouton

Secret for now.

## Tech Highlights

- GitHub CI/CD
- Doctrine Discriminator

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

#### Register

```
  POST /login
```

| Parameter  | Type     | Required | Description   |
|:-----------| :------- |----------|:--------------|
| `email`    | `string` | true     | Your email    |
| `password` | `string` | true     | Your password |

```json
{
  "email": "user@gmail.com",
  "password": "your_password"
}
```

#### Login

```
  POST /login
```

| Parameter  | Type     | Required | Description   |
|:-----------| :------- |----------|:--------------|
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


---

#### Get Product

```
  GET /api/items/${id}
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `string` | **Required**. Id of item to fetch |

#### add(num1, num2)

Takes two numbers and returns the sum.

---

## Tech Stack

**Programming Language:** PHP 8.2

**Framework:** Symfony 7.1

**Server:** Heroku

**Database:** PHP Built-in Server, Mysql 8.0.33

**Authentication and Authorization:** Heroku

**Testing:** PHPUnit

**Deployment Tools:** GitHub Actions

**Code Quality Tools:** PHPStan, PHP CS Fixer