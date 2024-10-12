
# Glouton

Secret for now.


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