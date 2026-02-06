# Real Estate API

Laravel API for managing properties and users. Includes REST endpoints for properties (protected by Laravel Sanctum), authentication (login/register), and a webhook for creating properties from external payloads.

## Requirements

- **PHP** >= 8.2
- **Composer**
- **SQLite** (default) or MySQL/PostgreSQL

## Installation

### 1. Clone and install dependencies

```bash
git clone <repository-url>
cd real-estate-api
composer install
```

### 2. Environment configuration

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` and set at least:

- **Database**: Default is SQLite. Ensure `database/database.sqlite` exists, or set `DB_CONNECTION`, `DB_DATABASE`, etc. for MySQL/PostgreSQL.
- **WEBHOOK_PROPERTY_TOKEN**: Secret token for the property webhook (see [Webhook](#webhook) below).

### 3. Database

```bash
# Run migrations
php artisan migrate
```

Optional: seed the database if you have seeders configured:

```bash
php artisan db:seed
```

### 4. Run the application

```bash
php artisan serve
```

API base URL: `http://localhost:8000` (or the URL shown by `artisan serve`). All API routes are prefixed with `/api`.

---

## API Overview

Base URL: `http://localhost:8000/api`

### Authentication (no token required)

| Method | Endpoint     | Description                    |
|--------|--------------|--------------------------------|
| POST   | `/login`     | Login; returns Sanctum token.  |
| POST   | `/register`  | Create user (no token returned).|

**Login** – `POST /api/login`

```json
{ "email": "user@example.com", "password": "secret" }
```

**Register** – `POST /api/register`

```json
{
  "name": "Jane Doe",
  "email": "jane@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

Use the token from login in the header for protected routes:

```
Authorization: Bearer <token>
```

---

### Properties (Sanctum protected)

All property endpoints require `Authorization: Bearer <token>`.

| Method   | Endpoint              | Description              |
|----------|------------------------|--------------------------|
| GET      | `/properties`          | List current user's properties. |
| GET      | `/properties/{id}`     | Show one property.       |
| POST     | `/properties`          | Create a property.        |
| PUT/PATCH| `/properties/{id}`     | Update a property.       |
| DELETE   | `/properties/{id}`     | Delete a property.       |

**Property payload** (create/update): `user_id`, `property_type` (residential | commercial | land), optional: `features` (array of strings), `price`, `taxes`, `income`, `expenditure`.

---

### Webhook

**Create property from webhook** – `POST /api/webhooks/properties`

Requires header:

```
X-Webhook-Token: <WEBHOOK_PROPERTY_TOKEN>
```

Set `WEBHOOK_PROPERTY_TOKEN` in `.env`. Payload is JSON; required: `user_id`, `property_type`. Optional: `features`, `price`, `taxes`, `income`, `expenditure`. Duplicate detection is done by hash (user + type + features + price).

---

## Project structure (high level)

- **Auth**: Login/Register controllers, Sanctum for API tokens.
- **Properties**: Single-action controllers (Index, Show, Store, Update, Destroy), PropertyService, PropertyRepository, hash-based deduplication.
- **Webhook**: Token-protected endpoint, CreatePropertyWebhookController, uses same CreatePropertyService.
- **Users**: UserService, UserRepository; used by auth and property creation.
