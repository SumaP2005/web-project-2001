# Bosnian Thrift Shop

Bosnian Thrift Shop is a second-hand marketplace prototype. The frontend is static and browser-only. The PHP backend provides a MySQL data-access layer, business services, and a FlightPHP JSON API; the frontend is not connected to the API.

## Run locally

With this repository in XAMPP's `htdocs` folder, start Apache and open:

`http://localhost/web_project/frontend/index.html`

You can also open `frontend/index.html` directly in a browser. Account details are stored only in the current browser, and item listings and reviews are not submitted to the backend.

## Project structure

- `frontend/index.html` - application shell and navigation
- `frontend/views/` - shop, account, dashboard, and other routed pages
- `frontend/assets/` and `frontend/js/` - styles, images, and browser scripts
- `backend/` - PHP database configuration, DAO, services, and FlightPHP API
- `database/web_project.sql` - MySQL schema and sample seed data
- `docs/AI_LOG.md` - AI usage notes for Milestones 1, 2, and 3

## Milestone 2: Database and DAOs

The MySQL schema and sample data are in [`database/web_project.sql`](database/web_project.sql). Import this script into a local MySQL development instance to create and seed the `web_project` database. **The script begins by dropping the existing `web_project` database; back up any data you need before importing it.**

Set database connection values in `backend/config/config.php` or the `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, and `DB_PASSWORD` environment variables. The local configuration file is ignored by Git; copy [`backend/config/config.example.php`](backend/config/config.example.php) as a starting point and adjust it for your MySQL setup. The configured database account must have access to `web_project`.

The DAOs in `backend/rest/dao/` provide prepared-statement CRUD operations for users, categories, items, item images, orders, and reviews. Each entity DAO extends `BaseDao` directly and communicates with the database only; HTTP endpoints and application validation belong outside this layer.

After importing the schema and configuring the connection, run the integration test from the repository root:

```sh
php backend/rest/dao/test.php
```

The script prints the return values from the six DAOs' CRUD and entity-specific read methods, then removes the linked temporary records. Password fields are redacted in printed results. It is an output example, not an assertion-based test. Database errors are reported by PHP/PDO rather than hidden.

## Milestone 3: Business Logic Services

The classes in `backend/rest/services/` add application validation and reusable business operations for users, categories, items, item images, orders, and reviews. Each service extends `BaseService` and is responsible for calling its DAO. Services validate required fields, formats, lengths, roles, prices, ratings, and entity relationships, and translate duplicate or missing records into the shared HTTP exceptions.

FlightPHP is installed with Composer. From the repository root, install backend dependencies with:

```sh
composer install --working-dir=backend
```

The API is served from `backend/index.php`. Route declarations are split into `backend/rest/routes/UserRoutes.php`, `CategoryRoutes.php`, `ItemRoutes.php`, `ItemImageRoutes.php`, `OrderRoutes.php`, and `ReviewRoutes.php`. They expose JSON CRUD endpoints under `/users`, `/categories`, `/items`, `/item-images`, `/orders`, and `/reviews` without an `/api` prefix. Each collection supports `GET` and `POST`; each `/{id}` supports `GET`, `PUT`, `PATCH`, and `DELETE`. Entity-specific filters are available for item seller/category, images by item, orders by buyer, reviews by item, and users by email. Successful reads, updates, and deletes return `200`, creates return `201`, validation errors return `400`, authentication/authorization exceptions return `401`/`403`, missing records and endpoints return `404`, and unexpected errors return `500`, all as JSON. Unsupported methods return JSON `405`. API requests that create or update data must send a JSON object in the request body.

The exchange-rate endpoint converts a BAM amount to another currency using live rates:

```text
GET /exchange-rates/convert?amount=25&to=EUR
```

Set `EXCHANGE_API_KEY` in the server environment or in `backend/config/config.php` before using this endpoint. Provider/network failures return JSON `503`; invalid input returns `400`, and unsupported currencies return `404`. The service tests use fake HTTP responses and do not require a live key:

```sh
php backend/rest/services/exchange_test.php
```

The generated OpenAPI document and Swagger UI are available at `http://localhost/web_project/backend/rest/public/v1/docs/swagger.php` and `http://localhost/web_project/backend/rest/public/v1/docs/`, respectively.

The routes do not implement authentication or authorization yet; configure access controls before exposing this API beyond local development. User passwords are hashed when saved and omitted from service responses.

The service tests use in-memory DAO doubles and require no database:

```sh
php backend/rest/services/test.php
```

## Database design

### Entity-Relationship Overview & Photo Support
1. **Users (`1` to `N`) Items**: A user (seller/admin) can list multiple thrift items, but each item belongs to a single seller.
2. **Categories (`1` to `N`) Items**: A category encompasses many thrift items, while each item belongs to one category.
3. **Items (`1` to `N`) Item Images**: An item can have multiple photos uploaded (stored via external URLs/paths), with an `is_primary` toggle to designate the main listing thumbnail.
4. **Users (`1` to `N`) Orders**: A buyer can place multiple orders over time.
5. **Items (`1` to `N`) Orders**: Unique thrift inventory items link directly to their corresponding sales order.
6. **Users (`1` to `N`) Reviews**: Users can leave multiple reviews and ratings, each mapped to a specific product.

### Schema Summary
* **`users`**: Manages accounts, credentials, and access permissions (`user`, `admin`, `seller`).
* **`categories`**: Organizes inventory into structured groups.
* **`items`**: Tracks product titles, descriptions, pricing, and live availability status (`available`/`sold`).
* **`item_images`**: Stores references/paths to product photographs, enabling multi-image listings.
* **`orders`**: Keeps a log of financial transactions and buyer history.
* **`reviews`**: Stores scoring feedback (1-5) and buyer commentary.

<img src="docs/images/erd.png" alt="ER Diagram" width="500">